<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = 'Dashboard';

use app\models\TblDashboardWidgets;
use Symfony\Component\Console\Input\Input;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="collection">
                    <div class="tbl-cell">
                        <div id="pieChartModals"></div>  
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<?php
$online_dcs = array_sum(array_map(function($item) {
            return $item['onlineDcs'];
        }, $dashboard_society_status_pie_chart));
$offline_dcs = array_sum(array_map(function($item) {
            return $item['offlineDcs'];
        }, $dashboard_society_status_pie_chart));


$script = " 
var online_dcs = " . $online_dcs . ";
var offline_dcs = " . $offline_dcs . ";
var pie_chart = $('#pieChartModals');
if (pie_chart.length) {
    Highcharts.chart('pieChartModals', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '" . Yii::t('app', 'Society Status Chart') . "'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    },
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Society Status Chart',
            colorByPoint: true,
            data: [{
                name: '" . Yii::t('app', 'Online DCS') . "',
                y: online_dcs
            }, {
                name: '" . Yii::t('app', 'Offline DCS') . "',
                y: offline_dcs
            }]
        }]
    });
}


";
$this->registerJs($script, View::POS_READY, 'pie_chart_status');
?>