<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
//$model->is_plant=$model->isNewRecord?0:$model->is_plant;
//$nameWarning = 0;
//$codeWarning = 0;
//if (!empty($_POST)) {
//    $nameWarning = $_POST['warning'];
//    $codeWarning = $_POST['code_warning'];
//}
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([

            'options' => ['id' => 'bmc-weight-data-form'],
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblweightcollection-union_code', '', $model->getAttributeLabel('dcs_code')); ?>            
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', 'form-group col-sm-2'); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, false, 'shift_code'); ?>
    </div>
    <div class="col-sm-2 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'milk_type_code'); ?>
    </div>
    <div class="col-sm-2 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= $form->field($model, 'sample_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'doc_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'cans')->textInput() ?>
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