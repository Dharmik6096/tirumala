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
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3 number-validate">  
        <?= $form->field($model, 'asset_code')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('asset_group_code', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('asset_group_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'asset_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('cmpl_product_code', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('cmpl_product_code')); ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= $form->field($model, 'is_serial_number', ['checkboxTemplate' => "<div class='checkbox " . $class . "'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
    </div> 
</div>

<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
