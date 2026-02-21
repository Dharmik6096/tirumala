<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Member Approval - Share Details';
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
                    'id' => 'share-detail'
        ]);
        ?>
        <?php echo $form->errorSummary($memberShareDetail); ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Share Details') ?></h4>
                </div>
                <div class="row hr10">
                    <div class="col-sm-12">
                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->dropdownStatic('mode_of_payment', $memberShareDetail, $form, 'form-group', $memberShareDetail->getAttributeLabel('mode_of_payment'), false, 'mode_of_payment', false); ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($memberShareDetail, 'ref_no')->textInput() ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($memberShareDetail, 'bank_name')->textInput() ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Yii::$app->controls->date($memberShareDetail, $form, 'deposit_date', '', true); ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'amount_deposit')->textInput() ?>
                        </div>
                        <div class="col-sm-2 default_hide">
                            <?= $form->field($memberShareDetail, 'no_of_share_req')->textInput(['readonly' => true]) ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'no_of_share_apply')->textInput() ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'admission_fee')->textInput(['readonly' => TRUE]) ?>
                        </div>
                        <div class="col-sm-2 number-validate disp_none">
                            <?= $form->field($memberShareDetail, 'per_share_rate')->textInput() ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'payable_share_amount')->textInput(['readonly' => TRUE]) ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'amount_payable')->textInput(['readonly' => TRUE]) ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($memberShareDetail, 'total_amount')->textInput(['readonly' => TRUE]) ?>
                        </div>
                    </div>
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
                        echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]);

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
 $('#tblmemberprovisionalsharedetails-no_of_share_apply').on('change', function() {
        var noOfShareApply = parseFloat($(this).val());
        var perShareRate = parseFloat($('#tblmemberprovisionalsharedetails-per_share_rate').val());
        var admissionFee = parseFloat($('#tblmemberprovisionalsharedetails-admission_fee').val());
        var payableShareAmount = noOfShareApply * perShareRate;
        $('#tblmemberprovisionalsharedetails-payable_share_amount').val(payableShareAmount.toFixed(2));
        var amountPayable = payableShareAmount + admissionFee;
        $('#tblmemberprovisionalsharedetails-amount_payable').val(amountPayable.toFixed(2));
        $('#tblmemberprovisionalsharedetails-total_amount').val(amountPayable.toFixed(2));

}); 
";
$this->registerJs($script, View::POS_END, 'member-share-details-script');
?>
