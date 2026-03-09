<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\components\ActiveForm;

$this->title = 'Member Approval View - Bank, Fee & Mismatch Details';
?>

<?=
$this->render('approval_view_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>

<div class="panel panel-default panel-main">
    <div class="panel-body">

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-bank-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Bank Details') ?></h4>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'bank_code',
                                'value' => Yii::$app->general->getforeignkey($model->bankCode, 'bank_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'branch_code',
                                'value' => Yii::$app->general->getforeignkey($model->branchCode, 'branch_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'bank_account_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'ifsc',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'beneficiary_name',
                                'value' => (!empty($model->member_name) ? $model->member_name : '') . ' ' . (!empty($model->surname) ? $model->surname : ''),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'pan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'voter_id',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'annual_income',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'is_verify',
                                'value' => ($model->is_verify == 0) ? 'Pending' : ($model->is_verify == 1 ? 'Verify' : ''),
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
                    'enableEditMode' => false,
                    'panel' => false,
                ]);
                ?>
            </div>


        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-fee-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Fee Details') ?></h4>
            </div>
            <div class="clearfix"></div> 
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'mode_of_payment',
                                'value' => isset($shareModel->mode_of_payment) ? Yii::$app->dropdown->getRecords('mode_of_payment')['data'][$shareModel->mode_of_payment] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'amount_deposit',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'deposit_date',
                                'value' => Yii::$app->controls->view_date($shareModel->deposit_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'ref_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'bank_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'receipt_scan_copy',
                                'label' => 'Fee Receipt Number',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];
                echo DetailView::widget([
                    'model' => $shareModel,
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

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-mismatch-detail')) { ?>
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
        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-bank-detail')) {
            $showPreviews = true;
            $panCard = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['panCard'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Pan Card') . '</span>' . (trim($panCard) != '' ? $panCard : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

            $voterID = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['voterID'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Voter ID') . '</span>' . (trim($voterID) != '' ? $voterID : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

            $bankPassbook = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['bankPassbook'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Bank Passbook') . '</span>' . (trim($bankPassbook) != '' ? $bankPassbook : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        }

        if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-fee-detail')) {
            $showPreviews = true;
            $receiptCopy = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['receiptCopy'], 'image');
            $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Receipt Copy') . '</span>' . (trim($receiptCopy) != '' ? $receiptCopy : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
        }

        if ($showPreviews) {
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
        <?php } ?>
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
