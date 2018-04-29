<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblLocalMilkSale */

$this->title = Yii::t('app', Yii::$app->label->title('view', 'Local Milk Sale'));
?>
<div class="tbl-local-milk-sale-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

                    <?php $attributes = [
                        [
                            'columns' => [
                                [
                                    'attribute'=>'local_milk_sale_code',
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'account_effect',
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'amount',
                                    'format' => Yii::$app->general->CurrencyFormat(),
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'date',
                                    'value' => Yii::$app->controls->view_date($model->date),
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'discount',
                                    'format' => Yii::$app->general->CurrencyFormat(),
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'cash',
                                    'format' => Yii::$app->general->CurrencyFormat(),
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'credit',
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'coupon',
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'entry_type',
                                    'value'=>$model->entryType()[$model->entry_type],
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'payment_mode',
                                    'value'=>($model->payment_mode==1)?'Bank':'Cash',
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'quantity',
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'rate',
                                    'format' => Yii::$app->general->CurrencyFormat(),
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'shift_id',
                                    'value'=>$model->shift->shift,
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'collection_point_code',
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'dcs_code',
                                    'value'=>$model->dcsCode->dcs_name,
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'member_code',
                                     'value'=>$model->memberCode->member_name,
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute'=>'milk_class',
                                    'value'=>$model->milkClass->class_name,
                                    'valueColOptions'=>['style'=>'width:30%']
                                ],
                                 [
                                    'attribute'=>'milk_type',
                                     'value'=>$model->milkType->animal_type_name,
                                    'valueColOptions'=>['style'=>'width:30%'],
                                ],

                            ],
                        ],
                        [
                            'columns' => [
                                 [
                                    'attribute'=>'sub_center_code',
                                     'value'=>$model->subCenterCode->sub_center_name,
                                    'valueColOptions'=>['style'=>'width:30%'],
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
