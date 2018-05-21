<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use zainiafzan\widget\Dropzone;
use yii\helpers\Html;

$url = ($model->isNewRecord) ? '' : Url::to(['../../organisation/tbl-unions/view', 'id' => $model->union_code]);

$checkChild = ($model->getCheckDcsExist()) ? ' disabled' : '';
$disable = ($model->isNewRecord) ? false : true;
$readonly = $type == 'create' ? FALSE : TRUE;
$summary_model = $type == 'create' ? [$model, $bankDetails, $contactDetails] : $model;

$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <?= Html::hiddenInput('file_name', '', ['id' => 'file_name']); ?> 
    <div class="col-sm-10 padding-0">
        <div class="col-sm-3">
            <?= $form->field($model, 'union_code_ex')->textInput(['maxlength' => true, 'readOnly' => $disable]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'union_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'union_short_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'registration_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>
    </div>
    <?php
    if (!empty($model->logo)) {
        $image = $model->logo;
    } else {
        $image = Yii::$app->request->baseUrl . '/themes/pcdf/assets/images/' . 'no_image.jpg';
    }
    ?>
    <div class="col-sm-2 form-group hide_drop_box">
        <?=
        Dropzone::widget([
            'id' => 'mainDrop',
            'options' => [
                'acceptedMimeTypes' => ".jpg, .png, .jpeg",
                'url' => Url::to(['/organisation/tbl-unions/upload-img']),
                'addRemoveLinks' => true,
                'autoDiscover' => false,
                'maxFiles' => 1,
                'dictDefaultMessage' => Html::img($image, ['class' => 'img-responsive']),
            ],
            'clientEvents' => [
                'success' => "function( file, response ){
                                                $('#file_name').val(response);
                                        }",
                'removedfile' => "function( file, response ){
                                                $('#file_name').val('');
                                        }",
                'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
            ]
        ]);
        ?>
    </div>
    <div class="col-sm-12">
        <p class="form-subtitle">Address Details</p>
        <hr class="hr10">
    </div>
    <div class="col-sm-3 <?//= $checkChild; ?>">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
    </div>
    <div class="col-sm-3 <?//= $checkChild; ?>">
        <?= Yii::$app->dropdown->district($model, $form, 'tblunions-state_code', 'district_code', 'District', FALSE, $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblunions-district_code', '', 'Sub District', '', $readonly); ?>
    </div>
    <div class="col-sm-3 <?//= $checkChild; ?>">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblunions-sub_district_code', '', 'Village', '', $readonly); ?>
    </div>
    <div class="col-sm-3 <?//= $checkChild; ?>">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblunions-village_code', '', 'Hamlet'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textarea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'fax_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12">
            <p class="form-subtitle">Contact Details</p>
            <hr class="hr10">
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>

        <div class="clearfix"></div>

        <div class="col-sm-12">
            <p class="form-subtitle">Bank Details</p>
            <hr class="hr10">
        </div>
        <?php //Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-3','Bank');   ?>
        <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field' => 'tblunions-district_code'
        ])
        ?>
    <?php } ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person_email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person_pan_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'upi_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?php if (Yii::$app->general->checkAccess('/organisation/tbl-unions/index')) { ?>
                <?= Yii::$app->controls->cancel($model); ?>
            <?php } else { ?> 
                <?= Yii::$app->controls->cancel($model, $url); ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
