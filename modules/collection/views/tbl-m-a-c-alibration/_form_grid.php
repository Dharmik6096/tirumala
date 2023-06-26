<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>
<?php

$attribute = [
    ['attribute' => 'id'],
    ['attribute' => 'BMCCode',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
    ['attribute' => 'PPCode',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
    ['attribute' => 'CalibrationFat', 'filter' => false],
    ['attribute' => 'CalibrationSnf', 'filter' => false],
    ['attribute' => 'CalibrationWater', 'filter' => false],
    ['attribute' => 'MilkType', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'MilkType', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    [
        'attribute' => 'dtdate',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'date'),
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dtdate);
}],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
];
$grid_option = [
    'id' => 'calibration-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    //'delete' => ['option' => 'date,product_requisition_code,tbl-product-requisition/delete'],
//        'dispatch' => function ($url, $model) {
//                $class = ($model->status==6)?'':'disabled';
//                $options = ['data-name' => $model->date,'class'=>$class, 'data-val' => $model->product_requisition_code,'title'=>'Dispatch Requisition'];
//                return GhostHtml::a('<span class="fas fa-plus"></span>', ['/inventory/tbl-product-material-dispatch/create','id'=>$model->product_requisition_code], $options);
//        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
