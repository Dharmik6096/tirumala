<?php

use app\components\ActiveForm;

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
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('asset_group_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('asset_group_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'asset_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ref_code')->textInput() ?>
    </div>
    <!--    <div class="col-sm-2">
    <? // Yii::$app->dropdown->dropdown('cmpl_product_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('cmpl_product_code')); ?>
        </div>-->
</div>
<div class="row">
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_serial_number', $class); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?php
        if ($model->assetBom) {
            echo Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_spare', $class);
            // echo $form->field($model, 'is_spare', ['checkboxTemplate' => "<div class='checkbox " . $class . "'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox();
        } else {
            echo Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_spare');
            // echo $form->field($model, 'is_spare', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox();
        }
        ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->dropdown('asset_type', $model, $form, '', true, FALSE, 'asset_type_code'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
