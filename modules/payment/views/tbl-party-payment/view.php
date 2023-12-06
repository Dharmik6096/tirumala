<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Party Payment');
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
                                'attribute' => 'party_master_code',
                                'value' => $model->partyMaster['party_master_code'],
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'payment_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'label' => Yii::t('app', 'Period'),
                                'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'payment_date',
                                'value' => Yii::$app->controls->view_date($model->payment_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],

                    [
                        'columns' => [                                               
                            [
                                'attribute' => 'net_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                              [
                                'attribute' => 'adjust_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ], 
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'final_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'status',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];

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

        <div id="gridcontentvehicle" class='hide-grid-settings not_ellipsis'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Payment Details') ?></h5>
            <?php
            $attribute = [
                ['attribute' => 'dispatch_datetime',
                    'value' => function ($model) {
                        return Yii::$app->controls->view_date($model->dispatch_datetime);
                    }, 'filter' => false, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'receipt_datetime',
                    'value' => function ($model) {
                        return Yii::$app->controls->view_date($model->receipt_datetime);
                    }, 'filter' => false],
                ['attribute' => 'challan_no', 'filter' => false, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'parsing_no', 'filter' => false, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'from_dest', 'value' => function ($model) {

                        $rel = Yii::$app->general->getDestRelation($model->from_type);
                        $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'party' ? 'party_name' : 'name');
                        if (!empty($rel))
                            return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
                    }, 'filter' => false],
                ['attribute' => 'to_dest', 'value' => function ($model) {
                        $rel = Yii::$app->general->getDestRelation($model->to_type);
                        $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'party' ? 'party_name' : 'name');
                        ;
                        if (!empty($rel))
                            return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
                    }, 'filter' => false],
                ['attribute' => 'disp_qty', 'label' => Yii::t('app', 'Disp Qty'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'disp_kg_fat', 'label' => Yii::t('app', 'Disp Kg FAT'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'disp_kg_snf', 'label' => Yii::t('app', 'Disp Kg SNF'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'rec_qty', 'label' => strtolower($model->payment_type) == 'sale' ? Yii::t('app', 'Rec Qty') : Yii::t('app', 'Purchase Qty'), 'filter' => false, 'pageSummary' => true],
                //   ['attribute' => 'qty', 'label' => Yii::t('app', 'Purchase Qty'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) != 'sale'],
                ['attribute' => 'rec_kg_fat', 'label' => strtolower($model->payment_type) == 'sale' ? Yii::t('app', 'Rec Kg FAT') : Yii::t('app', 'Kg FAT'), 'filter' => false, 'pageSummary' => true],
                ['attribute' =>  'rec_kg_snf', 'label' => strtolower($model->payment_type) == 'sale' ? Yii::t('app', 'Rec KG SNF') : Yii::t('app', 'Kg SNF'), 'filter' => false, 'pageSummary' => true],
                ['attribute' => 'rd_qty_diff', 'label' => Yii::t('app', 'Qty Diff.'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'rd_kg_fat_diff', 'label' => Yii::t('app', 'Kg FAT diff.'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                ['attribute' => 'rd_kg_snf_diff', 'label' => Yii::t('app', 'Kg SNF Diff'), 'filter' => false, 'pageSummary' => true, 'visible' => strtolower($model->payment_type) == 'sale'],
                
                ['attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
            ];
            $grid_option = [
                'id' => 'tpt-payment-detail',
                'attributes' => $attribute,
                'active_column' => FALSE,
                'showPageSummary' => true,
            ];
            Yii::$app->grid->bind($detailDataProvider, $searchModel, $grid_option);
            ?>
        </div>
        <div id="gridcontenthead" class='hide-grid-settings'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Payment Head Details') ?></h5>
            <?php
            $head_attribute = [
                ['attribute' => 'payment_head_code', 'value' => function ($model) {
                        return Yii::$app->general->getforeignkey($model->paymentHeadCode, 'payment_head_name');
                    }
                ],
                ['attribute' => 'payment_head_type',
                    'value' => function ($model) {
                        return isset($model->paymentHeadCode->payment_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->paymentHeadCode->payment_head_type] : 'N/A';
                    },],
                ['attribute' => 'amount'],
            ];
            $head_grid_option = [
                'id' => 'party-detail-bill-head-summary',
                'attributes' => $head_attribute,
                'active_column' => FALSE,
                'default_sorting' => FALSE
            ];
            Yii::$app->grid->bind($headDataProvider, $searchModel, $head_grid_option, ['#'], FALSE);
            ?>
        </div> 

        <div id='bill_head_view'></div>
        <?php
        $script = " 
$(document).on('click','.view-head',function(e){
    var party_payment_code= $(this).attr('data-party_payment_code');
    ViewBillHead(party_payment_code);
});

function ViewBillHead(party_payment_code){
    if(party_payment_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-party-payment/bill-head']) . "',
            data: {'party_payment_code' : party_payment_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#bill_head_view').html(data);
                $('#BillHeadModal').modal('toggle');              
                $('#loadercontent').hide();
                $('#pageloader').hide();                                                                  
            },
            error: function(data) {  
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    }
}";
        $this->registerJs($script, View::POS_END, 'party-payment-head-script');
        ?>