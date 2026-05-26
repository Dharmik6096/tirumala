<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */

$this->title = Yii::$app->label->title('view', 'GRN');
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
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
                                'valueColOptions' => ['style' => 'width:80%']
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
                                'attribute' => 'vendor_master_code',
                                'label' => $batchNoWiseInventory == 1 ? 'Plant' : 'Vendor',
                                'value' => $batchNoWiseInventory == 1 ? Yii::$app->general->getforeignkey($model->plantCode, 'name') : Yii::$app->general->getforeignkey($model->vendorCode, 'vendor_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'grn_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'grn_date',
                                'format' => 'html',
                                'value' => date('d-m-Y', strtotime($model->grn_date)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'invoice_date',
                                'format' => 'html',
                                'value' => date('d-m-Y', strtotime($model->invoice_date)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'invoice_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_stock_posted',
                                'value' => Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_stock_posted'),
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
                <h4 class="theme-box-heading">Product Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_list_grid', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'view' => TRUE
                ])
                ?>
            </div> 
        </div>
        <?php
        if ($model->payment_mode == 1) {
            ?>
            <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                    <h4 class="theme-box-heading">Installment Details</h4>
                </div>
                <div class="form-grid">
                    <?=
                    $this->render('_installment_grid', [
                        'grnInstallmentdataProvider' => $grnInstallmentdataProvider,
                        'grnInstallmentSearchModel' => $grnInstallmentSearchModel,
                    ])
                    ?>
                </div> 
            </div>
            <?php
        }
        ?>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Document Upload</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_document_grid', [
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                ])
                ?>
            </div> 
        </div>
    </div>
</div>
<?php
$script = "

$(document).ready(function() {
    $('.btn-toolbar.kv-grid-toolbar').hide();
});

";

$this->registerJs($script, View::POS_END, 'grn_view');
?>