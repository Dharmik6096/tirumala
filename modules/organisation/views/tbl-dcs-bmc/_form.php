<?php

use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

//$title = Yii::$app->label->title($type, 'Bmc');
$button = Yii::$app->label->button($type);
$readonly=$type=='create'?FALSE:TRUE;
//$this->title = Yii::t('app', $title);
$bmc_url=  Url::to(['create']);
$dcs_url= Url::to(['tbl-dcs/create']);
$model->is_mcc=$model->isNewRecord?0:$model->is_mcc;

$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$disabled = ($model->is_mcc == 1)?TRUE:FALSE;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
     <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union',$readonly); ?>
    </div>
     <?= Html::activeHiddenInput($model, 'is_mcc') ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('mcc', $model, $form, 'tbldcsbmc-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'MCC', '',$disabled); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('bmc_type', $model, $form, '', 'BMC Type',false,'bmc_type_code'); ?>        
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'bmc_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3"> 
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)',false,'capacity'); ?>        
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('manufacture', $model, $form, 'form-group col-sm-12', 'Manufacturer'); ?>
    </div>
     <div class="col-sm-3">
        <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group padding-right-5 col-sm-3', 'Milk Quality Type', false, 'bmc_milk_type'); ?>
    </div> 
        <div class="clearfix"></div>
        <div class="col-sm-3">
                <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tbldcsbmc-union_code,tbldcsbmc-state_code', 'district_code', 'District',FALSE,$readonly); ?>
        </div>
        <div class="col-sm-3">
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tbldcsbmc-district_code', 'form-group col-sm-4', 'Sub District', 'sub_district_code',$readonly); ?>
        </div>
<!--        <div class="clearfix"></div>-->
        <div class="col-sm-3">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tbldcsbmc-sub_district_code', 'form-group col-sm-4', 'Village','',$readonly); ?>
        </div>
        <div class="col-sm-3">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tbldcsbmc-village_code', 'form-group col-sm-4', 'Hamlet'); ?>
        </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
    </div>
    <div class="col-sm-3 mt35">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
            <?= Html::hiddenInput('from_bmc', 0, ['id' => 'bmc']); ?>
    <div class="clearfix"></div>
    <?php if($type=='create') { ?>
    <div class="col-sm-12">
        <p class="form-subtitle">Contact Details</p>
        <hr class="hr10">
    </div>
      <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>
    <?php } ?>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
           
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, str_replace(Url::base(), '', Url::previous())); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>