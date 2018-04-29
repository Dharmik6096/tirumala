<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
$fat = Yii::$app->general->dropdownRange('TblDpuCalibration', 'fat', 3);
$snf = Yii::$app->general->dropdownRange('TblDpuCalibration', 'snf', 3);
?>
<div class="grid-search">
    <?php 
    echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php

$attribute = [
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name','filter' => false],
//    ['label'=>'Date','attribute' => 'date','value'=>function($model){
//                return date('d-m-Y',strtotime($model->date));
//            }, 'filter' => true],
    ['label' => 'Date', 'attribute' => 'date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->date);
    }],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => $milk_type],
    ['attribute' => 'fat', 'filter' => Html::activeDropDownList($searchModel, 'fat', $fat,['class'=>'form-control','prompt'=>'Select FAT'])],
    ['attribute' => 'snf', 'filter' => Html::activeDropDownList($searchModel, 'snf', $snf,['class'=>'form-control','prompt'=>'Select SNF'])],
    ['attribute' => 'water', 'filter' => true],
    ['attribute' => 'cycle', 'filter' => false],
    ['attribute' => 'shift', 'value'=>'shiftCode.shift','filter' => false],
  
];

$grid_option = [
    'id' => 'dpu-calibration-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
