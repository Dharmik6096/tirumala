<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Consolidate Challan Preview');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
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
                                'attribute' => 'transaction_date',
                                'value' => Yii::$app->controls->view_date($model->transaction_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'trip_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transporter_code',
                                'value' => Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_code',
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no'),
                                'label' => Yii::t('app', 'Vehicle No.'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_code',
                                'label' => Yii::t('app', 'Driver Name'),
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_code',
                                'label' => Yii::t('app', 'Driver Contact No.'),
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_contact_no'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'kg_fat',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'kg_snf',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'total_qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'rejected_count',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'challan_no',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_detail',
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
        <p> <h5 class="panel-subtitle"><?= Yii::t('app', 'Dispatch Detail') ?></h5> </p>
        <div class="clearfix"></div>
        <div class="hide_toolbar_only hide_filters_only">
            <?php
            $attribute = [
                ['attribute' => 'bmc_code',
                    'label' => Yii::t('app', 'BMC Code'),
                    'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'bmc_name',
                    'label' => Yii::t('app', 'BMC Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'challan_no',
                    'label' => Yii::t('app', 'Challan No.'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcMilkDispatchCode, 'challan_no');
                    }, 'vAlign' => 'middle'],
                ['attribute' => 'milk_type_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
                    }, 'vAlign' => 'middle'],
                ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
                    }, 'vAlign' => 'middle'],
                'dispatch_qty',
                'fat',
                'snf',
                'rtpl',
                'amount',
                ['attribute' => 'sample_no',
                    'label' => Yii::t('app', 'Sample No.'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->sampleBottleNo, 'config_result');
                    }, 'vAlign' => 'middle'],
                [
                    'attribute' => 'is_rejected',
                    'value' => function($model) {
                        return $model->is_rejected == 1 ? 'Yes' : 'No';
                    }, 'filter' => false],
            ];

            $grid_option = [
                'id' => 'bmc-milk-dispatch-txn-list',
                'attributes' => $attribute,
                'active_column' => FALSE,
            ];

            $rowOptions = function ($model) {
                return $model->is_rejected == 1 ? ['class' => 'danger'] : '';
            };

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], true, [], [], true, $rowOptions);
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php
    $form = ActiveForm::begin([
                'validateOnBlur' => FALSE,
                
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->custombutton('CANCEL', ['/tankermovement/tbl-vehicle-trip/index']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>