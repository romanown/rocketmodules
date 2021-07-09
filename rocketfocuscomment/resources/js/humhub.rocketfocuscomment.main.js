humhub.module('rocketfocuscomment.main', function(module, require, $) {
    var event = require('event');
    var util = require('util');
    var showCommentsButtonsSelectors = '.show-all-link, a[data-action-click="comment.showMore"]';

    var getCommentIdParam = function() {
        return module.config['commentIdParam'];
    };

    var getCommentIdValue = function() {
        return $.trim(util.url.getUrlParameter(getCommentIdParam()));
    };

    var getFocusedCommentSelector = function() {
        return '#comment_' + getCommentIdValue();
    };

    var registerStreamRequestExtension = function() {
        event.on('humhub:afterInitModule', function(evt, m) {
            if (m.id !== 'humhub.modules.stream.Stream') {
                return false;
            }
            module.log.debug('Extend stream.StreamRequest module');
            var StreamRequest = require('stream.StreamRequest');
            var getRequestData = StreamRequest.prototype.getRequestData;
            StreamRequest.prototype.getRequestData = function () {
                var data = getRequestData.apply(this, arguments);
                var focusedCommentId = getCommentIdValue();
                if (focusedCommentId) {
                    data[getCommentIdParam()] = focusedCommentId;
                }
                return data;
            };
        });
    };

    var registerPassFocusedCommentIdAddition = function() {
        require('ui.additions').register('passCommentId', showCommentsButtonsSelectors, function($match) {
            var ctid = getCommentIdValue();
            if (!ctid) return;
            $match.each(function() {
                var $el = $(this);
                var oldActionUrl = $el.attr('data-action-url');
                if (oldActionUrl) {
                    var parsedUrl = new URL(oldActionUrl, 'http://example.com');
                    parsedUrl.searchParams.set(getCommentIdParam(), ctid);
                    var newActionUrl = parsedUrl.pathname + parsedUrl.search;
                    $el.attr('data-action-url', newActionUrl);
                    $el.data('action-url', newActionUrl);
                }
            });
        });
    };

    var registerCommentExtension = function() {
        event.on('humhub:modules:comment:afterInit', function(evt, m) {
            var $postContainer = $('#layout-content');
            if ($postContainer.data('observer')) {
                $postContainer.data('observer').disconnect();
            }
            var mutationConfig = { attributes: false, childList: true, subtree: true };
            var observer = new MutationObserver(function () {
                var selector = getFocusedCommentSelector();
                selector && $(selector).addClass('rocketfocuscomment-focus');
            });
            $postContainer.data('observer', observer);
            observer.observe($postContainer[0], mutationConfig);
            module.log.debug('Mutation observer initialized');
        });
    };

    var init = function() {
        registerPassFocusedCommentIdAddition();
        registerStreamRequestExtension();
        registerCommentExtension();
    };

    module.export({
        init: init
    });
});
