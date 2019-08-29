<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false,],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Soc. Code'), 'attribute' => 'dcs_code', 'filter' => true],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['attribute' => 'date_time_of_dispatch',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_dispatch);
        }],
    ['attribute' => 'challan_no'],
    ['attribute' => 'dispatch_type', 'value' => function ($model) {
            return isset($model->dispatch_type) ? Yii::$app->dropdown->getRecords('disp_in')['data'][$model->dispatch_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('disp_in', $searchModel, 'dispatch_type'),
    ],
    ['attribute' => 'destination_type', 'value' => function ($model) {
            return isset($model->destination_type) ? Yii::$app->dropdown->getRecords('destination_type')['data'][$model->destination_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('destination_type', $searchModel, 'destination_type'),
    ],
    ['attribute' => 'destination_code', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            $att = $model->destination_type == '0' ? 'bmc_name' : 'name';
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
        }, 'filter' => false],
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'shift_code', Yii::t('app', 'Select'))],
];

$grid_option = [
    'id' => 'dcs-milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
