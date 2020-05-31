<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

$this->title = Yii::$app->label->title('view', 'Staff Member');
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
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'staff_member_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ex_staff_member_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'staff_member_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'tenure_from_date',
                                'value' => Yii::$app->controls->view_date($model->tenure_from_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'tenure_to_date',
                                'value' => Yii::$app->controls->view_date($model->tenure_to_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'designation_code',
                                'value' => Yii::$app->general->getforeignkey($model->designationCode, 'designation_name'),
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
                                'attribute' => 'qualification_code',
                                'value' => Yii::$app->general->getforeignkey($model->qualificationCode, 'qualification_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'department',
                                'value' => Yii::$app->general->getforeignkey($model->designationCode, 'designation_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'email_id',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'gender_code',
                                'value' => Yii::$app->general->getforeignkey($model->gender, 'gender'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'birth_date',
                                'value' => Yii::$app->controls->view_date($model->birth_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'blood_group_code',
                                'value' => Yii::$app->general->getforeignkey($model->bloodGroup, 'blood_group'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'caste_category_code',
                                'value' => Yii::$app->general->getforeignkey($model->casteCategoryCode, 'caste_category_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_on_role',
                                'value' => Yii::$app->general->getStaticValue($model->is_on_role, 'is_on_role'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'uan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'esic_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'pf_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'payment_mode',
                                'value' => Yii::$app->general->getStaticValue($model->payment_mode, 'payment_mode_member'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bank_code',
                                'value' => Yii::$app->general->getforeignkey($model->bankCode, 'bank_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'branch_code',
                                'value' => Yii::$app->general->getforeignkey($model->branchCode, 'branch_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bank_account_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'aadhar_card_no',
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
                                'attribute' => 'pincode',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'state_code',
                                'value' => Yii::$app->general->getforeignkey($model->stateCode, 'state_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'district_code',
                                'value' => Yii::$app->general->getforeignkey($model->districtCode, 'district_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sub_district_code',
                                'value' => Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name'),
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
                                'attribute' => 'village_code',
                                'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
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
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Designation Details') ?></h5></div>
        <div class="form-grid">
            <?=
            $this->render('_form_grid_designation', [
                'model' => $model,
                'dataProvider' => $ddataProvider,
                'searchModel' => $dsearchModel,
            ])
            ?>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Family Details') ?></h5></div>
        <div class="form-grid">
            <?=
            $this->render('../../../staffmanagement/views/tbl-staff-member-family-details/_form_grid', [
                'model' => $model,
                'dataProvider' => $fdataProvider,
                'searchModel' => $fsearchModel,
                'isaction' => FALSE
            ])
            ?>
        </div>
    </div>
</div>