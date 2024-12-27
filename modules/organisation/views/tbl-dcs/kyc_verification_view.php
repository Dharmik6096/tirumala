<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\web\View;
use kartik\form\ActiveForm;

$this->title = Yii::$app->label->title('view', 'Society');
$code = ($type == 'DCS' ? $model->dcs_code : ($type == 'MEMBER' ? $model->member_code : ($type == 'CUSTOMER' ? $model->customer_code : '')));
$name = ($type == 'DCS' ? $model->dcs_name : ($type == 'MEMBER' ? $model->member_name : ($type == 'CUSTOMER' ? $model->customer_name : '')));
?>
<div class="modal modal-default fade" id="AppInformationModal" role="dialog">
    <div class="modal-dialog width_100-200 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Verification Details'); ?> (<?= $code ?>-<?= $name ?>)</h4>
            </div>
            <div class="row">            
                <?php
                $form = ActiveForm::begin([
                            'id' => 'kyc-verify',
                ]);
                ?>
                <div class="modal-body">   
                    <?php 
                        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
                        echo Html::hiddenInput('type', $type);
                        echo Html::hiddenInput('code', $code);
                        echo Html::hiddenInput('responseJson', $responseJson);
                    ?>


                    <div class="panel-body">
                        <div class="panel-subheading">
                            <div class="form-grid">
                                <div class="table-responsive">
                                    <?php
                                    if ($type == 'DCS') {
                                        $attributes = [
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'dcs_name',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'dcs_code_ex',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'ref_code',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'label' => 'Bank Name',
                                                        'value' => !empty($model->bankCode) ? Yii::$app->general->getforeignkey($model->bankCode, 'bank_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'label' => 'Branch Name',
                                                        'value' => !empty($model->branchCode) ? Yii::$app->general->getforeignkey($model->branchCode, 'branch_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'label' => 'Bank Account No.',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'bank_account_no') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'label' => 'Ifsc',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'ifsc') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'dcs_code',
                                                        'label' => 'Beneficiary Name',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'beneficiary_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'aadhaar_no',
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
                                            'deleteOptions' => [// your ajax delete parameters
                                                'params' => ['id' => 1000, 'kvdelete' => true],
                                            ],
                                            'container' => ['id' => 'kv-demo'],
                                        ]);
                                    } elseif ($type == 'MEMBER') {
                                        $attributes = [
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'member_code',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'member_name',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'ex_member_code',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'ref_code',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'member_code',
                                                        'label' => 'Bank Name',
                                                        'value' => !empty($model->bankCode) ? Yii::$app->general->getforeignkey($model->bankCode, 'bank_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'member_code',
                                                        'label' => 'Branch Name',
                                                        'value' => !empty($model->branchCode) ? Yii::$app->general->getforeignkey($model->branchCode, 'branch_name') : '',
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
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'adhar_no',
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
                                            'deleteOptions' => [// your ajax delete parameters
                                                'params' => ['id' => 1000, 'kvdelete' => true],
                                            ],
                                            'container' => ['id' => 'kv-demo'],
                                        ]);
                                    } elseif ($type == 'CUSTOMER') {
                                        $attributes = [
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'customer_name',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'customer_code_ex',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'ref_code',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'label' => 'Bank Name',
                                                        'value' => !empty($model->bankCode) ? Yii::$app->general->getforeignkey($model->bankCode, 'bank_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'label' => 'Branch Name',
                                                        'value' => !empty($model->ifscDetail) ? Yii::$app->general->getforeignkey($model->ifscDetail, 'branch_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'label' => 'Bank Account No.',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'bank_account_no') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'label' => 'Ifsc',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'ifsc') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'customer_code',
                                                        'label' => 'Beneficiary Name',
                                                        'value' => !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'beneficiary_name') : '',
                                                        'valueColOptions' => ['style' => 'width:30%']
                                                    ],
                                                        [
                                                        'attribute' => 'aadhaar_no',
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
                                            'deleteOptions' => [// your ajax delete parameters
                                                'params' => ['id' => 1000, 'kvdelete' => true],
                                            ],
                                            'container' => ['id' => 'kv-demo'],
                                        ]);
                                    }
                                    if (!empty($response)) {
                                        $responseAttributes = [
                                                [
                                                'group' => true,
                                                'label' => 'KYC Details',
                                                'rowOptions' => ['class' => 'bg-default']
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'reference_id',
                                                        'value' => $response->reference_id,
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'name_at_bank',
                                                        'value' => !empty($response->name_at_bank) ? $response->name_at_bank : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'bank_name',
                                                        'value' => !empty($response->bank_name) ? $response->bank_name : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'city',
                                                        'value' => !empty($response->city) ? $response->city : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'branch',
                                                        'value' => !empty($response->branch) ? $response->branch : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'micr',
                                                        'value' => !empty($response->micr) ? $response->micr : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                            [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'bank_account_no',
                                                        'value' => !empty($response->bank_account_no) ? $response->bank_account_no : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'ifsc',
                                                        'value' => !empty($response->ifsc) ? $response->ifsc : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'name_match_result',
                                                        'value' => !empty($response->name_match_result) ? $response->name_match_result : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                        [
                                                        'attribute' => 'name_match_score',
                                                        'value' => !empty($response->name_match_score) ? $response->name_match_score : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:30%'],
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'account_status',
                                                        'value' => $response->account_status,
                                                        'valueColOptions' => function ($t) use ($response) {
                                                            $class = empty($response->account_status) ? 'text-grey' : (strtolower($response->account_status) == 'valid' ? 'text-green' : 'text-red');
                                                            return ['style' => 'width:30%','class' => $class];
                                                         },
                                                    ],
                                                        [
                                                        'attribute' => 'account_status_code',
                                                        'value' => $response->account_status_code,
                                                        'valueColOptions' => function ($t) use ($response) {
                                                            $class = empty($response->account_status) ? 'text-grey' : (strtolower($response->account_status) == 'valid' ? 'text-green' : 'text-red');
                                                            return ['style' => 'width:30%','class' => $class];
                                                         },
                                                    ],
                                                ],
                                            ],
                                                [
                                                'columns' => [
                                                        [
                                                        'attribute' => 'utr',
                                                        'value' => !empty($response->utr) ? $response->utr : 'N/A',
                                                        'valueColOptions' => ['style' => 'width:80%'],
                                                    ],
                                                ],
                                            ],
                                        ];

                                        echo DetailView::widget([
                                            'model' => $model,
                                            'attributes' => $responseAttributes,
                                            'mode' => 'view',
                                            'bordered' => true,
                                            'striped' => false,
                                            'responsive' => true,
                                            'hAlign' => 'left',
                                            'vAlign' => 'top',
                                            'deleteOptions' => [// your ajax delete parameters
                                                'params' => ['id' => 1000, 'kvdelete' => true],
                                            ],
                                            'container' => ['id' => 'kv-demo'],
                                        ]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div> 

                    </div>
                    </br>
                    <div class="panel-footer">
                        <?php
                        if (!empty($response) && strtolower($response->account_status) == 'valid') {
                            echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn-login btn btn-primary submitdata', 'id' => 'verify', 'value' => 'verify', 'name' => 'verify']);
                        }
                        echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn-login btn btn-danger submitdata', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
                        ?>
                        <?= Yii::$app->controls->custombutton('Cancel', 'master-verification', '', 'btn-login'); ?> 
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = '
    $(".submitdata").click(function() {
       var id= $(this).attr("id");
       $(".set_operation").val(id);
        $("#kyc-verify").submit();
    });
';
$this->registerJs($script, View::POS_END, 'kyc-verifications');
