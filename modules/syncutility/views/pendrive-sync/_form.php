<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

$title = Yii::$app->label->title($type, 'Export Sync Data');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
?>


<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'col-sm-12'
            ],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>


<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblpendriveimportexport-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblpendriveimportexport-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblpendriveimportexport-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblpendriveimportexport-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>         
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'dest_org_type')->hiddenInput(['readOnly' => true, 'value' => 'VLC'])->label(false) ?>
        <?= Yii::$app->dropdown->androiddevicelist($model, $form, 'tblpendriveimportexport-dcs_code,tblpendriveimportexport-dest_org_type', 'device_id', $model->getAttributeLabel('device_id')); ?>
    </div>

    <div class="col-sm-2 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
