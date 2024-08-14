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
            <div class="card">
                <div class="card-body">
                    <div class="tbl-cell">
                        <div id="iot_temperature"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
function drawLineChart(id, hours, temperatures) {
    Highcharts.chart('iot_temperature', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'IoT Temperature'
        },
        xAxis: {
            categories: hours
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            },
        },
        series: [{
            name: 'Time',
            data: temperatures,
        }]
    });
}
";
$this->registerJs($script, \yii\web\View::POS_READY, 'temperature_line_chart');
?>
