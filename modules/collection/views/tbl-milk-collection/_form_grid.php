<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
//$fat = Yii::$app->general->dropdownRange('TblMilkCollection', 'fat', 3);
//$snf = Yii::$app->general->dropdownRange('TblMilkCollection', 'snf', 3);
//$qty = Yii::$app->general->dropdownRange('TblMilkCollection', 'qty', 100);
//$amount = Yii::$app->general->dropdownRange('TblMilkCollection', 'amount', 1000);
$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>

<?php

$attribute = [
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
        }, 'visible' => FALSE, 'filter' => false],
    ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
            return substr($model->member_code, -4);
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => true],
    ['label' => Yii::t('app', 'Soc. Code'), 'visible' => FALSE, 'attribute' => 'dcs_code', 'filter' => true],
    ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
    ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->date_time_of_collection);
}],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
    ['attribute' => 'name', 'filter' => false, 'visible' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    ['attribute' => 'fat', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'qty', 'filter' => Html::activeTextInput($searchModel, 'qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
    // ['attribute' => 'fat', 'filter' => Html::activeDropDownList($searchModel, 'fat', $fat,['class'=>'form-control','prompt'=>'Select FAT'])],
    // ['attribute' => 'snf', 'filter' => Html::activeDropDownList($searchModel, 'snf', $snf,['class'=>'form-control','prompt'=>'Select SNF'])],
    //['attribute' => 'qty', 'value' => 'qty', 'filter' => Html::activeDropDownList($searchModel, 'qty', $qty,['class'=>'form-control','prompt'=>'Select Qty'])],
    ['attribute' => 'rtpl', 'filter' => true],
    ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    //['attribute' => 'amount', 'filter' => Html::activeDropDownList($searchModel, 'amount', $amount,['class'=>'form-control','prompt'=>'Select Amount'])],
//    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection', 'value' => function($model) {
//            return date('d-m-Y', strtotime($model->date_time_of_collection));
//        }, 'filter' => true],
    ['attribute' => 'mobile_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'mobile_no');
        }, 'filter' => false, 'visible' => false],
];

$grid_option = [
    'id' => 'milk-collection-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
