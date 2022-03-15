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
    <div class="col-sm-2 create_fields">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2 create_fields">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblinterfacingdevicemapping-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2  create_fields">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblinterfacingdevicemapping-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $readonly); ?>
    </div>  
    <div class="col-sm-2  create_fields">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinterfacingdevicemapping-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE, '', '', $readonly); ?>
    </div>  
    <div class="col-sm-2  create_fields">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinterfacingdevicemapping-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2  create_fields">
        <?= Html::hiddenInput('type_1', 0, ['id' => 'type_1']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('device_code', $model, $form, 'tblinterfacingdevicemapping-union_code,type_1', 'form-group col-sm-4', $model->getAttributeLabel('weight_device_code'), 'weight_device_code', FALSE); ?>
    </div>

    <div class="col-sm-2  create_fields">
        <?= Html::hiddenInput('type_2', 1, ['id' => 'type_2']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('device_code', $model, $form, 'tblinterfacingdevicemapping-union_code,type_2', 'form-group col-sm-4', $model->getAttributeLabel('analyzer_device_code'), 'analyzer_device_code', FALSE); ?>
    </div>
    <div class="col-sm-2  create_fields">
        <?= Html::hiddenInput('type_3', 5, ['id' => 'type_3']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('device_code', $model, $form, 'tblinterfacingdevicemapping-union_code,type_3', 'form-group col-sm-4', $model->getAttributeLabel('printer_device_code'), 'printer_device_code', FALSE); ?>

    </div>
    <div class="col-sm-2  create_fields">
        <?= Html::hiddenInput('type_4', 2, ['id' => 'type_4']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('device_code', $model, $form, 'tblinterfacingdevicemapping-union_code,type_4', 'form-group col-sm-4', $model->getAttributeLabel('display_device_code'), 'display_device_code', FALSE); ?>

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
