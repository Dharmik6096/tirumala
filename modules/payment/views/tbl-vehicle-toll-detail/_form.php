<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$list = array('0' => 'No', '1' => 'Yes');
$disable = ($type == 'create') ? '' : ' disabled';
?>

<?php
$form = ActiveForm::begin([

            'options' => ['id' => 'bmc-form'],
            'validateOnBlur' => FALSE,
//            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-md-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblvehicletolldetail-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), 'vehicle_code', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'dispatch_date', '', '', false, $readonly, true); ?>
    </div>  
    <?php echo Html::hiddenInput('from_type', 'Owntype', ['id' => 'from_type']); ?>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dispatch_places_type($model, $form, 'tblvehicletolldetail-vehicle_code,tblvehicletolldetail-dispatch_date,from_type', 'from_type', $model->getAttributeLabel('from_type'), FALSE, $readonly); ?> 
    </div>
    <?php echo Html::hiddenInput('from_dest', 'Ownmccid', ['id' => 'from_dest']); ?>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dispatch_places($model, $form, 'tblvehicletolldetail-from_type,from_type,from_dest,tblvehicletolldetail-dispatch_date,tblvehicletolldetail-vehicle_code', 'from_dest', $model->getAttributeLabel('from_dest'), FALSE, $readonly); ?> 
    </div>

    <?php echo Html::hiddenInput('to_type', 'Type', ['id' => 'to_type']); ?>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dispatch_places_type($model, $form, 'tblvehicletolldetail-vehicle_code,tblvehicletolldetail-dispatch_date,to_type', 'to_type', $model->getAttributeLabel('to_type'), FALSE, $readonly); ?> 
    </div>
    <?php echo Html::hiddenInput('to_dest', 'ToPlace', ['id' => 'to_dest']); ?>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dispatch_places($model, $form, 'tblvehicletolldetail-to_type,to_type,to_dest,tblvehicletolldetail-dispatch_date,tblvehicletolldetail-vehicle_code', 'to_dest', $model->getAttributeLabel('to_dest'), FALSE, $readonly); ?> 
    </div>

    <div class="col-md-3 number-validate">
        <?= $form->field($model, 'toll_amount')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3 number-validate">
        <?= $form->field($model, 'fastag_amount')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3 number-validate">
        <?= $form->field($model, 'weighing_cost')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

