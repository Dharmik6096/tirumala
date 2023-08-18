<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->label->title('view', 'provisional member');
//$this->params['menu'][] = Yii::$app->controls->add('provisional member');
//if ($model->is_approved != 1) {
//    $this->params['menu'][] = Yii::$app->controls->update($model->provisional_member_code);
//}
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class = "panel-body">
        <div class = "table-responsive">
            <?php
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
                            'attribute' => 'bmc_code',
                            'value' => (string) $model->bmc_code,
                            'valueColOptions' => ['style' => 'width:30%']],
                            [
                            'attribute' => 'bmc_name',
                            'value' => isset($model->tblDcsBmc) ? $model->tblDcsBmc->bmc_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'society_code',
                            'value' => (string) $model->dcs_code,
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
                            'attribute' => 'member_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'reference_code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') . $model->ex_member_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'pro_ex_member_code',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'ex_member_code',
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
                            'attribute' => 'pan_no',
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
                            'attribute' => 'annual_income',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
//                        [
//                            'attribute' => 'payment_mode',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
                        [
                            'attribute' => 'is_approved',
                            'format' => 'html',
                            'value' => $model->is_approved == 1 ? 'Approved' : 'Pending',
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                    // [
                    //     'group' => true,
                    //     'label' => 'KYC Details',
                    //     'rowOptions' => ['class' => 'bg-default']
                    // ],
                    // [
                    //     'columns' => [
                    //         [
                    //             'attribute' => 'member_code',
                    //             'label' => 'Address Proof',
                    //             'value' => !empty($model->kycCode) ? Yii::$app->general->getforeignkey($model->kycCode->addressDoc, 'doc_name') : '',
                    //             'valueColOptions' => ['style' => 'width:30%']
                    //         ],
                    //         [
                    //             'attribute' => 'member_code',
                    //             'label' => 'Bank Proof',
                    //             'value' => !empty($model->kycCode) ? Yii::$app->general->getforeignkey($model->kycCode->bankDoc, 'doc_name') : '',
                    //             'valueColOptions' => ['style' => 'width:30%']
                    //         ],
                    //     ],
                    // ],
                    // [
                    //     'columns' => [
                    //         [
                    //             'attribute' => 'member_code',
                    //             'label' => 'Remarks',
                    //             'value' => Yii::$app->general->getforeignkey($model->kycCode, 'kyc_remark'),
                    //             'valueColOptions' => ['style' => 'width:30%']
                    //         ],
                    //         [
                    //             'attribute' => 'member_code',
                    //             'label' => 'KYC Done',
                    //             'value' => (Yii::$app->general->getforeignkey($model->kycCode, 'is_kyc') == '1') ? 'Yes' : 'No',
                    //             'valueColOptions' => ['style' => 'width:30%']
                    //         ],
                    //     ],
                    // ],
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
    <div class="row">
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Milk Collection</h4>
            </div>
            <div class="col-sm-12">
                <?=
                $this->render('_milk_collection_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Document Upload</h4>
            </div>
            <div class="col-sm-12">
                <?=
                $this->render('_document_grid', [
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                ])
                ?>
            </div>
        </div>
    </div>
    <br>
    <?php if ($flag == 'approve') { ?>
        <?php
        $form = ActiveForm::begin([
                    'options' => [],
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'action' => ['provisional-members-approvals'],
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>

        <div class="row">

            <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Confirm'), ['class' => 'btn btn-danger']) ?>
                    <?= Yii::$app->controls->custombutton('Cancle', '/dcsoperation/tbl-member-provisional/index'); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    </div>
<?php } ?>
</div>
<?php
$script = "

$(document).ready(function() {
    $('.btn-toolbar.kv-grid-toolbar').hide();
});

";

$this->registerJs($script, View::POS_END, 'provisional_view');
?>