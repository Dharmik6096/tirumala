<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Payment Detail');
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
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'payment_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'customer_type',
                                'value' => Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'customer_code',
                                'label' => Yii::t('app', 'Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'customer_ex_code',
                                'label' => Yii::t('app', 'Code Ex.'),
                                'value' => Yii::$app->general->getCustomer($model, $model->customer_type, TRUE),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'customer_name',
                                'label' => Yii::t('app', 'Name'),
                                'value' => Yii::$app->general->getCustomer($model, $model->customer_type),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'payment_count',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'from_datetime',
                                'label' => Yii::t('app', 'Period'),
                                'value' => Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime),
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
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'addition',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'deduction',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'net_payable',
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
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Bill Head Detail') ?></h4>
            </div>
            <div class="form-grid">
                <?php
                $head_attribute = [
                        ['attribute' => 'bill_head_code', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
                        }
                    ],
                        ['attribute' => 'bill_head_type',
                        'value' => function($model) {
                            return isset($model->billHeadCode->bill_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->billHeadCode->bill_head_type] : 'N/A';
                        },],
                        ['attribute' => 'amount'],
                ];
                $head_grid_option = [
                    'id' => 'bill-head-summary-detail-list',
                    'attributes' => $head_attribute,
                    'active_column' => FALSE,
                    'default_sorting' => FALSE
                ];
                Yii::$app->grid->bind($headDataProvider, $searchModel, $head_grid_option, ['#'], FALSE);
                ?>
            </div> 
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Payment Details') ?></h4>
            </div>
            <div class="form-grid">
                <?php
               echo $this->render('payment_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => ''
                ]);
                ?>
            </div>
        </div> 
    </div>
</div>

<div id='bill_head_view'></div>
<?php
$script = " 


$(document).on('click','.view-head',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var member_code= $(this).attr('data-member_code');
    ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code);
});

function ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/member-bill-head']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code},
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

$this->registerJs($script, View::POS_END, 'member-payment-head-script');
?>