<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\web\View;

$this->title = Yii::$app->label->title('view', 'Customer Master Provisional');
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
                            'attribute' => 'customer_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'ref_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'customer_type',
                            'value' => Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'customer_code_ex',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'customer_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'local_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
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
                            'attribute' => 'gst_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'state_code',
                            'value' => Yii::$app->general->getforeignkey($model->stateCode, 'state_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'district_code',
                            'value' => Yii::$app->general->getforeignkey($model->districtCode, 'district_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'sub_district_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'village_code',
                            'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'hamlet_code',
                            'value' => Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'route_code',
                            'value' => Yii::$app->general->getforeignkey($model->routeCode, 'route_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sap_vendor_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'ts_code_m',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'ts_code_e',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'x_col2',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'route_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'animal_type_code',
                            'value' => !empty($model->animalTypeCode) ? $model->animalTypeCode->animal_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'distance_from_mcc',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
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
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'firstname',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'lastname',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'surname',
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
                            'attribute' => 'local_firstname',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'local_lastname',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'mobile_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'aadhaar_no',
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
                            'attribute' => 'is_approved',
                            'format' => 'html',
                            'value' => Yii::$app->general->getStaticDropdownVal('approved_status', $model, 'is_approved'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'provisional_from',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'is_aadhar_verify',
                            'value' => Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_aadhar_verify'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'is_bank_verify',
                            'value' => Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_bank_verify'),
                            'valueColOptions' => ['style' => 'width:30%']
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

        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Document Upload</h4>
                </div>
                <div class="col-sm-12">
                    <?php
                    $attribute = [
                        ['attribute' => 'doc_id', 'value' => function ($model) {
                                return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
                            }],
                    ];
                    $grid_option = [
                        'id' => 'document',
                        'attributes' => $attribute,
                        'active_column' => false,
                        'actions' => [
                            'view-attachment' => function ($url, $model) {
                                $attachemnt = $model->attachment;
                                $url = !empty($attachemnt) ? $attachemnt : '';
                                return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank']);
                            },
                        ]
                    ];
                    Yii::$app->grid->bind($dataProviderOther, $attachment, $grid_option, '', false);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Previous Approval Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_process_approval_grid', [
                        'processApprovalModel' => $processApprovalModel,
                        'processApprovalDataProvider' => $processApprovalDataProvider,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "

$(document).ready(function() {
    $('.btn-toolbar.kv-grid-toolbar').hide();
});

";
$this->registerJs($script, View::POS_END, 'provisional_customer_view');
?>