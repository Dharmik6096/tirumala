<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Bonus Payment');
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
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"></h5></div> -->
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
                    'id' => 'bonus-detail-bill-head-summary',
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
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Payment Detail') ?></h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                        ['attribute' => 'customer_type', 'value' => function($model) {
                            return (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'));
                        }],
                        ['attribute' => 'customer_code'],
                        ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true);
                        }],
                        ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type, true);
                        }],
                        ['attribute' => 'customer_name'],
                        ['attribute' => 'kg_fat'],
                        ['attribute' => 'kg_snf'],
                        ['attribute' => 'qty'],
                        ['attribute' => 'amount'],
                        ['attribute' => 'addition'],
                        ['attribute' => 'deduction'],
                        ['attribute' => 'net_payable'],
                        ['attribute' => 'bank_name'],
                        ['attribute' => 'branch_name'],
                        ['attribute' => 'ifsc'],
                        ['attribute' => 'bank_account_no'],
                        ['attribute' => 'beneficiary_name'],
                ];

                $grid_option = [
                    'id' => 'bonus-detail-list-index',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'default_sorting' => FALSE,
                    'actions' => [
                        'member-bill-head' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-bonus_payment_code' => $model->bonus_payment_code];
                            return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-bonus-payment/bill-head', 'bonus_payment_code' => $model->bonus_payment_code], $options);
                        },
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
                ?>
            </div>
        </div> 
    </div>
</div>

<div id='bill_head_view'></div>
<?php
$script = " 
$(document).on('click','.view-head',function(e){
    var bonus_payment_code= $(this).attr('data-bonus_payment_code');
    ViewBillHead(bonus_payment_code);
});

function ViewBillHead(bonus_payment_code){
    if(bonus_payment_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-bonus-payment/bill-head']) . "',
            data: {'bonus_payment_code' : bonus_payment_code},
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
$this->registerJs($script, View::POS_END, 'bonus-payment-head-script');
?>