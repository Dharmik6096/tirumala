<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\components\ActiveForm;

$this->title = 'Member Approval View - Member Details';
?>

<?=
$this->render('approval_view_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>

<div class="panel panel-default panel-main">
    <div class="panel-body">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Member Details') ?></h4>
        </div>
        <div class="clearfix"></div>
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->tblDcsBmc, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'application_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'member_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'father_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'gender_code',
                            'value' => Yii::$app->general->getforeignkey($model->genderCode, 'gender'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dob',
                            'value' => Yii::$app->controls->view_date($model->dob),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'age',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'qualification_code',
                            'value' => Yii::$app->general->getforeignkey($model->qualificationCode, 'qualification_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'caste_category_code',
                            'value' => Yii::$app->general->getforeignkey($model->casteCategoryCode, 'caste_category_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'religion_code',
                            'value' => Yii::$app->general->getforeignkey($model->religionCode, 'religion'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'member_type_code',
                            'value' => Yii::$app->general->getforeignkey($model->memberTypeCode, 'member_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'registration_date',
                            'value' => Yii::$app->controls->view_date($model->registration_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'remarks',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>

        <?php
        $previews = '';
        $applicantPhoto = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['applicantPhoto'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Applicant Photo') . '</span>' . (trim($applicantPhoto) != '' ? $applicantPhoto : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

        $signature = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['signatureOfApplicant'], 'image');
        $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Signature of Witness') . '</span>' . (trim($signature) != '' ? $signature : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

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

        <?php $form = ActiveForm::begin(); ?>
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
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>
