<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => FALSE],
//    ['attribute' => 'vehicle_code', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
//        },],
    ['attribute' => 'parsing_no'],
    ['attribute' => 'from_type', 'value' => function($model) {
            return strtoupper($model->from_type);
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('place_type', $searchModel, 'from_type'),
    ],
    ['attribute' => 'from_dest', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->from_type);
            $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
        }, 'filter' => false],
    ['attribute' => 'to_type', 'value' => function($model) {
            return strtoupper($model->to_type);
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('place_type', $searchModel, 'to_type'),
    ],
    ['attribute' => 'to_dest', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->to_type);
            $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
        }, 'filter' => false],
    [
        'attribute' => 'dispatch_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dispatch_date);
}],
    ['attribute' => 'toll_amount'],
    ['attribute' => 'fastag_amount'],
    ['attribute' => 'weighing_cost']
];

$grid_option = [
    'id' => 'location-wise-km-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
