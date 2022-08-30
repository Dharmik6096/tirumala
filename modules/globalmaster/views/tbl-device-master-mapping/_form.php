<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
        <?php
        $record = [Yii::$app->request->get('id') => Yii::$app->request->get('type') . '(' . Yii::$app->request->get('srno') . ')'];
        echo $form->field($model, 'device_master_code')->dropDownList($record, ['disabled' => true])->label(Yii::t('app', 'device master'));
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('applicability_type', $model, $form, 'form-group', $model->getAttributeLabel('applicability_type'), false, 'applicability_type', false); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->merge_bmc_dcs($model, $form, 'tbldevicemastermapping-applicability_type', 'applicability_code', 'Applicable Name'); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->controls->date($model, $form, 'wef_date', 'form-group col-sm-3', false, false, false); ?>
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
