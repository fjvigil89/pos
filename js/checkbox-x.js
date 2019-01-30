/*!
 * @copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2015
 * @version 1.5.4
 *
 * An extended checkbox plugin for bootstrap with three states and additional styles.
 *
 * For more JQuery/Bootstrap plugins and demos visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";
    var CheckboxX = function (element, options) {
        var self = this;
        self.$element = $(element);
        self.disabled = self.$element.attr('disabled') || self.$element.attr('readonly');
        self.initialValue = self.$element.val();
        self.init(options);
    };

    CheckboxX.prototype = {
        constructor: CheckboxX,
        init: function (options) {
            var self = this, $el = self.$element, isCbx = $el.is(':checkbox'), val = parseInt($el.val()),
                css = options.inline ? 'cbx-container' : 'cbx-container cbx-block';
            self.options = options;
            self.clearEvents();
            if (isCbx && val !== 0 && val !== 1) {
                $el.attr('checked', false).prop('indeterminate', options.threeState);
            }
            if (isCbx && options.useNative) {
                $el.on('change.checkbox', function () {
                    self.change();
                }).removeClass('cbx-loading');
                self.setCheckboxProp($el.val());
                return;
            }
            if (self.$container === undefined) {
                self.$container = $(document.createElement("div")).addClass(css).html(self.render());
                self.$container.insertBefore($el).append($el);
                $el.hide();
            }
            else {
                self.$container.before($el).addClass(css).html(self.render()).append($el);
            }
            $el.removeClass('cbx-loading');
            self.$cbx = self.$container.find('.cbx');
            $el.closest('form').off('reset.checkbox').on('reset.checkbox', function () {
                self.reset();
            });
            self.$cbx.off('click.checkbox').on('click.checkbox', function () {
                if (isCbx && !options.enclosedLabel && !options.useNative && !self.disabled) {
                    $el.trigger('change');
                    return;
                }
                if (!isCbx && !self.enclosedLabel) {
                     self.change();
                }
            }).on('keyup.checkbox', function (e) {
                if (e.which === 32) {
                    self.change();
                    e.preventDefault();
                }
            });
            if (isCbx && !options.useNative) {
                $el.on('change.checkbox', function () {
                    self.change();
                });
            } else {
                if (!isCbx) {
                    $el.on('click.checkbox', function () {
                        self.change();
                    });
                }
            }
        },
        change: function () {
            var self = this, $el = self.$element, newVal;
            if (self.disabled) {
                return;
            }
            newVal = self.getValue();
            $el.val(newVal);
            self.validateCheckbox(newVal);
            if (!self.options.useNative) {
                self.$cbx.html(self.getIndicator());
            }
        },
        getValue: function () {
            var self = this, val = parseInt(self.$element.val());
            switch (val) {
                case 0:
                    return self.options.threeState ? null : 1;
                case 1:
                    return 0;
                default:
                    return 1;
            }
        },
        setCheckboxProp: function (newVal) {
            var self = this, $el = self.$element;
            $el.prop('indeterminate', false).prop('checked', false);
            switch (newVal) {
                case 1:
                    $el.prop('checked', true);
                    break;
                case 0:
                    break;
                default:
                    $el.prop('indeterminate', true);
                    break;
            }
        },
        validateCheckbox: function (newVal) {
            var self = this, $el = self.$element, isCbx = $el.is(':checkbox');
            if (!isCbx) {
                $el.trigger('change');
                return;
            }
            self.setCheckboxProp(newVal);
        },
        reset: function () {
            var self = this, $el = self.$element;
            $el.val(self.initialValue);
            self.refresh();
            $el.trigger('reset.checkbox');
        },
        clearEvents: function () {
            var self = this, $el = self.$element, 
                $cbx = self.$container ? self.$container.find('.cbx') : null;
            $el.off('.checkbox').closest('form').off('.checkbox');
            if ($cbx) {
                $cbx.off('.checkbox');
            }
        },
        destroy: function () {
            var self = this, $el = self.$element;
            self.clearEvents();
            self.$container.before($el).remove();
            $el.removeData().show();
        },
        refresh: function (options) {
            var self = this;
            if (options) {
                self.init($.extend(self.options, options));
            } else {
                self.disabled = self.$element.attr('disabled') || self.$element.attr('readonly');
                self.init(self.options);
            }
            self.initialValue = self.$element.val();
        },
        getIndicator: function () {
            var self = this,
                options = self.options,
                icon = options.iconUnchecked,
                val = parseInt(self.$element.val());
            if (val === 1) {
                icon = options.iconChecked;
            }
            else {
                if (val !== 0 && self.options.threeState) {
                    icon = options.iconNull;
                }
            }
            return '<span class="cbx-icon">' + icon + '</span>';
        },
        render: function () {
            var self = this,
                icon = self.getIndicator(),
                size = self.options.size,
                tab = self.disabled || !self.options.tabindex ? '' : ' tabindex="' + self.options.tabindex + '"',
                css = 'cbx cbx-' + size + (self.disabled ? ' cbx-disabled' : ' cbx-active');
            return '<div class="' + css + '"' + tab + '>' + icon + '</div>';
        }
    };

    $.fn.checkboxX = function (option) {
        var args = Array.apply(null, arguments);
        args.shift();        
        return this.each(function () {
            var $this = $(this), defaults,
                data = $this.data('checkboxX'),
                options = typeof option === 'object' && option;

            if (!data) {
                defaults = $.extend({}, $.fn.checkboxX.defaults);
                data = new CheckboxX(this, $.extend(defaults, options, $this.data()));
                $this.data('checkboxX', data);
            }

            if (typeof option === 'string') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.checkboxX.defaults = {
        threeState: true,
        inline: true,
        iconChecked: '<i class="glyphicon glyphicon-ok"></i>',
        iconUnchecked: ' ',
        iconNull: '<div class="cbx-icon-null"></div>',
        size: 'md',
        enclosedLabel: false,
        useNative: false,
        tabindex: 1000
    };

    $.fn.checkboxX.Constructor = CheckboxX;

    $('input[data-toggle="checkbox-x"]').addClass('cbx-loading');

    $(document).ready(function () {
        $('input[data-toggle="checkbox-x"]').checkboxX();
    });
})(window.jQuery);