<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Asset Detail');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body hide-grid-settings">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'asset_detail_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'asset_group_code',
                            'value' => Yii::$app->general->getforeignkey($model->assetGroupCode, 'asset_group_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'asset_code',
                            'value' => Yii::$app->general->getforeignkey($model->assetCode, 'asset_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'serial_number',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'store_location_type',
                            'value' => Yii::$app->general->getmultiforeignkey($model->storeLocCode, ['storeLocType'], 'slt_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'store_location_code',
                            'value' => Yii::$app->general->getforeignkey($model->storeLocCode, 'store_location_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'manufacturer_code',
                            'value' => Yii::$app->general->getforeignkey($model->manufacturerCode, 'customer_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'capacity',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'purchase_date',
                            'value' => Yii::$app->controls->view_date($model->purchase_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'put_to_use_date',
                            'value' => Yii::$app->controls->view_date($model->put_to_use_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'warranty_period',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'maintanance_duration_in_days',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'make',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'current_status',
                            'value' => (isset($model->current_status) && $model->current_status != null) ? Yii::$app->dropdown->getRecords('asset_detail_status')['data'][$model->current_status] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'cluster_email',
                            'value' => Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'cluster_email'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'cluster_mobile',
                            'value' => Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'cluster_mobile'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'vendor_email',
                            'value' => Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'vendor_email'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'vendor_mobile',
                            'value' => Yii::$app->general->getforeignkey($model->assetClusterVendorInfo, 'vendor_mobile'),
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
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Attachment</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_attachment_list', [
                    'attachment' => $attachment,
                    'attachmentDataProvider' => $attachmentDataProvider
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Asset Transaction</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('asset_transaction_detail', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
