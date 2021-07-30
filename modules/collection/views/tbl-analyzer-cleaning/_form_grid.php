<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2

?>
<?php

$attribute = [
    ['attribute' => 'bmc_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    [
        'attribute' => 'date_time_of_cleaning',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'date'),
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->date_time_of_cleaning);
}],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
    [
        'attribute' => 'date_time_of_actual_cleaning',
        'filter' => FALSE,
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->date_time_of_actual_cleaning);
        }],
    ['attribute' => 'cycle', 'filter' => false],
    ['attribute' => 'measuring', 'filter' => false],
    ['attribute' => 'counter', 'filter' => false],
];
$grid_option = [
    'id' => 'cleaning-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    //'delete' => ['option' => 'date,product_requisition_code,tbl-product-requisition/delete'],
//        'dispatch' => function ($url, $model) {
//                $class = ($model->status==6)?'':'disabled';
//                $options = ['data-name' => $model->date,'class'=>$class, 'data-val' => $model->product_requisition_code,'title'=>'Dispatch Requisition'];
//                return GhostHtml::a('<span class="glyphicon glyphicon-plus"></span>', ['/inventory/tbl-product-material-dispatch/create','id'=>$model->product_requisition_code], $options);
//        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
