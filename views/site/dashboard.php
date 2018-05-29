<?php
$this->title = 'Dashboard';

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$chart_url = Url::to(['load-chart']);
$results3 = !empty($results3) ? $results3 : [];
if (!empty($results4)) {
    foreach ($results4 as $res) {
        $cal_data[$res['dt']] = [$res['AvgFAT'], $res['AvgSNF'], $res['Qty']];
    }
} else {
    $cal_data = [];
}
$villages = array_column(array_values($results3), 'dcs_name');
$unions = array_column(array_values($results3), 'union_name');
$combined = array_map(function($a, $b) {
    return $a . '<br/>' . $b;
}, $villages, $unions);

$collection = array_column(array_values($results3), 'Qty');
$bmc_collection = array_column(array_values($results6), 'quantity');
$c_bmc = array_column(array_values($results6), 'bmc_name');

$bmc_dispatch = array_column(array_values($results7), 'quantity');
$d_bmc = array_column(array_values($results7), 'bmc_name');
$fat = array_column(array_values($results3), 'AvgFAT');
$snf = array_column(array_values($results3), 'AvgSNF');
$cal_data = json_encode($cal_data);

$dcs = array_column(array_values($results8), 'VillageName');
$unions = array_column(array_values($results8), 'union_name');
$graph_chart_dcs = array_map(function($a, $b) {
    return $a;
}, $dcs, $unions);
$DPUCount = array_column(array_values($results8), 'DPUCount');
$DPUCount = json_encode($DPUCount);
$CFCount = array_column(array_values($results8), 'CFCount');
$CFCount = json_encode($CFCount);
?>
<div class="panel-group row panel-fixed" id="filter">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h4 class="panel-title">
                <?= Yii::t('app', 'Data for PCDF') . ' ' ?> (<?= Yii::$app->controls->view_date($date) ?>)
                <a data-toggle="collapse" href="#collapse1" class="setting"><i class="fa fa-gear"></i></a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'post',
                ]);
                ?>
                <div class="filt">
                    <div class="">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                    </div>
                    <div class="">
                        <?= Yii::$app->controls->date($model, $form, 'date'); ?>
                    </div>
                    <div class="filt-btn">
                        <?= Yii::$app->controls->search(); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-main panel-dashboard">
    <div class="panel-body">
        <div class="row">
            <?php if (Yii::$app->session->get('organizations_type') !== 'UNION') { ?>
                <div class="col-sm-6">
                    <div class="flt">
                        <?=
                        $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_union',
                            'url' => $chart_url, 'container' => 'container1',
                            'date_range' => false, 'range2' => false,
                            'range_id1' => 'dt1',
                            'shift' => true, 'type' => 'column', 'title' => Yii::t('app', 'Unionwise Milk Collection')]);
                        ?>
                        <div id="container1" class="cont"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="flt">
                        <?=
                        $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_comparison',
                            'url' => $chart_url, 'container' => 'container2',
                            'date_range' => true, 'range2' => true,
                            'range_id1' => 'comp1', 'range_id2' => 'comp2',
                            'shift' => false, 'type' => 'column',
                            'title' => 'Compare Milk Collection']);
                        ?>
                        <div id="container2" class="cont"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="flt">
                        <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_datewise', 'url' => $chart_url, 'container' => 'container3', 'date_range' => true, 'range2' => false, 'shift' => false, 'type' => 'column', 'title' => 'Datewise Milk Collection']); ?>
                        <div id="container3" class="cont"></div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-6">
                    <div class="flt">
                        <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_comparison', 'url' => $chart_url, 'container' => 'container1', 'date_range' => true, 'range2' => true, 'shift' => false, 'type' => 'column', 'title' => 'Compare Milk Collection']); ?>
                        <div id="container1" class="cont"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="flt">
                        <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_datewise', 'url' => $chart_url, 'container' => 'container2', 'date_range' => true, 'range2' => false, 'range_id1' => 'comp1', 'range_id2' => 'comp2', 'shift' => false, 'type' => 'column', 'title' => 'Datewise Milk Collection']); ?>
                        <div id="container2" class="cont"></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clearfix mt25"></div>      
        <div class="row">
            <div class="col-sm-6">
                <div id="container5" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <div id="container6" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <div id="container7" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <div class="milk-collection">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th colspan="4">Monthly Milk Collection</th> 
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Union') ?></th>
                                    <th>Villages</th>
                                    <th>No of Pourers</th>
                                    <th>Monthly Milk Collection(ltr)</th>
                                </tr>
                            </thead>
                            <?php
                            if (!empty($results)) {
                                foreach ($results as $result) {
                                    ?>
                                    <tr>
                                        <td><?= $result['union_name'] ?></td>
                                        <td><?= $result['dcs_name'] ?></td>
                                        <td><?= $result['Member_Count'] ?></td>
                                        <td><?= $result['Qty'] ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr><td colspan="4">Data not available.</td></tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'No. of Societies') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($results) ? $results[0]['Dcs_Count'] : 0 ?></h3>
                        <p><b>M:</b> <?= !empty($results) ? $results[0]['Dcs_Count_M'] : 0 ?> | <b>E:</b> <?= !empty($results) ? $results[0]['Dcs_Count_E'] : 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="collection">                                
                    <div class="tbl-cell">
                        <p>No. of Pourers</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($results) ? $results[0]['Total_Member'] : 0 ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($results) ? $results[0]['UnionQty'] : 0 ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Dispatch(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($results) ? $results[0]['UnionDisQty'] : 0 ?></h3>
                        <p><b>M:</b> <?= !empty($results) ? $results[0]['Dcs_DisQty_M'] : 0 ?> | <b>E:</b> <?= !empty($results) ? $results[0]['Dcs_DisQty_E'] : 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total BMC Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($results) ? $results[0]['BmcQty'] : 0 ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Collection vs Installed') ?></p>
                        <p><h4><?= (!empty($results) ? $results[0]['Dcs_Count'] : 0) . '/' . (!empty($results) ? $results[0]['Install_Count'] : 0) ?></h4></p>
                        <p><?= Yii::t('app', 'Collection vs Dispatch') ?></p>
                        <p><h4><?= (!empty($results) ? $results[0]['Dcs_Count'] : 0) . '/' . (!empty($results) ? $results[0]['Dcs_DisQty_M'] + $results[0]['Dcs_DisQty_E'] : 0) ?></h4></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">            
            <div class="col-sm-12">
                <div id="reconciliation" class="cont"></div>
            </div>            
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="cal-header">Avg. FAT, Avg. SNF and Qty for</div>
                <div id="calendar"></div>
            </div>
            <!--            <div class="col-sm-6">
                            <div id="map_div"></div>
                        </div>-->
        </div>
    </div>
</div>
<div id="chartModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id='modal-title'></h4>
            </div>
            <div class="modal-body" id='modal-body'>
            </div>
        </div>

    </div>
</div>
<?php
$script = "  
    var a = " . json_encode($collection) . ";
    var collection = a.map(function (x) { 
        return parseFloat(x, 10); 
    });
    barChart('container5','" . Yii::$app->controls->view_date($date) . " Milk Collection '," . json_encode($combined) . ",collection);
    function barChart(cont,text,xdata,ydata){
    var bar_chart = $('#'+cont);
        if (bar_chart.length) {
            Highcharts.chart(cont, {
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: text
                },
                xAxis: [{
                        categories: xdata,
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
                        opposite: false,
                    }
                ],
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
                            name: 'QTY(ltr)',
                            type: 'column',
                            color: '#3a7bd5',
                            yAxis: 1,
                            data: ydata,
                            tooltip: {
                                valueSuffix: ' lt'
                            }

                    }]
            });
        }
     }
     
      function drawChart(id,cntr,url,type)
      {
        var datastring = $('#'+id).serialize();
        var union= $('#dashboard-union_code').val();
        $.ajax({
                     type: 'post',
                     url: url,
                     data: datastring+'&sp='+id+'&union='+union,
                     success: function(data) {
                    
                        var index=$('#'+cntr).data('highcharts-chart');
                        var chart=Highcharts.charts[index];
                        var vals=[];
                        var color='3a7bd5';
                        var suf='';
                        while( chart.series.length > 0 ) {
                            chart.series[0].remove( false );
                        }
                        $.each(data.res, function (key, val) {
                        vals = val.map(function (x) { 
                            return parseFloat(x, 10); 
                        });
                        if(key.toLowerCase()==='qty')
                        {
                            suf='(ltr)';
                        }
                        else
                        {
                            suf='';
                        }
                        
                        chart.addSeries({  
                            type: type,
                            name: key.toUpperCase()+suf,
                            data: vals,
                            yAxis:1,
                            color:'#'+color,
                        }, false);
                        color=parseInt(color)+003333;
                       
                        });
                        chart.xAxis[0].setCategories(data.lbl[0]);
                         chart.redraw();
                     },
                     error:function(data){
                                 //alert('Your data has not been submitted..Please try again');
                            }
        });
      }
      
//calender functions
var cal_data=" . $cal_data . ";
var chartModal = $('#chartModal').modal({
        show: false
    });
//calendar widget
    $('#calendar').fullCalendar({
    dayRender: function(date, cell) {
        var d=date.format('YYYY-MM-DD');
        if(d in cal_data)
        {
            cell.append('<div class=\"cal-data\"><span class=\"label text-success\" title=\"Avg FAT\">'+cal_data[d][0]+'</span><span class=\"label text-danger\" title=\"Avg SNF\">'+cal_data[d][1]+'</span><span class=\"label text-info\" title=\"Qty(ltr)\">'+cal_data[d][2]+'</span></div>');
        }
      },
    defaultDate: moment('" . $date . "'),
    viewRender: function (view, element) {
       var b = $('#calendar').fullCalendar('getDate');
       var m=b.format('Y-MM');
       var union= $('#dashboard-union_code').val();
            $('.fc-day-grid').html('<div class=\"text-center mt35\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
        $.ajax({
                     type: 'post',
                     url: '" . Url::to(['/site/load-month-data']) . "',
                     data: 'm='+m+'&union='+union,
                     success: function(data) {

                         var obj1 = data;
                         if (obj1.status == 'success')
                         {
                          cal_data=obj1.res;                                                   
                         }
                        let cview = $('#calendar').fullCalendar('getView');  
                        cview.unrenderDates();
                        cview.renderDates();
                        $(window).trigger('resize'); 
                         

                     },
                     error:function(data){
                                 //alert('Your data has not been submitted..Please try again');
                             }
         });
    },
    dayClick: function(date, jsEvent, view) {
       var dt=date.format();
       var union= $('#dashboard-union_code').val();
        $('#modal-title').html('Data for '+date.format('DD-MM-YYYY'));
        $('#modal-body').html('<div class=\"text-center\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
        $.ajax({
                     type: 'post',
                     url: '" . Url::to(['/site/load-dcs-data']) . "',
                     data: 'dt='+dt+'&union='+union,
                     success: function(data) {

                         var obj1 = data;
                          if (obj1.status == 'success')
                         {
                          
                            var html='<div class=\"milk-collection\">'+
                            '<div class=\"table-responsive\"><table class=\"table table-striped\">'+
                            '<thead><tr><th>Union</th><th>Villages</th><th>Avg FAT</th><th>Avg SNF</th><th>Milk Collection (ltr)</th></tr></thead>';
                           $.each(obj1.res, function(index, value) {
                            html=html+'<tr>'+
                                '<td>'+value.union_name+'</td>'+
                                '<td>'+value.dcs_name+'</td>'+
                                '<td>'+value.AvgFAT+'</td>'+
                                '<td>'+value.AvgSNF+'</td>'+
                                '<td>'+value.total_qty+'</td>'+
                            '</tr>';
                             });
                            
                        html=html+'</table></div></div>';
                       $('#modal-body').html(html);
                         }
                         else{
                            $('#modal-body').html('Data not available.');
                         }

                     },
                     error:function(data){
                                 //alert('Your data has not been submitted..Please try again');
                             }
         });
        chartModal.modal('show');
    }
});



var bmc_c = " . json_encode($bmc_collection) . ";
var bmc_collection = bmc_c.map(function (x) { 
    return parseFloat(x, 10); 
});
barChart('container6','" . Yii::$app->controls->view_date($date) . " BMC Collection '," . json_encode($c_bmc) . ",bmc_collection);
function barChart(cont,text,xdata,ydata){
var bar_chart = $('#'+cont);
    if (bar_chart.length) {
        Highcharts.chart(cont, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: text
            },
            xAxis: [{
                    categories: xdata,
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
                    opposite: false,
                }
            ],
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
                        name: 'Quantity(ltr)',
                        type: 'column',
                        color: '#3a7bd5',
                        yAxis: 1,
                        data: ydata,
                        tooltip: {
                            valueSuffix: ' lt'
                        }

                }]
        });
    }
}
var bmc_d = " . json_encode($bmc_dispatch) . ";
var bmc_dispatch = bmc_d.map(function (x) { 
    return parseFloat(x, 10); 
});
barChart('container7','" . Yii::$app->controls->view_date($date) . " BMC Dispatch '," . json_encode($d_bmc) . ",bmc_dispatch);
function barChart(cont,text,xdata,ydata){
var bar_chart = $('#'+cont);
    if (bar_chart.length) {
        Highcharts.chart(cont, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: text
            },
            xAxis: [{
                    categories: xdata,
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
                    opposite: false,
                }
            ],
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
                        name: 'Quantity(ltr)',
                        type: 'column',
                        color: '#3a7bd5',
                        yAxis: 1,
                        data: ydata,
                        tooltip: {
                            valueSuffix: ' lt'
                        }

                }]
        });
    }
}

    var dcount = " . $DPUCount . ";
    var dc = dcount.map(function (x) { 
        return parseFloat(x, 10); 
    });
    
    var cfcount = " . $CFCount . ";
    var cfc = cfcount.map(function (x) { 
        return parseFloat(x, 10); 
    });
    

    /* Bar and Line dual chart */
    var line_chart = $('#reconciliation');
    if (line_chart.length) {
        Highcharts.chart('reconciliation', {
            chart: {
                zoomType: 'xy',
                height: '530px'
            },
            title: {
                text: '" . Yii::$app->controls->view_date($date) . ' ' . Yii::t('app', 'Reconciliation Chart') . "',
                y: 9,

            },

            xAxis: [{
                    categories: " . json_encode($graph_chart_dcs) . ",
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
                x: 400,
                verticalAlign: 'top',
                y: 10,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            series: [
                {
                    name: '" . Yii::t('app', 'DPU Farmer') . "',                     
                    type: 'spline',
                    color: '#3a7bd5',
                    data: dc,
                },{
                    name: '" . Yii::t('app', 'Collection Farmer') . "',                     
                    type: 'spline',
                    color: '#117856',
                    data: cfc,
                }
            ]
        });
    }
";
$this->registerJs($script, View::POS_READY, 'village-code');
