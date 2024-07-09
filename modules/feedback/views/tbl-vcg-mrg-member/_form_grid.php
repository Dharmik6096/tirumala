<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Html;
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
    ['attribute' => 'member_code'],
    ['attribute' => 'member_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle'],
    ['attribute' => 'member_tr_code'],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'end_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->end_date);
        }],
    ['attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }],
    ['attribute' => 'type','filter' => array('MRG'=>'MRG','VCG'=>'VCG'), ],
    ['attribute' => 'status','filter' => array('Draft'=>'Draft','Approved'=>'Approved','Rejected'=>'Rejected', 'Inactivate'=>'Inactivate')],
    ['attribute' => 'remark'],
];

$grid_option = [
    'id' => 'vcg-mrg-member-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        // 'update' => true,
        'update' => function ($url, $model) {
            $class = in_array($model->status, ['REJECTED','INACTIVATE']) ? 'disabled' : '';
            $options = ['data-code' => $model->VCG_MRG_member_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Asset Detail Bom', 'class' => $class,];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/feedback/tbl-vcg-mrg-member/update', 'id' => $model->VCG_MRG_member_id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
