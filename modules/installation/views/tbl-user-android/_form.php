<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbluserandroid-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbluserandroid-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $readonly); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbluserandroid-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE, '', '', $readonly); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbluserandroid-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'username')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'password')->passwordInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'repeat_password')->passwordInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('role_code', $model, $form, '', 'Role Name'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
    </div>
    <div class="clearfix"></div>
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


