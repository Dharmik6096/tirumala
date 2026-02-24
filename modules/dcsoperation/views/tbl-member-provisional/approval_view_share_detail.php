<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\components\ActiveForm;

$this->title = 'Member Approval View - Share Details';
?>

<?=
$this->render('approval_view_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>

<div class="panel panel-default panel-main">
    <div class="panel-body">

        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Share Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_share_grid', [
                        'shareMemberModel' => $shareMemberModel,
                        'shareDataProvider' => $shareDataProvider,
                    ])
                    ?>
                </div>
            </div>
        </div>


        <div class="row">           
            <?php $form = ActiveForm::begin(); ?>
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
                                        <div class="col-sm-4">
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
                            $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code, true);
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
</div>
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>
