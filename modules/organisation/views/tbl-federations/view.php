<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'federation');
if (Yii::$app->general->checkAccess('/organisation/tbl-federations/update')) {
    $this->params['menu'][] = Yii::$app->controls->update($model->federation_code, '/organisation/tbl-federations/update');
}
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-university"></i> Bank Details'), ['/organisation/tbl-federations/bank-details', 'id' => $model->federation_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fas fa-user-circle"></i> Contact Details'), ['/organisation/tbl-federations/contact-details', 'id' => $model->federation_code], ['class' => 'btn btn-danger btn-block']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                // DetailView Attributes Configuration
                $attributes = [
                    [

                        'columns' => [
                            [
                                'attribute' => 'federation_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'federation_code_ex',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [

                        'columns' => [
                            [
                                'attribute' => 'federation_name',
                                'valueColOptions' => ['style' => 'width:80%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:80%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'federation_short_name',
                                'valueColOptions' => ['style' => 'width:80%'],
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
                                'attribute' => 'registration_no',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'city',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'address',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_address',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'fax_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'contact_person_pan_no',
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Bank Details</h5></div>
        <div class="form-grid">
            <?=
            $this->render('../../../details/views/tbl-bank-details/_bank_details', [
                'model' => $model,
                'dataProvider' => $bdataProvider,
                'searchModel' => $bsearchModel,
            ])
            ?>
        </div>

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Contact Details</h5></div>
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