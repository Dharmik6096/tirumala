<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
//$fat = Yii::$app->general->dropdownRange('TblMilkDispatch', 'fat', 3);
//$snf = Yii::$app->general->dropdownRange('TblMilkDispatch', 'snf', 3);
//$qty = Yii::$app->general->dropdownRange('TblMilkCollection','qty', 100);
?>

<?php

$attribute = [
    ['attribute' => 'bmc_code',
        'filter' => Html::activeTextInput($searchModel, 'bmc_code', ['class' => 'form-control']),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    ['attribute' => 'fat', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'dispatch_qty', 'filter' => Html::activeTextInput($searchModel, 'dispatch_qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
//    ['attribute' => 'water', 'filter' => true],
    ['attribute' => 'dispatch_shift', 'value' => 'shiftCode.shift', 'filter' => false],
    ['attribute' => 'dispatch_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dispatch_datetime);
}],
    ['attribute' => 'temprature', 'filter' => false, 'visible' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model){ return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'); }, 'filter' => false, 'visible' => false],
    ['attribute' => 'mbrt', 'filter' => false, 'visible' => false],
    ['attribute' => 'actual_qty', 'filter' => false, 'visible' => false],
    ['attribute' => 'alcohole_test', 'filter' => false, 'visible' => false],
];

$grid_option = [
    'id' => 'milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => [
//        'view' => TRUE,
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
