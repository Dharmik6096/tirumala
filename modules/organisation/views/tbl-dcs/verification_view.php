<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Society');
$code = ($type == 'DCS' ? $model->dcs_code : ($type == 'MEMBER' ? $model->member_code : ($type == 'CUSTOMER' ? $model->customer_code : '')));
$name = ($type == 'DCS' ? $model->dcs_name : ($type == 'MEMBER' ? $model->member_name : ($type == 'CUSTOMER' ? $model->customer_name : '')));
?>
<div class="modal modal-default fade" id="AppInformationModal" role="dialog">
    <div class="modal-dialog width_100-200">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Verification Details'); ?> (<?= $code ?>-<?= $name ?>)</h4>
            </div>
            <div class="modal-body">            
                <div class="panel-body">
                    <div class="panel-subheading">
                        <div class="form-grid">
                            <div class="table-responsive">
                                <?php
                                // DetailView Attributes Configuration
                                if ($type == 'DCS') {
                                    $attributes = [
                                        [
                                            'columns' => [
                                                [
                                                    'label' => 'Milk Type',
                                                    'format' => 'html',
                                                    'value' => $model->milkType(),
                                                    'valueColOptions' => ['style' => 'width:80%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'dcs_short_name',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                                [
                                                    'attribute' => 'local_name',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'local_short_name',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                                [
                                                    'attribute' => 'phone_no',
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
                                                    'attribute' => 'pan_no',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'fssi',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                                [
                                                    'attribute' => 'address',
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'local_address',
                                                    'valueColOptions' => ['style' => 'width:30%'],
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
                                                    'value' => Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name'),
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'block_code',
                                                    'value' => Yii::$app->general->getforeignkey($model->blockCode, 'block_name'),
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                                [
                                                    'attribute' => 'village_code',
                                                    'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'hamlet_code',
                                                    'value' => Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name'),
                                                    'valueColOptions' => ['style' => 'width:30%'],
                                                ],
                                                [
                                                    'attribute' => 'pincode',
                                                    'valueColOptions' => ['style' => 'width:30%'],
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
                                } elseif ($type == 'MEMBER') {
                                    $attributes = [
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'union_code',
                                                    'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                                    'valueColOptions' => ['style' => 'width:80%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'dcs_code',
                                                    'value' => isset($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                                                    'valueColOptions' => ['style' => 'width:80%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'member_code',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'ex_member_code',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'ref_code',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'member_type_code',
                                                    'value' => isset($model->memberTypeCode) ? $model->memberTypeCode->member_type_name : '',
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
                                                    'attribute' => 'local_name',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'father_name',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'local_father_name',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'surname',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'local_surname',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'nominee_name',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'local_nominee_name',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'dob',
                                                    'value' => Yii::$app->controls->view_date($model->dob),
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'bloodgroup_code',
                                                    'value' => !empty($model->bloodGroupCode) ? $model->bloodGroupCode->blood_group : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'gender_code',
                                                    'value' => !empty($model->genderCode) ? $model->genderCode->gender : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'qualification_code',
                                                    'value' => !empty($model->qualificationCode) ? $model->qualificationCode->qualification_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'caste_category_code',
                                                    'value' => !empty($model->casteCategoryCode) ? $model->casteCategoryCode->caste_category_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'religion_code',
                                                    'value' => !empty($model->religionCode) ? $model->religionCode->religion : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'nominee_relation',
                                                    'value' => !empty($model->relationship) ? $model->relationship->relationship : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'voter_id',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'registration_date',
                                                    'value' => Yii::$app->controls->view_date($model->registration_date),
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'member_class',
                                                    'value' => ($model->member_class == 1) ? 'APL' : ($model->member_class == 2 ? 'BPL' : ''),
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'reference_code',
                                                    'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') . $model->ex_member_code,
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'x_col3',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => true,
                                            'label' => 'Animal Details',
                                            'rowOptions' => ['class' => 'bg-default']
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'animal_type_code',
                                                    'value' => !empty($model->animalTypeCode) ? $model->animalTypeCode->animal_type_name : '',
                                                    'valueColOptions' => ['style' => 'width:80%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'no_of_buffalo',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'no_of_cow_cross',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'no_of_cow_ind',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'total_animals',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
//                        [
//                            'attribute' => 'land_class',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
                                                [
                                                    'attribute' => 'total_land',
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
                                                    'value' => !empty($model->stateCode) ? $model->stateCode->state_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'district_code',
                                                    'value' => !empty($model->districtCode) ? $model->districtCode->district_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'sub_district_code',
                                                    'value' => !empty($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'village_code',
                                                    'value' => !empty($model->villageCode) ? $model->villageCode->village_name : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'hamlet_code',
                                                    'value' => !empty($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
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
                                                    'attribute' => 'email',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => true,
                                            'label' => 'KYC Details',
                                            'rowOptions' => ['class' => 'bg-default']
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'member_code',
                                                    'label' => 'Address Proof',
                                                    'value' => !empty($model->kycCode) ? Yii::$app->general->getforeignkey($model->kycCode->addressDoc, 'doc_name') : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'member_code',
                                                    'label' => 'Bank Proof',
                                                    'value' => !empty($model->kycCode) ? Yii::$app->general->getforeignkey($model->kycCode->bankDoc, 'doc_name') : '',
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                            ],
                                        ],
                                        [
                                            'columns' => [
                                                [
                                                    'attribute' => 'member_code',
                                                    'label' => 'Remarks',
                                                    'value' => Yii::$app->general->getforeignkey($model->kycCode, 'kyc_remark'),
                                                    'valueColOptions' => ['style' => 'width:30%']
                                                ],
                                                [
                                                    'attribute' => 'member_code',
                                                    'label' => 'KYC Done',
                                                    'value' => (Yii::$app->general->getforeignkey($model->kycCode, 'is_kyc') == '1') ? 'Yes' : 'No',
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
                                } elseif ($type == 'CUSTOMER') {
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
                                                    'valueColOptions' => ['style' => 'width:80%']
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
                                }
                                ?>
                            </div>
                        </div>
                    </div> 

                </div>
                </br>


            </div>
        </div>
    </div>
</div>