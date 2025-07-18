<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tblmilkvehicleentrysearch';
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'id' => 'receipt_trip_search',
                'method' => 'get',
                'validateOnChange' => FALSE,
                'validateOnBlur' => FALSE,
                'validateOnSubmit' => TRUE,
    ]);
    ?> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?php echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false); ?>
    </div>    
    <div class="col-sm-2 shift">
        <?php echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>  
    <div class="col-sm-2">
        <?php echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false); ?>
    </div>    
    <div class="col-sm-2 shift">
        <?php echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'f_plant_code', 'Plant'); ?>
    </div> 
    <div class="clearfix"></div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Html::hiddenInput('trip_type', 'alltrip', ['id' => 'trip_type']); ?>
        <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'tblmilkvehicleentrysearch-vehicle_code,trip_type,tblmilkvehicleentrysearch-from_date,tblmilkvehicleentrysearch-to_date', 'trip_code', $model->getAttributeLabel('trip_code'), false, false); ?>
    </div>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
$('#receipt_trip_search').submit(function(e){
    e.stopImmediatePropagation();
});";

$this->registerJs($script, View::POS_END, 'receipt-trip-search');
?>