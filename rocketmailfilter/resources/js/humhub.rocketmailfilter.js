humhub.module('rocketmailfilter.unread', function(module, require, $) {

    var Widget = module.require('ui.widget').Widget;
    var Filter = require('ui.filter').Filter;

    var MyFilter = Filter.extend();

    MyFilter.prototype.init = function() {
        this.hiddenInput = this.$.find('[name=unread]');
        this.mailFilterForm = Widget.instance('#mail-filter-root').$.find('form');
        this.mainCheckbox = this.$.find('[name=unread-toggle]');
        this.cosmeticCheckbox = this.addFakeCosmeticCheckbox();
        this.placeUnreadToggle();
    };

    MyFilter.prototype.placeUnreadToggle = function() {
        var targetEl = $('#mail-conversation-overview .panel-heading').children().first();
        this.$.detach().insertAfter(targetEl);
        this.$.removeClass('hidden');
    }

    MyFilter.prototype.triggerChange = function() {
        this.toggleInputValue();
        this.cosmeticCheckbox.prop('checked', this.isChecked());
        if (this.isChecked()) {
            this.addFakeInput();
        } else {
            this.removeFakeInput();
        }
        Widget.instance('#mail-filter-root').triggerChange();
    };

    MyFilter.prototype.addFakeInput = function() {
        if (this.mailFilterForm.find('[name="unread"]').length) {
            return;
        }
        this.mailFilterForm.prepend(this.hiddenInput);
    };

    MyFilter.prototype.removeFakeInput = function() {
        this.mailFilterForm.find('[name="unread"]').remove();
    };

    MyFilter.prototype.addFakeCosmeticCheckbox = function() {
        var self = this;
        var checkbox = $('#rocketmailfilter-filter-checkbox');
        var parentDiv = checkbox.closest('div.form-check');
        this.mailFilterForm.prepend(parentDiv);
        parentDiv.removeClass('hidden');
        checkbox.on('change', function() {
            self.mainCheckbox.click();
        });

        return checkbox;
    };

    MyFilter.prototype.toggleInputValue = function() {
        this.hiddenInput.val(this.isChecked() ? "0" : "1");
    };

    MyFilter.prototype.isChecked = function () {
        return Number(this.hiddenInput.val()) !== 0;
    }

    module.export = MyFilter;
});
