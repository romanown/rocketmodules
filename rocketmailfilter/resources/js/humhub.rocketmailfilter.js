humhub.module('rocketmailfilter.unread', function(module, require, $) {

    var Widget = module.require('ui.widget').Widget;
    var Filter = require('ui.filter').Filter;

    var MyFilter = Filter.extend();

    MyFilter.prototype.init = function() {
        this.mainCheckbox = this.$.find('[name=unread]');
        this.mailFilterForm = Widget.instance('#mail-filter-root').$.find('form');
        this.placeUnreadCheckbox();
        this.attachListeners();
    };

    MyFilter.prototype.placeUnreadCheckbox = function() {
        this.mailFilterForm.prepend(this.$.detach());
        this.$.removeClass('hidden');
    }

    MyFilter.prototype.attachListeners = function() {
        this.mainCheckbox.on('change', function() {
            Widget.instance('#mail-filter-root').triggerChange();
        });
    };

    module.export = MyFilter;
});
