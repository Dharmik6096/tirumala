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
<div class="grid-search large-search">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
        }, 'visible' => true, 'filter' => false],
    ['header' => 'Old Soc. Code', 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
    ['header' => 'Soc. Code', 'attribute' => 'dcs_code',
        'value' => 'dcs_code', 'filter' => true],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    // ['attribute' => 'fat', 'filter' => Html::activeDropDownList($searchModel, 'fat', $fat,['class'=>'form-control','prompt'=>'Select FAT'])],
    //['attribute' => 'snf', 'filter' => Html::activeDropDownList($searchModel, 'snf', $snf,['class'=>'form-control','prompt'=>'Select SNF'])],
    //['attribute' => 'qty', 'value' => 'qty', 'filter' => Html::activeDropDownList($searchModel, 'qty', $qty,['class'=>'form-control','prompt'=>'Select Qty'])],
    ['attribute' => 'fat', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'qty', 'filter' => Html::activeTextInput($searchModel, 'qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
    ['attribute' => 'water', 'filter' => true],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
//    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection', 'value' => function($model) {
//            return date('d-m-Y', strtotime($model->date_time_of_collection));
//        }, 'filter' => false],
    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->date_time_of_collection);
}],
        // 'date_time_of_recieve',
        // 'village_code',
        // 'sample_no',
        // 'type_of_data_receive',
];

$grid_option = [
    'id' => 'milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
