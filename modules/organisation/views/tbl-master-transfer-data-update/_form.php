<?php

use app\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */
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
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmastertransferdataupdate-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmastertransferdataupdate-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmastertransferdataupdate-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE, '', '', FALSE); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->transfer_bmc_society($model, $form, 'tblmastertransferdataupdate-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date') ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift', true, FALSE, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2', true) ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift', true, FALSE, 'to_shift'); ?>
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