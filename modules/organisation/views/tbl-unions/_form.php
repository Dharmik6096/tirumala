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
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
<div class="col-md-6 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Company') ?> Details</h4>
        </div>
    <?= Html::hiddenInput('file_name', '', ['id' => 'file_name']); ?> 
    <div class="col-sm-12 padding_left_right_0 padding-0">
        <div class="col-sm-4">
            <?= $form->field($model, 'union_code_ex')->textInput(['maxlength' => true, 'readOnly' => $disable]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'union_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'union_short_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'registration_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>

    <?php
    if (!empty($model->logo)) {
        $image = $model->logo;
    } else {
        $image = Yii::$app->request->baseUrl . '/themes/pcdf/assets/images/' . 'no_image.jpg';
    }
    ?>
    <div class="col-sm-4 form-group hide_drop_box">
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
    </div>
</div>

<div class="col-md-6 padding_10_0 theme-box theme_border_left ">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
        <h4 class="theme-box-heading">Address Details</h4>
    </div>
    <div class="col-sm-8 padding_left_right_0">
        <div class="row">
            <div class="col-sm-6 <?//= $checkChild; ?>">
                <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
            </div>
            <div class="col-sm-6 <?//= $checkChild; ?>">
                <?= Yii::$app->dropdown->district($model, $form, 'tblunions-state_code', 'district_code', 'District', FALSE, $readonly); ?>
            </div>
            <div class="col-sm-6">
                <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblunions-district_code', '', 'Sub District', '', $readonly); ?>
            </div>
            <div class="col-sm-6 <?//= $checkChild; ?>">
                <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblunions-sub_district_code', '', 'Village', '', $readonly); ?>
            </div>
            <div class="col-sm-6 <?//= $checkChild; ?>">
                <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblunions-village_code', '', 'Hamlet'); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'address')->textarea() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-4">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'contact_person_email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'fax_no')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class="clearfix"></div>
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 theme_border_top clearfix">
            <h4 class=""></h4>
        </div>
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

        <div class="clearfix"></div>

        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bank Details</h4>
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
    
    <div class="col-sm-2">
        <?= $form->field($model, 'contact_person_pan_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'upi_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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

$script = "
    var delay=2000;
    $('#tblunions-contact_person_pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });
";
$this->registerJs($script, View::POS_END, 'union');

