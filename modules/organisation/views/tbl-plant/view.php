<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Plants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'][] = Yii::$app->controls->update($model->plant_code);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-plant/contact-details', 'id' => $model->plant_code], ['class' => 'btn btn-danger btn-block']);
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
                                'attribute' => 'plant_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'plant_code_ex',
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
                                'attribute' => 'name',
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
                                'attribute' => 'capacity',
                                'format' => 'html',
                                'value' => isset($model->capacity0) ? $model->capacity0->value : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'valid_from',
                                'value' => Yii::$app->controls->view_date($model->valid_from),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'union_code',
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'email',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
//                            [
//                                'attribute' => 'mobile_no',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
//                        ],
//                    ],
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'contact_person',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
//                            [
//                                'attribute' => 'local_contact_person_name',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
//                            
//                        ],
//                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'description',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'state_code',
                                'value' => isset($model->stateCode) ? $model->stateCode->state_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'district_code',
                                'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'sub_district_code',
                                'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'village_code',
                                'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'hamlet_code',
                                'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'sap_vendor_code',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_virtual_plant',
                                'value' => $model->is_virtual_plant == 1 ? 'Yes' : 'No',
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
                ?>
            </div>
        </div>

        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Contact Details</h5></div> -->
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

        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">MCC Mapping</h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">MCC Mapping</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_plant_mcc_grid', [
                    'model' => $model,
                    'dataProvider' => $mccdataProvider,
                    'searchModel' => $mccsearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading mb15">Plant Dock Mapping</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_plant_dock_grid', [
                        'dockdataProvider' => $dockdataProvider,
                        'docksearchModel' => $docksearchModel,
                        'mapping_flag' => 'view',
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>