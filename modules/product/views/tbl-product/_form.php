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
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('product_group', $model, $form, 'tblproduct-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('product_group_code')); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('unit', $model, $form, 'tblproduct-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('unit_code')); ?>
        <?php // Yii::$app->dropdown->dropdown('unit_code', $model, $form, '', $model->getAttributeLabel('unit_code'), false, 'unit_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'product_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ref_code')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('depend_tax_code', $model, $form, 'tblproduct-union_code', 'form-group col-sm-2', $model->getAttributeLabel('tax_code'), 'tax_code'); ?>
        <?php // Yii::$app->dropdown->dropdown('tax_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('tax_code'), false, 'tax_code'); ?>
    </div>  
    <?php
    $class = $disableDpuProduct ? ' disabledDiv ' : '';
    ?>
    <div class="col-sm-2 mt15 <?= $class ?>">
        <?= $form->field($model, 'is_dpu_product', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['readonly' => $disableDpuProduct]); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'dpu_product_code')->textInput(['readonly' => empty($model->is_dpu_product) || $disableDpuProduct, 'class' => 'form-control number-validate']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'product_desc')->textarea() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_inhouse', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_inclusive_tax', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_saleable', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'is_indent', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->active($model, $form); ?>
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


<?php
$script = "
    $('#tblproduct-is_dpu_product').on('click', function(){
        $('#tblproduct-dpu_product_code').val('');
        if($(this).is(':checked')) {
            $('#tblproduct-dpu_product_code').prop('readonly', false);
        } else {
            $('#tblproduct-dpu_product_code').prop('readonly', true);
        }
    });
";
$this->registerJs($script, View::POS_END, 'product-form');
?>