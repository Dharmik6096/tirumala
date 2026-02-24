<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\components\ActiveForm;

$this->title = 'Member Approval View - Address & Adhar Details';
?>

<?=
$this->render('approval_view_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>

<div class="panel panel-default panel-main">
    <div class="panel-body">

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-address-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Address Details') ?></h4>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'local_address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'state_code',
                                'value' => Yii::$app->general->getforeignkey($model->stateCode, 'state_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'district_code',
                                'value' => Yii::$app->general->getforeignkey($model->districtCode, 'district_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'sub_district_code',
                                'value' => Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'village_code',
                                'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'hamlet_code',
                                'value' => Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'region_code',
                                'value' => Yii::$app->general->getforeignkey($model->regionCode, 'region_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'post_office',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'pincode',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mobile_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'is_contact_verified',
                                'value' => ($model->is_contact_verified == 0) ? 'Pending' : ($model->is_contact_verified == 1 ? 'Verify' : ''),
                                'valueColOptions' => ['style' => 'width:30%']
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
                    'panel' => false,
                ]);
                ?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-adhar-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Aadhar Details') ?></h4>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'aadhaar_card_address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'adhar_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'email',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'email_relation',
                                'value' => Yii::$app->general->getforeignkey($model->relationship, 'relationship'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'is_email_verify',
                                'value' => ($model->is_email_verify == 0) ? 'Pending' : ($model->is_email_verify == 1 ? 'Verify' : ''),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'is_aadhar_verify',
                                'value' => ($model->is_aadhar_verify == 0) ? 'Pending' : ($model->is_aadhar_verify == 1 ? 'Verify' : ''),
                                'valueColOptions' => ['style' => 'width:30%']
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
                    'enableEditMode' => false,
                    'panel' => false,
                ]);
                ?>
            </div>
        <?php } ?>

        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Document Previews') ?></h4>
                </div>
                <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-address-detail')) { ?>
                    <div class="col-sm-3 text-center">
                        <?= Yii::t('app', 'Current Address Proof :') ?>
                        <br>
                        <?= Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['currentAddressProof'], 'image') ?>
                    </div>
                    <div class="col-sm-3 text-center">
                        <?= Yii::t('app', 'Other ID Proof :') ?>
                        <br>
                        <?= Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['otherIdProof'], 'image') ?>
                    </div>
                <?php } ?>
                <div class="col-sm-3 text-center">
                    <?= Yii::t('app', 'Aadharcard Front Photo :') ?>
                    <br>
                    <?= Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['aadharCard'], 'image') ?>
                </div>
                <div class="col-sm-3 text-center">
                    <?= Yii::t('app', 'Aadharcard Back Photo :') ?>
                    <br>
                    <?= Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['aadharCardBack'], 'image') ?>
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
                                echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute btn-login me-2', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#ProvisionalModal',]);
                            }
                            $btnLabel = $isLastStep ? 'save' : 'Save & Next';
                            echo Yii::$app->controls->save($btnLabel, $processModel);
                            ?>

                            <?php
                            $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code, true);
                            if ($prevStep) {
                                ?>
                                <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default btn-login">Previous</a>
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
