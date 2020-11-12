<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Transporters');
$this->params['menu'][] = Yii::$app->controls->update($model->transporter_code);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'transporter_name',
                            'valueColOptions' => ['style' => 'width:30%']
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
                            'attribute' => 'registration_no',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'group' => true,
                    'label' => 'Address Details',
                    'rowOptions' => ['class' => 'bg-default']
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'address',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'district_code',
                            'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sub_district_code',
                            'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'village_code',
                            'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'hamlet_code',
                            'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pincode',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'phone_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
//                [
//                    'group' => true,
//                    'label' => 'Contact Details',
//                    'rowOptions' => ['class' => 'bg-default']
//                ],
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'contact_person',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        [
//                            'attribute' => 'local_contact_person',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                    ],
//                ],
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'mobile_no',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        [
//                            'attribute' => 'email',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                    ],
//                ],
//                [
//                    'group' => true,
//                    'label' => 'Bank Details',
//                    'rowOptions' => ['class' => 'bg-default']
//                ],
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'bank_code',
//                            'value' => isset($model->bankCode) ? $model->bankCode->bank_name:'',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        [
//                            'attribute' => 'branch_code',
//                            'value' => isset($model->branchCode) ? $model->branchCode->branch_name:'',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                    ],
//                ],
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'bank_account_no',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        [
//                            'attribute' => 'ifsc',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                    ],
//                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'gstin',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'tds_per',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pan_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'beneficiary_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'agreement_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'declaration',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'security_cheque_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'security_amount',
                            'format' => Yii::$app->general->CurrencyFormat(),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active),
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
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
        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Bank Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('../../../details/views/tbl-bank-details/_bank_details', [
                    'model' => $model,
                    'dataProvider' => $bdataProvider,
                    'searchModel' => $bsearchModel,
                ])
                ?>
            </div>

            <div class="col-sm-12 col-md-12 padding_left_0 padding_top_20 padding_right_0 margin-bottom-10 clearfix">
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