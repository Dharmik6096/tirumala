
<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$summary_model = $type == 'create' ? [$model, $contactDetails] : $model;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Vendor Details</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'vendor_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'adhar_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'vendor_type')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'local_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2 mt10">
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>
        <div class="col-md-12 padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Bank Details</h4>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-3', 'Bank'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblvendormaster-bank_code', '', 'Branch', 'branch_code'); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'bank_account_no')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'ifsc')->textInput(['readonly' => true]) ?>        
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'beneficiary_name')->textInput() ?>
            </div>
        </div>
    <?php } ?>

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
    <?php
    $script = "
    $('#tblvendormaster-bank_code').on('change', function() {
        $('#tblvendormaster-ifsc').val('');
    });

    $('#tblvendormaster-branch_code').on('change', function() {
        var id = $(this).val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
            data: 'id=' + id,
            success: function(data) {
                var obj1 = $.parseJSON(data);
                $('#tblvendormaster-ifsc').val(obj1.code);
            },
            error: function(data) {
                // Alert: Your data has not been submitted. Please try again.
            }
        });
    });
";
    $this->registerJs($script, View::POS_END, 'vendor');
    ?>