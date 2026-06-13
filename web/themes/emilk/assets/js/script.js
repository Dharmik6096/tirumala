var initDepdropMs;
(function ($) {
    window.handleDepdropBeforeSend = function (options) {
        const parentIds = options.depends || [];
        const selfId = options.selfId;

        const currentParentVals = parentIds.map(id => {
            const val = $(`#${id}`).val();
            return (val === null || val === undefined || val === '') ? '' : val;
        });
        var parentVal = $(`#` + parentIds[0]).val();
        // if (!currentParentVals[0]) { // Check if any parent is empty
        if (isEmpty(parentVal)) { // Check if first parent is empty
            resetChildDropdown(selfId);
            return false;
        }

        const stateKey = parentIds.concat(currentParentVals).join('|');
        if (!window.depdropStateTracker) {
            window.depdropStateTracker = {};
        }

        const self = $(`#${selfId}`);
        const shouldAllowRequest = (
                !window.depdropStateTracker[selfId] ||
                window.depdropStateTracker[selfId].stateKey !== stateKey ||
                self.find('option').length <= 1 ||
                self.prop('disabled')
                );

        if (shouldAllowRequest) {
            self.prop('disabled', false);

            // Update tracker
            window.depdropStateTracker[selfId] = {
                stateKey: stateKey,
                timestamp: Date.now()
            };
            return true;
        }
        return false;
    };

    function resetChildDropdown(id) {
        var self = $('#' + id);
        if (self.data('select2')) {
            self.val(null).trigger('select2:select');
            self.trigger('select2:unselect');
            self.trigger('select2:close');
            self.find('option').remove();
            self.prop('disabled', true);
        }
    }

    function isEmpty(value) {
        if (value === null || value === undefined) {
            return true;
        } else if (typeof value === 'string') {
            return value.length === 0;
        } else if (Array.isArray(value)) {
            if (value.length === 0) {
                return true;
            }
            return value.every(item => isEmpty(item));
        } else if (typeof value === 'object') {
            const keys = Object.keys(value);
            if (keys.length === 0) {
                return true;
            }
            return keys.every(key => {
                const propValue = value[key];
                return isEmpty(propValue);
            });
        }
        return false;
    }

    initDepdropMs = function (id, text, val) {
        var $s2 = $('#' + id), $s2cont = $('#' + id).parent('.form-group'), ph = '...';

        $s2.on('depdrop.beforeChange', function () {
            $s2.find('option').attr('value', ph).html(text);
            $s2.val(ph);
            $s2.multiselect('select', ph);
            $s2cont.removeClass('kv-loading').addClass('kv-loading');
        }).on('depdrop.afterChange', function (event, id, value) {
            $s2.multiselect('rebuild');
            if (value != '')
            {
                $s2.multiselect('enable');
            } else
            {
                $s2.multiselect('disable');
            }
            if (JSON.parse(val) != null)
            {
                $s2.multiselect('select', JSON.parse(val), true);
            }
            $s2cont.removeClass('kv-loading');
        });
    };
    $(document).ready(function () {
        // if (!$("div").hasClass('not_ellipsis')) {
        //     var num;
        //     var $tds;
        //     var tdsx = $("table.kv-grid-table").children('tbody').children('tr:first-child').children('td').length;
        //     $("table.kv-grid-table").each(function (i, t) {
        //         $tds = $("td", t);
        //         num = $tds.length;
        //         if (tdsx > 1) {
        //             for (var i = tdsx; i < num; i++) {
        //                 var t = $tds.eq(i).text();
        //                 if ($tds.eq(i).text().length > 10 && $tds.eq(i).text().indexOf('<div') < 0 && $tds.eq(i).html().indexOf('<div') < 0)
        //                 {
        //                     $tds.eq(i).text(t.substr(0, 10));
        //                     $tds.eq(i).attr("title", t);
        //                 }

        //             }
        //         }

        //     });
        // }

        function applyEllipsis() {
            $("td.is_ellipsis").each(function () {
                var $td = $(this);
                if (!$td.attr("title")) {
                    var text = $td.text().trim();
                    if (text.length > 10) {
                        $td.attr("title", text);
                        $td.text(text.substr(0, 10) + '...');
                    }
                }
            });
        }
        applyEllipsis();

        $('.shift select option[value=\'3\']').remove();
        var toolbar = $('#importModal');
//        $toolbar.parent().after($toolbar);
        // $(toolbar).remove();
        var div = $(toolbar).parent().closest('div');
        $(toolbar).remove();
        $(div).append($(toolbar));
        //  console.log();

        $('form :input:enabled:visible:first').focus();

        $("button[button='save']").click(function () {
            $(this).closest("form").submit();
        });

        $("#tblsubcenter-bank_code").on('change', function () {
            $("#tblsubcenter-ifsc").val("");
        });

        $("#tbldcs-bank_code").on('change', function () {
            $("#tbldcs-ifsc").val("");
        });

        $("#tblunions-bank_code").on('change', function () {
            $("#tblunions-ifsc").val("");
        });

        $("#tblfederations-bank_code").on('change', function () {
            $("#tblfederations-ifsc").val("");
        });

        $(function () {
            $(".shortcut-main").placeShortcut({});
        });

        var specialDecimalKeys = new Array();
        specialDecimalKeys.push(8);
        $(document).on("keypress", ".number-validate", function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
            /*var v = this.value;
             var value = new RegExp('^\d+(?:\.\d{2})?$');
             alert(value.test(v));
             return value;*/
        });
        $(document).on("cut copy paste", '.number-validate', function (e) {
            e.preventDefault();
        });
        var specialKeys = new Array();
        specialKeys.push(8);
        $(".qty-validate").bind("keypress", function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialKeys.indexOf(keyCode) != -1) || keyCode == 9);
            return ret;
        });

//        $("#w1-cols").hide();
//        $("#w3").click(function (event) {
//            event.preventDefault();
//            $('#w4').append($('#w1-cols-list li'));
//            $("#w4 li.divider").prev().hide();
//        });
        $('form').on('submit', function (e) {
            var type = $(this).attr('method');
            if (type.toLowerCase() === 'get')
            {
                $(this).find('input[name=q]').remove();
                encryptData($(this));
                if ($(this).attr('id') != 'report-form') {
                    $(this).unbind().submit();
                }
            }
        });
        $(document).on('submit', 'form[data-pjax]', function (event) {
            $(this).find('input[name=q]').remove();
            encryptData($(this));
            $.pjax.submit(event, '.grid-content');
        });
        function encryptData(frm)
        {
            var olddata = false;
            if (frm.parents('.grid-search').length == 0)
            {
                var searchfrm = $('.grid-search form');
                olddata = searchfrm.serialize();
            }
            if (olddata == false && frm.parents('#search_filter').length == 0)
            {
                var searchfrm = $('#search_filter form');
                olddata = searchfrm.serialize();
            }
            var formString = frm.serialize();
            if (olddata !== false && olddata != '')
            {
                formString = olddata + '&' + formString; // All data in one String.
            }
            $.each(SearchParam, function (index, value) {
                formString = index + '=' + value + '&' + formString;
            });
            var encrypted = window.btoa(formString);
            encrypted = Base64UrlEncode(encrypted);
            if (frm.attr('id') != 'report-form') {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'qstr',
                    name: 'q'
                }).appendTo(frm);
                $('#qstr').val(encrypted);
                frm.find('select').not($('#qstr')).attr('disabled', 'disabled');
                frm.find('input').not($('#qstr')).attr('disabled', 'disabled');
            }
        }
        function Base64UrlEncode(s)
        {
            s = s.split('=')[0]; // Remove any trailing '='s
            s = s.replace('+', '-'); // 62nd char of encoding
            s = s.replace('/', '_'); // 63rd char of encoding
            return s;

        }

        $.fn.preventEncryption = function ()
        {
            $(this).find('select').not($('#qstr')).attr('disabled', false);
            $(this).find('input').not($('#qstr')).attr('disabled', false);
            $('#qstr').remove();
        };

        $('.grid-content td:contains("Total")').parents('tr').css('font-weight', '600');
//        //$('.grid-content td:contains("Total")').parents('tr').find('td:first').text('');
//        var copy = $('.grid-content td:contains("Total"):not(:contains("-Total"))').parents('tr').clone();
//        $('.grid-content td:contains("Total"):not(:contains("-Total"))').parents('tr').remove();
//        $('.grid-content tbody').prepend(copy);

        function setHeight() {
            var page_height = $(".pagination").height();
            var toolbar_height = $(".kv-grid-toolbar").height();
            var search_height = 0;//$(".grid-search").height();
            if (page_height === null) {
                page_height = 0;
            } else {
                page_height += 28;
            }

            if (search_height === null) {
                search_height = 5;
                if (toolbar_height === null) {
                    search_height = 5;
                } else {
                    search_height = toolbar_height + 25;
                }
            } else {
                search_height += 70;
            }
//            $(".kv-grid-wrapper").height($(".panel-body").height() - search_height - page_height);
        }

        $(window).on("load", function () {
            $('.kv-grid-wrapper.kv-grid-container .table.table-bordered.table-hover.kv-grid-table.kv-table-wrap').each(function () {
                var min_height = $("thead").height();
                var table_height = $(this).height();
//                $(this).closest($(".kv-grid-wrapper.kv-grid-container")).css({"max-height": table_height + 5, "min-height": min_height + 80});
            });
            setHeight();
        });

        $(window).on('resize', function () {
            setHeight();
        });

        $(document).on('pjax:success', function () {
            setHeight();
            applyEllipsis();
        });
//window.setInterval(function(){setHeight();}, 1);

        $(".help-block").each(function (i, t) {
            var errorText = $(this).text();
            if (errorText != '') {
                $(this).attr('title', errorText);
            }
        });
        $(document).ajaxStop(function () {
            $('.depend-control').each(function () {
                $(this).attr('disabled', 'disabled');
            })
        })
    });

    $('.check_mobile_length').bind("keyup", function (e) {
        var this_id = $(this).attr('id');
        var filter = /^\d*(?:\.\d{1,2})?$/;
        var mob_num = $(e.target).val();
        if (filter.test(mob_num)) {
            if (mob_num.length != 10) {
                $('.field-' + this_id + ' .help-block').attr('title', $('.field-' + this_id + ' label').text() + ' must contain exactly 10 digits').text($('.field-' + this_id + ' label').text() + ' must contain exactly 10 digits');
            } else {
                $('.field-' + this_id + ' .help-block').attr('title', '').text('');
            }
        } else {
            $('.field-' + this_id + ' .help-block').attr('title', $('.field-' + this_id + ' label').text() + ' is not valid').text($('.field-' + this_id + ' label').text() + ' is not valid');
        }
    });

    $('.check_password_strength').on("keyup", function () {
        var this_id = $(this).attr('id');
        var password = $(this).val();
        var fieldContainer = $('.field-' + this_id);
        var errorBlock = fieldContainer.find('.invalid-feedback');
        var inputField = fieldContainer.find('input');
        var label = fieldContainer.find('label').text();
        var message = '';

        if (password.length < 8) {
            message = label + ' must be at least 8 characters long';
        } else if (!/[a-z]/.test(password)) {
            message = label + ' must contain at least one lowercase letter';
        } else if (!/[A-Z]/.test(password)) {
            message = label + ' must contain at least one uppercase letter';
        } else if (!/\d/.test(password)) {
            message = label + ' must contain at least one digit';
        } else if (!/[\W_]/.test(password)) {
            message = label + ' must contain at least one special character';
        }

        if (message) {
            errorBlock.html(message).show();
            inputField.removeClass('is-valid').addClass('is-invalid');
        } else {
            errorBlock.html('').hide();
            inputField.removeClass('is-invalid').addClass('is-valid');
        }
    });
    $('.24_hour_time_input').on("keyup", function () {
        var $this = $(this), id = $this.attr('id'), $help = $('.field-' + id + ' .help-block'),
                time = $this.val();
        $help.text('');
        if (time.length == 5 && !time.includes('_') && !/^([01]\d|2[0-3]):[0-5]\d$/.test(time)) {
            $help.text('Invalid time.');
        }
    });

    $(document).ready(function () {
        const Interaction_Block_Classes = 'div.disabled, div.no_pointer, div.readonly';
        const Target_Selectors = 'input, select';
        const isInteractionBlocked = ($element) => {
            return $element.is(':disabled') || $element.prop('readonly') || $element.closest(Interaction_Block_Classes).length > 0;
        };
        const updateElementTabindex = ($element) => {
            $element.attr('tabindex', isInteractionBlocked($element) ? '-1' : null);
        };
        $(Target_Selectors).each((index, element) => updateElementTabindex($(element)));
        const domChangeObserver = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                const $targetElement = $(mutation.target);
                if (($targetElement.is('input') || $targetElement.is('select')) && mutation.type === 'attributes' && (mutation.attributeName === 'readonly' || mutation.attributeName === 'disabled')) {
                    updateElementTabindex($targetElement);
                } else if ($targetElement.is('div') && mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    $targetElement.find(Target_Selectors).each((index, element) => updateElementTabindex($(element)));
                }
            });
        });
        const observerConfig = {
            attributes: true,
            attributeFilter: ['readonly', 'disabled', 'class'],
            subtree: true
        };
        domChangeObserver.observe(document.body, observerConfig);
    });
    $(document).on("input", ".two-decimal-validate", function () {
        if (!/^\d+(\.\d{0,2})?$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
    });
    $(document).on("input", ".one-decimal-validate", function () {
        if (!/^\d+(\.\d{0,1})?$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
    });
    $(document).on("input", ".three-decimal-validate", function () {
        if (!/^\d+(\.\d{0,3})?$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
    });
})(jQuery);

