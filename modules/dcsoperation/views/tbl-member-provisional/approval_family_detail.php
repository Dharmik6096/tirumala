<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\helpers\Url;

$this->title = 'Member Approval - Family Details';
?>
<?=
$this->render('approval_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>
<div class="panel panel-default panel-main">
    <div class="panel-body">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin-bottom-10">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Family Details') ?></h4>
                </div>

                <div class="col-sm-12">
                    <div class="form-grid hide-grid-settings">
                        <?=
                        $this->render('@app/modules/dcsoperation/views/tbl-member-provisional-family-details/create', [
                            'msearchModel' => $msearchModel,
                            'mdataProvider' => $mdataProvider,
                            'memberFamilyDetail' => $memberFamilyDetail,
                            'memberFamilySearchModel' => $memberFamilySearchModel,
                            'memberFamilyDataProvider' => $memberFamilyDataProvider,
                            'model' => $model,
                            'tabview' => true
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $form = ActiveForm::begin(); ?>
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
                        if ($isLastStep) {
                            echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute btn-login me-2', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#ProvisionalModal',]);
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
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
echo $this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
]);
?>