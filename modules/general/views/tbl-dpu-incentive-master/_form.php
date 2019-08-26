<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'no_pointer';
?>

<?php
$form = ActiveForm::begin([

            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-3" >
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldpuincentivemaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldpuincentivemaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>  
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldpuincentivemaster-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldpuincentivemaster-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', $readonly, TRUE); ?>         
    </div>
    <div class="clearfix"></div>

    <div class="col-sm-2">
        <?=
        $form->field($model, 'm_start_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'e_start_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'm_cutoff_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'e_cutoff_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'm_lock_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'e_lock_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99:99',])
        ?> 
    </div>
    <div class="col-sm-3 number-validate">
        <?= $form->field($model, 'inc_rate')->textInput() ?>
    </div>
    <div class="col-sm-3 number-validate">
        <?= $form->field($model, 'inc_deduction')->textInput() ?>
    </div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>
