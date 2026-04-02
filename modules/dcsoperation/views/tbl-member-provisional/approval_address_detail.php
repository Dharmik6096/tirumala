<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Member Approval - Address Details';
?>
<?=
$this->render('approval_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>
<div class="panel panel-default panel-main">
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-address-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Address Details') ?></h4>
                    </div>
                    <div class="col-sm-4" id="union">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', true, true); ?>
                    </div>
                    <div class="col-sm-2 icon-set">
                        <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', FALSE); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblmemberprovisional-union_code,tblmemberprovisional-state_code', 'district_code', 'District'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblmemberprovisional-district_code', 'form-group col-sm-2', 'Sub District'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblmemberprovisional-sub_district_code', 'form-group col-sm-2', 'Village'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblmemberprovisional-village_code', 'form-group col-sm-2', 'Hamlet'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->depend_dropdown('region', $model, $form, 'tblmemberprovisional-union_code', 'form-group col-sm-12', 'Region', 'region_code'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'post_office')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($model->provisional_from == 'mobile_app' || $model->provisional_from == 'mobile_update') { ?>
                        <div class = "col-sm-2 mt10">
                            <?php
                            $contactVerificationStatus = $model->is_contact_verified == 1 ? 'Verify' : 'Not Verify';
                            echo $model->getAttributeLabel('is_contact_verified') . ' - ' . $contactVerificationStatus;
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-sm-2 mt10">
                            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_contact_verified'); ?>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-adhar-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Aadhar Details') ?></h4>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'aadhaar_card_address')->textarea() ?>
                    </div>
                    <div class="col-sm-2 icon-set">
                        <?= $form->field($model, 'adhar_no')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'email')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('relation', $model, $form, '', $model->getAttributeLabel('email_relation'), false, 'email_relation'); ?>
                    </div>

                    <?php if ($model->provisional_from == 'mobile_app' || $model->provisional_from == 'mobile_update') { ?>
                        <div class = "col-sm-2 mt10">
                            <?php
                            $emailVerificationStatus = $model->is_email_verify == 1 ? 'Verify' : 'Not Verify';
                            echo $model->getAttributeLabel('is_email_verify') . ' - ' . $emailVerificationStatus;
                            ?>
                        </div>
                        <div class = "col-sm-2 mt10">
                            <?php
                            $aadharVerificationStatus = $model->is_aadhar_verify == 1 ? 'Verify' : 'Not Verify';
                            echo $model->getAttributeLabel('is_aadhar_verify') . ' - ' . $aadharVerificationStatus;
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-sm-2 mt10">
                            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_email_verify'); ?>
                        </div>
                        <div class="col-sm-2 mt10">
                            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_aadhar_verify'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php
        $previews = '';
        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-address-detail')) {
            $currentAddressProof = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['currentAddressProof'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Current Address Proof') . '</span>' . (trim($currentAddressProof) != '' ? $currentAddressProof : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

            $otherIdProof = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['otherIdProof'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Other ID Proof') . '</span>' . (trim($otherIdProof) != '' ? $otherIdProof : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        }

        $aadharCard = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['aadharCard'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Aadharcard Front Photo') . '</span>' . (trim($aadharCard) != '' ? $aadharCard : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

        $aadharCardBack = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['aadharCardBack'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Aadharcard Back Photo') . '</span>' . (trim($aadharCardBack) != '' ? $aadharCardBack : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Document Previews') ?></h4>
                </div>
                <div class="doc-preview-container">
                    <?= $previews ?>
                </div>
            </div>
        </div>
        <div class="row">           
            <div class="col-sm-12 margin-top-10">
                <?php if ($isLastStep) { ?>
                    <div class="col-md-12 padding_10_0 theme-box mt10">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix margin-bottom-10">
                            <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
                        </div>
                        <div class="form-grid">
                            <div class="col-sm-12">
                                <?php echo $form->errorSummary([$processModel, $model]); ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <?= Yii::$app->dropdown->dropdownStatic('provisional_approval_status', $processModel, $form, '', $processModel->getAttributeLabel('status'), false, 'status', FALSE, FALSE, FALSE); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?= $form->field($processModel, 'remarks')->textarea(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?php
                        echo Html::hiddenInput('reroute_remarks', '', ['id' => 'reroute_remarks']);
                        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
                        if ($isLastStep) {
                            echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary btn-login apply-shortcut reroute me-2', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#ProvisionalModal',]);
                        }
                        $btnLabel = $isLastStep ? 'save' : 'Save & Next';
                        echo Yii::$app->controls->save($btnLabel, $processModel);

                        $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code);
                        if ($prevStep) {
                            ?>
                            <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default btn-login">Previous</a>
                        <?php } ?>
                    </div>  
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>
<?php
$script = "
$(document).ready(function() {
    function setDefaultHamletCode() {
        var hamletDropdown = $('#tblmemberprovisional-hamlet_code');
        var options = hamletDropdown.find('option');
        if (options.length == 2) {
            var singleOption = options.eq(1).val();
            hamletDropdown.val(singleOption).trigger('change');
        }
    }
    $('#tblmemberprovisional-village_code').on('change', function() {
        $('#tblmemberprovisional-hamlet_code').on('depdrop.afterChange', function(event, id, value) {
            setDefaultHamletCode();
        });
    });

    enableDisableField();
    $('#tblmemberprovisional-is_contact_verified, #tblmemberprovisional-is_email_verify, #tblmemberprovisional-is_aadhar_verify').on('click', function() {
        enableDisableField();
    });
    function enableDisableField() {
        $('.field-tblmemberprovisional-email').removeClass('disabled no_pointer');
        $('.field-tblmemberprovisional-adhar_no').removeClass('disabled no_pointer');
        
        if ($('#tblmemberprovisional-is_email_verify').is(':checked')) {
            $('.field-tblmemberprovisional-email').addClass('disabled no_pointer');
        }
        if ($('#tblmemberprovisional-is_aadhar_verify').is(':checked')) {
            $('.field-tblmemberprovisional-adhar_no').addClass('disabled no_pointer');
        }
    }
    
});
";
$this->registerJs($script, View::POS_END, 'approval_address_detail');
?>

