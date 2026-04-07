<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Member Approval - Member Details';
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
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

        <div class="row theme_border_left theme_border_right theme_border_bottom mt-10">
            <div class="col-md-12 padding_10_0">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Member Details') ?> </h4>
                </div>
                <div class="col-sm-2" id="union">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', true, true); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberprovisional-union_code', 'plant_code', true, false, '', true); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberprovisional-plant_code', 'mcc_plant_code', true, false, '', true); ?>
                </div>  
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberprovisional-mcc_plant_code', 'bmc_code', true, false, '', '', true); ?>
                </div>
                <div class="col-sm-2">
                    <?php $readonly = (empty($model->provisional_status) || (($model->provisional_status == 'Pending' || $model->provisional_status == 'Reroute') && $model->provisional_from != 'mobile_update')) ? false : true; ?>
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmemberprovisional-bmc_code', 'dcs_code', true, false, '', $readonly); ?>         
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel('member_type_code')); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'member_name')->textInput() ?>
                </div>

                <div class="col-sm-2">
                    <?= $form->field($model, 'local_name')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'father_name')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'local_father_name')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'surname')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'local_surname')->textInput() ?>
                </div>

                <div class="col-sm-2">
                    <?= Yii::$app->controls->date($model, $form, 'dob'); ?>
                </div>
                <div class="col-sm-2 number-validate">
                    <?= $form->field($model, 'age')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('gender', $model, $form, '', 'Gender'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('qualification', $model, $form, '', 'Qualification'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('caste-category', $model, $form, '', 'Caste/Category'); ?>
                </div>

                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('religion', $model, $form, '', 'Religion'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'remarks')->textarea(['rows' => 1]) ?>
                </div>
            </div>
        </div>

        <?php
        $previews = '';
        $applicantPhoto = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['applicantPhoto'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Applicant Photo') . '</span>' . (trim($applicantPhoto) != '' ? $applicantPhoto : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

        $signature = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['signatureOfApplicant'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Signature of Witness') . '</span>' . (trim($signature) != '' ? $signature : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
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
                <div class="form-group">
                    <?php if ($isLastStep) { ?>
                        <div class="col-md-12 padding_10_0 theme-box mt10">
                            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix margin-bottom-10">
                                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
                            </div>
                            <div class="form-grid">
                                <div class="col-sm-12">
                                    <?php echo $form->errorSummary($processModel); ?>
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

                            $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code);
                            if ($prevStep) {
                                ?>
                                <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default">Previous</a>
                            <?php } ?>
                        </div>  
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
    $('.btn-toolbar.kv-grid-toolbar').hide();

    
    $('#tblmemberprovisional-route_code').on('change', function(e) {
            var module_code = $(this).val();
            var module_name = 'routeMapping';
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/details/tbl-contact-details/contact-details']) . "',
            data: 'module_code='+module_code+'&module_name='+module_name,
            success: function(response) {
                var obj1 = $.parseJSON(response);
                var data = obj1.data;
                if(data){
                    $('#tblmemberprovisional-supervisor_employee_id').val(data.employee_code);
                    $('#tblmemberprovisional-supervisor_employee_name').val(data.firstname);
                }
            }
        });
    });
});
";
$this->registerJs($script, View::POS_END, 'approval_member_detail');
?>
