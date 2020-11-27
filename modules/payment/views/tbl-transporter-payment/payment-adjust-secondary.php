<?php

use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use kartik\detail\DetailView;
use yii\web\View;

$this->title = 'Transporter Payment Process : Step 2';
$net_amount = $model->net_amount;
$model->final_pay = $model->final_amount;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <div class="table-responsive">
                <?php
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'transporter_type',
                                'value' => isset($model->transporter_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->transporter_type] : 'N/A',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'transporter_code',
                                'value' => $model->transporterCode->transporter_name,
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'vendor_code',
                                'value' => $model->transporterCode->vendor_code,
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'label' => Yii::t('app', 'Period'),
                                'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'net_amount',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'final_amount',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'total_amount',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'total_addition',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'total_deduction',
                                'valueColOptions' => ['style' => 'width:15%']
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div id="gridcontentvehicle" class='hide-grid-settings'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Date wise Payment Details') ?></h5>
            <?php
            $attribute = [
                [ 'attribute' => 'dispatch_date',
                    'filterType' => GridView::FILTER_DATE,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                            'autoclose' => true]
                    ],
                    'value' => function($model) {
                return Yii::$app->controls->view_date($model->dispatch_date);
            }, 'filter' => false],
                [ 'attribute' => 'parsing_no', 'filter' => false],
                ['attribute' => 'from_dest', 'value' => function($model) {
                        $rel = Yii::$app->general->getDestRelation($model->from_type);
                        $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'vendor' ? 'customer_name' : 'name');
                        if (!empty($rel))
                            return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
                    }, 'filter' => false],
                ['attribute' => 'to_dest', 'value' => function($model) {
                        $rel = Yii::$app->general->getDestRelation($model->to_type);
                        $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'vendor' ? 'customer_name' : 'name');
                        ;
                        if (!empty($rel))
                            return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
                    }, 'filter' => false],
                [ 'attribute' => 'qty', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'total_kms', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'extra_kms', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'km_rate', 'filter' => false],
                [ 'attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'toll_amount', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'fastag_amount', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'weighing_cost', 'filter' => false, 'pageSummary' => true],
                [ 'attribute' => 'total_amount', 'filter' => false, 'pageSummary' => true],
            ];


            $grid_option = [
                'id' => 'tpt-payment-detail',
                'attributes' => $attribute,
                'active_column' => FALSE,
                'showPageSummary' => true,
            ];
            Yii::$app->grid->bind($vehicleDetail, $searchModel, $grid_option);
            ?>
        </div>
        <div id="gridcontenthead" class='hide-grid-settings'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Payment Head Details') ?></h5>
            <?php
            $attribute = [
                [ 'attribute' => 'transporter_payment_head_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->paymentHeadCode, 'transporter_payment_head');
                    }, 'filter' => false],
                ['attribute' => 'type', 'value' => function($model) {
                        return isset($model->type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] : '';
                    }, 'filter' => false],
                [ 'attribute' => 'amount', 'filter' => false],
            ];


            $grid_option = [
                'id' => 'tpt-payment-head-detail',
                'attributes' => $attribute,
                'active_column' => FALSE,
            ];
            Yii::$app->grid->bind($headDetail, $searchModelHead, $grid_option);
            ?>
        </div>
        <hr/>
        <div id="adjustamount">
            <?php
            $form = ActiveForm::begin([
                        'validateOnBlur' => false,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <div class="row">
                <div class="col-sm-2">
                    <div class="col-sm-12">
                        <?= $form->field($model, 'adjust_amount')->textInput(['class' => 'form-control number-validate-negative']) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'final_pay')->textInput(['disabled' => TRUE]) ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'adjust_remark')->textarea() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?= Yii::$app->controls->save('CONFIRM', $model); ?>  
                        <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = " $('#tbltransporterpayment-adjust_amount').on('change',function(){            
        var net = " . $net_amount . ";
        var adjust = $('#tbltransporterpayment-adjust_amount').val();
         adjust = parseFloat(adjust);
         net = parseFloat(net);
        var final_pay =  net + adjust;
       $('#tbltransporterpayment-final_pay').val(parseFloat(final_pay));
       });";



$this->registerJs($script, View::POS_END, 'payment-adjust-script');
?>
