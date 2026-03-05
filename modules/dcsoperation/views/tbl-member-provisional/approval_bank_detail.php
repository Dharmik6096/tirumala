<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\detail\DetailView;

$this->title = 'Member Approval - Bank Details';

$disable_ifsc = !empty($model->ifsc) && !empty($model->bank_code) ? true : false;
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
                    'options' => [],
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary([$model, $shareModel]); ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-bank-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Bank Details') ?></h4>
                    </div>
                    <div class="col-sm-2 disp_none" id="union">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', true, true); ?>
                    </div>
                    <div class="col-sm-2 disp_none">
                        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', FALSE); ?>
                    </div>
                    <div class="col-sm-2 disp_none">
                        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblmemberprovisional-union_code,tblmemberprovisional-state_code', 'district_code', 'District'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblmemberprovisional-district_code', 'bank_code', 'Bank'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblmemberprovisional-bank_code', '', 'Branch', 'branch_code'); ?>
                    </div>
                    <div class="col-sm-2 icon-set">
                        <?= $form->field($model, 'bank_account_no')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'ifsc')->textInput(['readonly' => true]) ?>        
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'beneficiary_name')->textInput() ?>
                    </div>
                    <div class="col-sm-2 icon-set">
                        <?= $form->field($model, 'pan_no')->textInput() ?>
                    </div>
                    <div class="col-sm-2 icon-set">
                        <?= $form->field($model, 'voter_id')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'annual_income')->textInput() ?>
                    </div>
                    <div class="col-sm-2 mt10">
                        <?= $form->field($model, 'is_verify', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
                    </div>

                </div>
            </div>
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-fee-detail')) { ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Fee Information') ?></h4>
                    </div>

                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('mode_of_payment', $shareModel, $form, 'form-group', $shareModel->getAttributeLabel('mode_of_payment'), false, 'mode_of_payment', false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($shareModel, 'amount_deposit')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->controls->date($shareModel, $form, 'deposit_date'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($shareModel, 'ref_no')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($shareModel, 'bank_name')->textInput() ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-mismatch-detail')) { ?>
            <?php
            $declarationAttachment = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['DeclarationAttachment'], 'image');
            $documentCheck = '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Declaration Attachment') . '</span>' . (trim($declarationAttachment) != '' ? $declarationAttachment : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
            ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Name Mismatch Declaration') ?></h4>
                    </div>
                    <div class="doc-preview-container">
                        <?= $documentCheck ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php
        $previews = '';
        $showPreviews = false;
        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-bank-detail')) {
            $showPreviews = true;
            $panCard = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['panCard'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Pan Card') . '</span>' . (trim($panCard) != '' ? $panCard : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

            $voterID = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['voterID'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Voter ID') . '</span>' . (trim($voterID) != '' ? $voterID : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

            $bankPassbook = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['bankPassbook'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Bank Passbook') . '</span>' . (trim($bankPassbook) != '' ? $bankPassbook : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        }

        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-fee-detail')) {
            $showPreviews = true;
            $receiptCopy = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['receiptCopy'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Receipt Copy') . '</span>' . (trim($receiptCopy) != '' ? $receiptCopy : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        }

        if ($showPreviews) {
            ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom">
                <div class="col-md-12 padding_10_0">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Document Previews') ?></h4>
                    </div>
                    <div class="doc-preview-container">
                        <?= $previews ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">           
            <div class="col-sm-12 margin-top-10">
                <?php if ($isLastStep) { ?>
                    <div class="col-md-12 padding_10_0 theme-box mt10">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix margin-bottom-10">
                            <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
                        </div>
                        <div class="form-grid">
                            <div class="col-sm-12">
                                <?php echo $form->errorSummary([$processModel, $model, $shareModel]); ?>
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
                            echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]);
                        }
                        $btnLabel = $isLastStep ? 'save' : 'Save & Next';
                        echo Yii::$app->controls->save($btnLabel, $processModel);
                        ?>

                        <?php
                        $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code);
                        if ($prevStep) {
                            ?>
                            <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default">Previous</a>
                        <?php } ?>
                    </div>  
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
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
    $('#tblmemberprovisional-bank_code').on('change',function(){
        $('#tblmemberprovisional-ifsc').val('');
    });
    $('#tblmemberprovisional-branch_code').on('change',function(){
            var id = $(this).val();
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                data: 'id='+id,
                success: function(data) {
                        var obj1 = $.parseJSON(data);
                        $('#tblmemberprovisional-ifsc').val(obj1.code);
                }
            });
    });
    $('#tblmemberprovisional-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
            return val.toUpperCase();
        });
   });
   
   enableDisableField();
   $('#tblmemberprovisional-is_aadhar_verify,#tblmemberprovisional-is_verify').on('click',function(){
        enableDisableField();
    });
    function enableDisableField(){
        $('.field-tblmemberprovisional-bank_code').removeClass('disabled no_pointer');
        $('.field-tblmemberprovisional-branch_code').removeClass('disabled no_pointer');
        $('.field-tblmemberprovisional-bank_account_no').removeClass('disabled no_pointer');
        $('#tblmemberprovisional-ifsc').prop('disabled', false);
         $('.field-tblmemberprovisional-adhar_no').removeClass('disabled no_pointer');
         
        if ($('#tblmemberprovisional-is_aadhar_verify').is(':checked')) {
           $('.field-tblmemberprovisional-adhar_no').addClass('disabled no_pointer');
        }
        if ($('#tblmemberprovisional-is_verify').is(':checked')) {
            $('.field-tblmemberprovisional-bank_code').addClass('disabled no_pointer');
            $('.field-tblmemberprovisional-branch_code').addClass('disabled no_pointer');
            $('.field-tblmemberprovisional-bank_account_no').addClass('disabled no_pointer');
            $('.field-tblmemberprovisional-ifsc').addClass('disabled no_pointer');
        }
    }
});
";
$this->registerJs($script, View::POS_END, 'approval_bank_detail');
?>
