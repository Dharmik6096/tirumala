<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php
$attribute = [
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'label' => Yii::t('app', 'MCC Name'), 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'label' => Yii::t('app', 'BMC Name'), 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'label' => Yii::t('app', 'Route Name'), 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'VCG_M_code'],
    ['attribute' => 'VCG_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->VCG_date);
        }],
    ['attribute' => 'from_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->from_time);
        }, 'filter' => false],
    ['attribute' => 'to_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->to_time);
        }, 'filter' => false],
    ['attribute' => 'attandance_count'],
    ['attribute' => 'route_supervisor_code'],
    ['attribute' => 'route_supervisor_name', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->routeSupervisorCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'pib_office_code'],
    ['attribute' => 'pib_office_name', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->pibOfficeName, 'name');
    }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'status', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('vcg_mrg_meeting_status', $searchModel, 'status')],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'vcg-mrg-member-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'report' => function ($url, $model) {
                $options = ['title' => Yii::t('app', 'View Report'), 'target' => '_blank'];
                return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/jasperreports/default/vcg-meeting', 'code' => $model->VCG_M_Id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
