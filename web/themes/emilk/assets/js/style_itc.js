(function ($) {
    $(document).ready(function () {
        var pageloader = $("#pageloader");
        pageloader.liveloader({
            bgcolor: '#EEEEEE',
            bordercolor: '#3B3E47',
            color: '#82c341',
            faspeed: 'slow',
            fafont: 'spinner',
            faeffect: 'spin',
            fasize: 'fa-4x'
        });

        var tooltip = $('[data-toggle="tooltip"]');
        if (tooltip.length) {
            $(tooltip).tooltip();
        }

        /*var filestyle = $("input[type='file']");
         if (filestyle.length) {
         $("input[type='file']").filestyle({
         buttonName: "btn-primary",
         icon: false,
         buttonText: "Browse"
         });
         }*/

        var window_h = $(window).height();
        var header = $(".menu-wrap").outerHeight();
        var filterDiv = $("#filter");
        var filter = 0;
        if (filterDiv.length) {
            filter = $("#filter").outerHeight() - 9;
        }
        //console.log(filter);            
        var footer = $("footer").outerHeight();
        var panel_header = $(".panel-main .panel-heading").outerHeight();
        var panel_footer = $(".panel-main > .panel-footer").outerHeight();
        var panel_margin = 40;
        var panel_body = $(".panel-main > .panel-body");
        var panel_search = $(".panel-main > .panel-body .large-search").outerHeight();
        var panel_table = $(".panel-main > .panel-body .table-responsive");
        var panel_form_table = $(".panel-main > .panel-body .form-grid .table-responsive");
        var panel_table_b = $(".panel-main > .panel-body .kv-panel-before").outerHeight();
        var panel_table_a = $(".panel-main > .panel-body .kv-panel-after").outerHeight();
        var panel_side_header = $(".panel-sidebar .panel-heading").outerHeight();
        var panel_side_footer = $(".panel-sidebar .panel-footer").outerHeight();
        var panel_side_body = $(".panel-sidebar > .panel-body");
        var panel_btn = $(".panel-button").outerHeight();
        var pgc_body = $(".pgc-modal .modal-body");
        var rparea = $('.panel-main > .panel-body .report-area').outerHeight();

//        $(panel_body).css("height", window_h - header - filter - panel_header - panel_margin - footer);
//        $(panel_table).css("height", window_h - header - filter - panel_header - panel_search - panel_table_b - panel_table_a - panel_margin - footer - 2);
        $(panel_form_table).css({
            "max-height": "none",
            "height": "auto"
        });
        $(".panel-main > .panel-body .table-rate-chart").css("height", window_h - header - filter - panel_header - panel_table_b - panel_table_a - panel_margin - footer - 58);
        $(panel_side_body).css("height", window_h - header - filter - panel_btn - panel_side_header - panel_side_footer - panel_margin - footer);
        $(pgc_body).css({
            "max-height": window_h - 200,
            "overflow-y": "auto"
        });
//        $('.panel-main > .panel-body .report-grid').css("height", window_h - header - filter - panel_header - panel_footer - panel_margin - rparea - 30);
        $(".pgc-modal").on("shown.bs.modal", function () {
            var window_h = $(window).height();
            var pgc_header = $(".pgc-modal .modal-header").outerHeight();
            var pgc_body = $(".pgc-modal .modal-body");
            var pgc_footer = $(".pgc-modal .modal-footer").outerHeight();
            $(pgc_body).css({
                "max-height": window_h - pgc_header - pgc_footer - 65,
                "overflow-y": "auto"
            });
        });

        $(window).resize(function () {
            var window_h = $(window).height();
            var header = $(".menu-wrap").outerHeight();
            var footer = $("footer").outerHeight();
            var panel_header = $(".panel-main .panel-heading").outerHeight();
            var panel_footer = $(".panel-main > .panel-footer").outerHeight();
            var panel_margin = 40;
            var panel_body = $(".panel-main > .panel-body");
            var panel_search = $(".panel-main > .panel-body .large-search").outerHeight();
            var panel_table = $(".panel-main > .panel-body .table-responsive");
            var panel_form_table = $(".panel-main > .panel-body .form-grid .table-responsive");
            var panel_table_b = $(".panel-main > .panel-body .kv-panel-before").outerHeight();
            var panel_table_a = $(".panel-main > .panel-body .kv-panel-after").outerHeight();
            var panel_side_header = $(".panel-sidebar .panel-heading").outerHeight();
            var panel_side_footer = $(".panel-sidebar .panel-footer").outerHeight();
            var panel_side_body = $(".panel-sidebar > .panel-body");
            var panel_btn = $(".panel-button").outerHeight();
            var pgc_header = $(".pgc-modal .modal-header").outerHeight();
            var pgc_body = $(".pgc-modal .modal-body");
            var pgc_footer = $(".pgc-modal .modal-footer").outerHeight();
            var rparea = $('.panel-main > .panel-body .report-area').outerHeight();

//            $(panel_body).css("height", window_h - header - filter - panel_header - panel_margin - footer);
//            $(panel_table).css("height", window_h - header - filter - panel_header - panel_search - panel_table_b - panel_table_a - panel_margin - footer - 2);
            $(panel_form_table).css({
                "max-height": "none",
                "height": "auto"
            });
            $(".panel-main > .panel-body .table-rate-chart").css("height", window_h - header - filter - panel_header - panel_table_b - panel_table_a - panel_margin - footer - 58);
            $(panel_side_body).css("height", window_h - header - filter - panel_btn - panel_side_header - panel_side_footer - panel_margin - footer);
            $(pgc_body).css({
                "max-height": window_h - pgc_header - pgc_footer - 65,
                "overflow-y": "auto"
            });
//            $('.panel-main > .panel-body .report-grid').css("height", window_h - header - filter - panel_header - panel_footer - panel_margin - rparea - 30);
        });

        $('.dropdown-submenu a.dropdown-toggle').on("click", function (e) {
            $(this).parent().toggleClass("open");
            $(this).parent().find(".dropdown-submenu").removeClass("open");
            $(this).parent().siblings(".dropdown-submenu").removeClass("open");
            $(this).parent().siblings(".dropdown-submenu").find(".dropdown-submenu").removeClass("open");
            e.stopPropagation();
            e.preventDefault();
        });

        $('.table-responsive').on('show.bs.dropdown', function () {
            $('.table-responsive').css("min-height", "300px");
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
            $('.table-responsive').css("min-height", "none");
        });

        /* Map Chart */
        var map = $('#map_div');
        if (map.length) {
            google.charts.load('current', {packages: ['map']});
            google.charts.setOnLoadCallback(drawMap);

            function drawMap() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Address');
                data.addColumn('string', 'Location');
                data.addColumn('string', 'Marker');

                data.addRows([
                    ['Varanasi, Uttar Pradesh', 'Varanasi, Uttar Pradesh', 'blue'],
                    ['Kanpur, Uttar Pradesh', 'Kanpur, Uttar Pradesh', 'green'],
                    ['Lucknow, Uttar Pradesh', 'Lucknow, Uttar Pradesh', 'green'],
                    ['Jhansi, Uttar Pradesh', 'Jhansi, Uttar Pradesh', 'green'],
                    ['Agra, Uttar Pradesh', 'Agra, Uttar Pradesh', 'blue'],
                    ['Allahabad, Uttar Pradesh', 'Allahabad, Uttar Pradesh', 'blue'],
                    ['Gorakhpur, Uttar Pradesh', 'Gorakhpur, Uttar Pradesh', 'green']
                ]);

                var options = {
                    mapType: 'styledMap',
                    zoomLevel: 7,
                    showTooltip: true,
                    showInfoWindow: true,
                    useMapTypeControl: false,
                    icons: {
                        blue: {
                            normal: '../themes/emilk/assets/images/Map-Marker-Ball-Pink-icon.png',
                            selected: '../themes/emilk/assets/images/Map-Marker-Ball-Pink-icon.png'
                        },
                        green: {
                            normal: '../themes/emilk/assets/images/Map-Marker-Ball-Green-icon.png',
                            selected: '../themes/emilk/assets/images/Map-Marker-Ball-Green-icon.png'
                        }
                    },
                    maps: {
                        styledMap: {
                            name: 'Styled Map', // This name will be displayed in the map type control.
                            styles: [
                                {
                                    "featureType": "all",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "saturation": -100
                                        },
                                        {
                                            "gamma": 0.8
                                        }
                                    ]
                                }
                            ]
                        }
                    }
                };
                var map = new google.visualization.Map(document.getElementById('map_div'));
                map.draw(data, options);
            }
        }

        /* Bar and Line dual chart */
        var line_chart = $('#container');
        if (line_chart.length) {
            Highcharts.chart('container', {
                chart: {
                    zoomType: 'xy',
                    height: '530px'
                },
                title: {
                    text: 'Fat / Snf Chart'
                },
                xAxis: [{
                        categories: ['15 Sep', '16 Sep', '17 Sep', '18 Sep', '19 Sep', '20 Sep',
                            '21 Sep', '22 Sep', '23 Sep', '24 Sep', '25 Sep', '26 Sep'],
                        crosshair: true
                    }],
                yAxis: [{// Primary yAxis
                        labels: {
                            format: '{value}',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        title: {
                            text: '',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        }
                    }, {// Secondary yAxis
                        title: {
                            text: '',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        opposite: true
                    }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 120,
                    verticalAlign: 'top',
                    y: 100,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                series: [{
                        name: 'Fat',
                        type: 'spline',
                        color: '#3B3E47',
                        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6]
                    }, {
                        name: 'Snf',
                        type: 'spline',
                        color: '#82c341',
                        data: [59.0, 38.2, 45.2, 60.6, 144.0, 158.5, 135.6, 162.5, 183.3, 196.3, 153.9]
                    }]
            });
        }



        $("[data-toggle='toggle']").click(function () {
            $("#action").toggleClass('in');
        });
    });
    $(document).on('ready pjax:success', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('.check_length_with_mex').bind("keyup", function (e) {
        var this_id = $(this).attr('id');
        var filter = /^\d*(?:\.\d{1,2})?$/;
        var mob_num = $(e.target).val();
        if (filter.test(mob_num)) {
            if (mob_num.length < 10) {
                $('.field-' + this_id + ' .help-block').attr('title', $('.field-' + this_id + ' label').text() + ' must contain exactly 10 digits').text($('.field-' + this_id + ' label').text() + ' must contain minimum 10 digits');
            } else if (mob_num.length > 16) {
                $('.field-' + this_id + ' .help-block').attr('title', $('.field-' + this_id + ' label').text() + ' must contain exactly 10 digits').text($('.field-' + this_id + ' label').text() + ' must contain maximum 16 digits');
            } else {
                $('.field-' + this_id + ' .help-block').attr('title', '').text('');
            }
        } else {
            $('.field-' + this_id + ' .help-block').attr('title', $('.field-' + this_id + ' label').text() + ' is not valid').text($('.field-' + this_id + ' label').text() + ' is not valid');
        }
    });
})(jQuery);

