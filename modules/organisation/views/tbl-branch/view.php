<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'branch');
$this->params['menu'][] = Yii::$app->controls->update($model->branch_code);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            // DetailView Attributes Configuration
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'branch_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'bankCode',
                            'format' => 'html',
                            'label' => 'Bank',
                            'value' => isset($model->bankCode) ? $model->bankCode->bank_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'branch_name',
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
                            'attribute' => 'ifsc',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'state_code',
                            'format' => 'html',
                            'value' => isset($model->stateCode) ? $model->stateCode->state_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'district_code',
                            'format' => 'html',
                            'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sub_district_code',
                            'format' => 'html',
                            'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'village_code',
                            'format' => 'html',
                            'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'hamlet_code',
                            'format' => 'html',
                            'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
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
                            'attribute' => 'pincode',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'contact_person',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'contact_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'valid_from',
                            'value' => Yii::$app->controls->view_date($model->valid_from),
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
//    'condensed' => $condensed,
                'responsive' => true,
//    'hover' => $hover,
                'hAlign' => 'left',
                'vAlign' => 'top',
//    'fadeDelay'=>$fadeDelay,
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
//    'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            ]);
            ?>
        </div>
    </div>
</div>