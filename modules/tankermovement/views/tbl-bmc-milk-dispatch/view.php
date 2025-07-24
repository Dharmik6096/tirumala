<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\web\View;

$this->title = Yii::$app->label->title('view', 'Tanker Milk Dispatch');
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
                $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
                $relSource = Yii::$app->general->getDestRelation($model->source_org_type);
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : (strtolower($model->destination_type) == 'party' ? 'party_name' : 'name'));
                $attSource = strtolower($sourceType) == 'bmc' ? 'bmc_name' : (strtolower($sourceType) == 'vendor' ? 'customer_name' : (strtolower($sourceType) == 'party' ? 'party_name' : 'name'));
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
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_org_type',
                                'label' => Yii::t('app', 'Source Type'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'source_org_code',
                                'label' => Yii::t('app', 'Source Name'),
                                'value' => !empty($rel) ? Yii::$app->general->getforeignkey($model->$relSource, $attSource) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_org_code',
                                'label' => Yii::t('app', 'Source Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => (Yii::t('app', 'Source Ref.Code')),
                                'value' => (strtolower($model->source_org_type) != 'party') ? Yii::$app->general->getforeignkey($model->$relSource, 'ref_code') : 'N/A',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'destination_type',
                                'label' => Yii::t('app', 'Dest. Type'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'destination_code',
                                'label' => Yii::t('app', 'Dest. Name'),
                                'value' => !empty($rel) ? Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) : $model->destination_type,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'destination_code',
                                'label' => Yii::t('app', 'Dest. Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => (Yii::t('app', 'Dest. Ref.Code')),
                                'value' => (strtolower($model->destination_type) != 'party') ? Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, 'ref_code') : 'N/A',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'value' => Yii::$app->controls->view_date($model->from_date) . '(' . Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift') . ')',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'to_date',
                                'value' => Yii::$app->controls->view_date($model->to_date) . '(' . Yii::$app->general->getforeignkey($model->toShiftCode, 'shift') . ')',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
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
                                'attribute' => 'challan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_last_destination',
                                'value' => $model->is_last_destination == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transporter_code',
                                'label' => Yii::t('app', 'Transporter'),
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
                                'attribute' => 'driver_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'driver_contact_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_in_time',
                                'value' => Yii::$app->controls->view_time($model->vehicle_in_time),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_out_time',
                                'value' => Yii::$app->controls->view_time($model->vehicle_out_time),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'gross_weight',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'tare_weight',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Channel'),
                                'value' => Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'tested_by',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'remarks',
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
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"></h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Dispatch Transactions Detail') ?></h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_transaction_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isVisible' => (strtolower($model->source_org_type == 'bmc')) ? true : false,
                    'txnEdit' => 'false',
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
    $script = "$(document).ready(function(){
    $(document).on('click','.view-config',function(e){
    var id= $(this).attr('data-val');
  ViewConfig(id);
    });
    function ViewConfig(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/tankermovement/tbl-bmc-milk-dispatch/view-config']) . "',
                data: {'id' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#config_detail_view').html(data);
                   $('#ConfigModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
});";
    $this->registerJs($script, View::POS_END, 'bmc-config-popup');
    ?>