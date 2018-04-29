<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMilkDispatch */


$this->title = Yii::t('app', Yii::$app->label->title('view', 'Milk Dispatch'));
?>
<div class="tbl-milk-dispatch-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

            <?php $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute'=>'milk_dispatch_code',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'acidity',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'avg_clr',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'avg_fat',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'avg_snf',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'challan_no',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'chamber_no',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'density',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'destination_code',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'dip_stick_reading_opening',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'dip_stick_reading_closing',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'head_load_kms',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute'=>'dispatch_qty',
                                'valueColOptions'=>['style'=>'width:30%']
                            ],
                             [
                                'attribute'=>'freezing_point',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'format' => 'html','valueColOptions'=>['style'=>'width:30%'],
                                'value' => Yii::$app->controls->view_date($model->from_date)
                            ],
                             [
                                'attribute'=>'from_shift',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'to_date',
                                'format' => 'html','valueColOptions'=>['style'=>'width:30%'],
                                'value' => Yii::$app->controls->view_date($model->to_date)
                            ],
                             [
                                'attribute'=>'to_shift',
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'lactose','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'non_default_dispatch',
                                'format' => 'html','valueColOptions'=>['style'=>'width:30%'],
                                'value' => $model->non_default_dispatch==1?'True':'False'
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'nos_of_can','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'protein','valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'route_no','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'seal_no','valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'temp','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'to_bmc_plant',
                                'format' => 'html',
                                'value' => $model->getBmcPlant(),'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_in_time','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'vehicle_no','valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_out_time','valueColOptions'=>['style'=>'width:30%'],
                            ],
                             [
                                'attribute' => 'water','valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dcs_code',
                                 'value'=>$model->dcsCode->dcs_name,
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],
                            [
                                'attribute' => 'sub_center_code',
                                 'value'=>$model->subCenterCode->sub_center_name,
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'milk_quality_type',
                                 'value'=>$model->milkQualityType->milk_quality_type_name,
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],
                            [
                                'attribute' => 'milk_type',
                                 'value'=>$model->milkType->animal_type_name,
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],

                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                 'value'=>$model->unionCode->union_name,
                                'valueColOptions'=>['style'=>'width:30%'],
                            ],
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html','valueColOptions'=>['style'=>'width:30%'],
                                'value' => GeneralFunctions::getRecordStatus($model->is_active)
                            ],

                        ],
                    ],
            ]; 
            
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'deleteOptions'=>[ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete'=>true],
                ],
                'container' => ['id'=>'kv-demo'],
            ]);
            
            ?>
            
        </div>
             </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>                      
</div>
