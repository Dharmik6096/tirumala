<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'member');
$this->params['menu'][] = Yii::$app->controls->add('member');
if ($model->is_active == 1) {
    $this->params['menu'][] = Yii::$app->controls->update($model->member_code);
}
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
                            'attribute' => 'dcs_code',
                            'value' => isset($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'dcs_ref_code',
                            'value' => isset($model->dcsCode) ? $model->dcsCode->ref_code : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'is_dcs_member',
                            'value' => ($model->is_dcs_member == '1') ? 'Yes' : 'No',
                            'valueColOptions' => ['style' => 'width:30%']
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
                            'value' => isset($model->member_class) ? Yii::$app->dropdown->getRecords('member_class')['data'][$model->member_class] : '',
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
                    'columns' => [
                            [
                            'attribute' => 'vendor_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'sap_farmer_code',
                            'format' => 'html',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'employee_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'employee_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'created_by',
                            'value' => Yii::$app->general->getforeignkey($model->userName, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'member_identity_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'aadhaar_card_address',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'applicant_relation',
                            'value' => Yii::$app->general->getforeignkey($model->applicantRelationship, 'relationship'),
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
                            'attribute' => 'witness_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'place',
                            'valueColOptions' => ['style' => 'width:80%']
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
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'rate_class',
                            'value' => !empty($model->rate_class) ? Yii::$app->dropdown->getRecords('rate_class')['data'][$model->rate_class] : '',
                            'valueColOptions' => ['style' => 'width:30%']
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
                    'columns' => [
                            [
                            'attribute' => 'region_code',
                            'value' => Yii::$app->general->getforeignkey($model->regionCode, 'region_name'),
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                    [
                    'group' => true,
                    'label' => 'Bank Details',
                    'rowOptions' => ['class' => 'bg-default']
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
                            'attribute' => 'pan_no',
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
                            'attribute' => 'annual_income',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
//                        [
//                            'attribute' => 'payment_mode',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'is_verified',
                            'label' => Yii::t('app', 'Bank Verification'),
                            'value' => $model->is_verified == 1 ? 'Verified' : ( $model->is_verified == 2 ? 'Reject' : 'Pending'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'is_contact_verified',
                            'label' => Yii::t('app', 'Contact Verification'),
                            'value' => $model->is_contact_verified == 1 ? 'Verified' : ( $model->is_contact_verified == 2 ? 'Reject' : 'Pending'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'bank_remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'contact_remarks',
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
                            'value' => $model->is_active == '1' ? (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 ? 'In Active' : 'Active') : 'In Active',
                            'valueColOptions' => ['style' => 'width:80%'],
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
            ?>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Deactivation Details</h5></div>
        <div class="form-grid">
            <?php
            $attribute = [
                    ['attribute' => 'from_date', 'label' => Yii::t('app', 'From Date'), 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->from_date);
                    }, 'filter' => false],
                    ['attribute' => 'to_date', 'label' => Yii::t('app', 'To Date'), 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->to_date);
                    }, 'filter' => false],
                    ['attribute' => 'remarks', 'filter' => false],
            ];

            $grid_option = [
                'id' => 'detail-list',
                'attributes' => $attribute,
                'active_column' => FALSE,
                    // 'actions' => []
            ];

            echo Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Family Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_family_grid', [
                        'familyMemberModel' => $familyMemberModel,
                        'fDataProvider' => $fDataProvider,
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Animal Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_animal_grid', [
                        'animalMemberModel' => $animalMemberModel,
                        'animalDataProvider' => $animalDataProvider,
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
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
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Member Animal Tag Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?php
                    echo $this->render('../../../veterinary/views/tbl-member-animal-tag-details/index', [
                        'searchModel' => $tagSearchModel,
                        'dataProvider' => $tagDataProvider,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>