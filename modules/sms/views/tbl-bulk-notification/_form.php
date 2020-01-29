<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\TblBulkNotification */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('app_type', $model, $form, '', TRUE, false, 'app_type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('login_type', $model, $form, 'form-group', $model->getAttributeLabel('login_type'), false, 'login_type', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbulknotification-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbulknotification-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbulknotification-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblbulknotification-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>         
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblbulknotification-dcs_code', '', Yii::t('app', 'Member')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, date('Y-m-d'), false, TRUE, true); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'campaign_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'title')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'message')->textInput() ?>
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