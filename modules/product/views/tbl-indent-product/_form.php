<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblIndentProduct */
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
    <div class="col-sm-2 no_padding_input">
        <?= Html::hiddenInput('is_indent', '1', ['id' => 'is_indent']); ?>
        <?php Yii::$app->dropdown->depend_dropdown('indent-product', $model, $form, 'tblindentproduct-union_code,is_indent', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
    </div>

    <div class="col-sm-1 mt15">
       <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_mcc'); ?>
    </div>

    <div class="col-sm-1 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_warehouse'); ?>
    </div>

    <div class="col-sm-2 qty-validate">
        <?= $form->field($model, 'qty')->textInput() ?>
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
    $('#tblindentproduct-is_warehouse').on('click', function(){
        $('#tblindentproduct-qty').val('');
        if($(this).is(':checked')) {
            $('#tblindentproduct-qty').prop('readonly', false);
        } else {
            $('#tblindentproduct-qty').prop('readonly', true);
        }
    });
";
$this->registerJs($script, View::POS_END, 'indent-product-form');
?>