<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Vendor Matser');
$this->params['menu'][] = Yii::$app->controls->update($model->vendor_master_code);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/product/tbl-vendor-master/contact-details', 'id' => $model->vendor_master_code], ['class' => 'btn btn-danger btn-block']);
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                // DetailView Attributes Configuration
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'vendor_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vendor_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'adhar_no',
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
                                'attribute' => 'union_code',
                                'label' => 'Union',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vendor_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bank_code',
                                'value' => !empty($model->bankCode) ? $model->bankCode->bank_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'branch_code',
                                'value' => !empty($model->branchCode) ? $model->branchCode->branch_name : '',
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
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                                'columns' => [
                                        [
                                                'attribute' => 'local_name',
                                                'valueColOptions' => ['style' => 'width:30%']
                                        ],
                                        [
                                                'attribute' => 'ledger_code',
                                                'valueColOptions' => ['style' => 'width:30%']
                                        ]
                                ],

                        ],
                ];

                // View file rendering the widget
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
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Contact Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('../../../details/views/tbl-contact-details/_contact_details', [
                    'model' => $model,
                    'dataProvider' => $cdataProvider,
                    'searchModel' => $csearchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>