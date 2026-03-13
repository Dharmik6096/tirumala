<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Voucher Detail View';
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                // DetailView Attributes Configuration
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Ref. Code'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Society Name'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'voucher_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->voucherTypeCode, 'voucher_type_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'dock_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'voucher_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'voucher_date',
                                'value' => Yii::$app->controls->view_date($model->voucher_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'financial_year_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'bill_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'bill_date',
                                'value' => Yii::$app->controls->view_date($model->bill_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'auto_posted',
                                'value' => !empty(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->auto_posted]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->auto_posted] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'cancelled',
                                'value' => !empty(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->cancelled]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->cancelled] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
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

        <div class="col-md-6 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Credit Voucher Transactions</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('../tbl-voucher-transaction/_form_grid', [
                    'model' => $model,
                    'dataProvider' => $creditDataProvider,
                    'searchModel' => $creditSearchModel,
                    'grid_id' => 'credit-voucher-transaction-list',
                ])
                ?>
            </div>
        </div>
        <div class="col-md-6 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Debit Voucher Transactions</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('../tbl-voucher-transaction/_form_grid', [
                    'model' => $model,
                    'dataProvider' => $debitDataProvider,
                    'searchModel' => $debitSearchModel,
                ])
                ?>
            </div>
        </div>

        <div class="col-sm-12 transaction_section">
            <div class="modal modal-default fade" id="voucher-subledger-modal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Voucher Sub Ledger</h4>
                        </div>
                        <div class="modal-body">
                            <div id="voucher-subledger-modal-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$script = "$(document).ready(function(){
    $(document).on('click','.view-sub-ledger',function(e){
        e.preventDefault();
        var id= $(this).attr('data-val');
        ViewSubLedger(id);
    });
    function ViewSubLedger(code){
        if(code != ''){         
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/dcsaccounting/tbl-voucher/view-sub-ledger']) . "',
                data: {'code' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#voucher-subledger-modal-content').html(data);
                  $('#voucher-subledger-modal').modal('show');
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

$this->registerJs($script, View::POS_END, 'view-voucher-sub-ledger');
?>