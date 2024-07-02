<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = 'Dashboard';

use yii\web\View;
?>
<div class="card">
    <div class="card-body">
        <div class="col-sm-12">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Active VLC'); ?></div><div id="total_mpp_count" class="right-title"><?= 0; ?></div></div>
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_mpp"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Total Farmers'); ?></div><div id="total_member_count" class="right-title"><?= 0; ?></div></div>
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_member"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Total Emplpyees'); ?></div><div id="total_employee_count" class="right-title"><?= 0; ?></div></div>
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_employee"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Total Route Supervisor'); ?></div><div id="total_supervisor_count" class="right-title"><?= 0; ?></div></div>            
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_supervisor"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Total Area Incharge'); ?></div><div id="total_az_manager_count" class="right-title"><?= 0; ?></div></div>
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_az_manager"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center background_dark"><div class="left-title"><?= Yii::t('app', 'Other Employees'); ?></div><div id="total_other_Staff_count" class="right-title"><?= 0; ?></div></div>
                    <div class="card-body">
                        <div class="tbl-cell">
                            <div id="total_other_Staff"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
$script = " 
function drawPieChart(id,w_data){
var seriesArr = [];
var colorArr = ['#198754e6', '#f03b3bdb'];
var total_count = 0;
  $.each(w_data, function(index, value) {
   total_count = parseInt(total_count);
   current_count= parseInt(value.total_count);
                    if (!isNaN(total_count) && !isNaN(current_count)) {
                        total_count += current_count;
                    } 
  var series = {  
        'name' :value.name,
        'y' :parseInt(value.total_count),
        'color' :colorArr[index]  
     };
    seriesArr.push(series);  
    });
     if ($('#'+id+'_count').length) {
        $('#'+id+'_count').html(total_count);
     }
     if ($('#'+id).length) {
        Highcharts.chart(id, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                height: 250
            },
            title: false,
            tooltip: {
                pointFormat: '<b>({point.y})</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels:
                        {
                            enabled: true,
                            distance: -40,
                            format: '<b>{point.y}</b>',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            },
                        },showInLegend: true
                }
            },
             series: [{
                name: 'Brands',
                colorByPoint: true,
                data : seriesArr
                }]
        });
    }

}

";
$this->registerJs($script, View::POS_READY, 'dashboard_pie_chart');
?>