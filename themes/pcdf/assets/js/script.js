var initDepdropMs;
(function ($) {
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
        if (!$("div").hasClass('not_ellipsis')) {
            var num;
            var $tds;
            var tdsx = $("table.kv-grid-table").children('tbody').children('tr:first-child').children('td').length;
            $("table.kv-grid-table").each(function (i, t) {
                $tds = $("td", t);
                num = $tds.length;
                if (tdsx > 1) {
                    for (var i = tdsx; i < num; i++) {
                        var t = $tds.eq(i).text();
                        if ($tds.eq(i).text().length > 10 && $tds.eq(i).text().indexOf('<div') < 0 && $tds.eq(i).html().indexOf('<div') < 0)
                        {
                            $tds.eq(i).text(t.substr(0, 10));
                            $tds.eq(i).attr("title", t);
                        }

                    }
                }

            });
        }

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
        $(".number-validate").bind("keypress", function (e) {
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
                $(this).unbind().submit();
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
            $('<input>').attr({
                type: 'hidden',
                id: 'qstr',
                name: 'q'
            }).appendTo(frm);
            $('#qstr').val(encrypted);
            frm.find('select').not($('#qstr')).attr('disabled', 'disabled');
            frm.find('input').not($('#qstr')).attr('disabled', 'disabled');
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
        //$('.grid-content td:contains("Total")').parents('tr').find('td:first').text('');
        var copy = $('.grid-content td:contains("Total"):not(:contains("-Total"))').parents('tr').clone();
        $('.grid-content td:contains("Total"):not(:contains("-Total"))').parents('tr').remove();
        $('.grid-content tbody').prepend(copy);

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
})(jQuery);

