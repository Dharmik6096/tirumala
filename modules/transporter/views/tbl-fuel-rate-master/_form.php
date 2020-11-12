<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblfuelratemaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblfuelratemaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $readonly); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblfuelratemaster-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE, '', '', $readonly); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('fuel_type_code', $model, $form, 'form-group col-sm-2', 'Fuel Type', $readonly); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'rate')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


