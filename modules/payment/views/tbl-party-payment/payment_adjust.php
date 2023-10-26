<?php

use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use kartik\detail\DetailView;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Party Payment Process : Step 2';
$net_amount = $model->net_amount;
//$model->final_pay = $model->final_amount;
$party_info = Yii::$app->general->getforeignkey($model->partyMaster, 'party_name') . ' > ' .
        (Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date) );
$message = Yii::t('app', 'Payment data will be Locked for (' . $party_info . '). Are you sure ?');
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
                                'attribute' => 'payment_type',
                                'value' => isset($model->payment_type) ? Yii::$app->dropdown->getRecords('party_payment_type')['data'][strtolower($model->payment_type)] : 'N/A',
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                             [
                                'attribute' => 'party_master_code',
                                'value' => $model->partyMaster['party_master_code'],
                                'valueColOptions' => ['style' => 'width:15%']
                            ],
                            [
                                'attribute' => 'party_master_name',
                                'value' => $model->partyMaster['party_name'],
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
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div id="gridcontentvehicle" class='hide-grid-settings not_ellipsis'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Date wise Payment Details') ?></h5>
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
                ['attribute' => 'rec_kg_snf', 'label' => strtolower($model->payment_type) == 'sale' ? Yii::t('app', 'Rec KG SNF') : Yii::t('app', 'Kg SNF'), 'filter' => false, 'pageSummary' => true ],
                ['attribute' => 'amount', 'filter' => false, 'pageSummary' => true],['attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
            ];
            $grid_option = [
                'id' => 'tpt-payment-detail',
                'attributes' => $attribute,
                'active_column' => FALSE,
                'showPageSummary' => true,
            ];
            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
        <div id="gridcontenthead" class='hide-grid-settings'>
            <h5 class="panel-heading"><?= Yii::t('app', 'Payment Head Details') ?></h5>
            <?php
            $attribute = [
                ['attribute' => 'party_payment_head_code', 'value' => function ($model) {
                        return Yii::$app->general->getforeignkey($model->paymentHeadCode, 'payment_head_name');
                    }, 'filter' => false, 'label' => Yii::t('app', 'Payment Head Name')],
                ['attribute' => 'type', 'value' => function ($model) {
                        return isset($model->payment_head_type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->payment_head_type] : '';
                    }, 'filter' => false],
                ['attribute' => 'amount', 'filter' => false],
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
                        'id' => 'party-payment-adjust',
                        'validateOnBlur' => TRUE,
                        'validateOnChange' => TRUE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <?= Html::hiddenInput('process_lock_flag', 'processed', ['class' => 'process_lock_flag']); ?>
            <?= Html::activeHiddenInput($model, 'from_date'); ?>
            <?= Html::activeHiddenInput($model, 'to_date'); ?>
            <?= Html::activeHiddenInput($model, 'union_code'); ?>
            <?= Html::activeHiddenInput($model, 'payment_type'); ?>
            <?= Html::activeHiddenInput($model, 'party_master_code'); ?>
            <div class="row">
                <div class="col-sm-2">
                    <div class="col-sm-12">
                        <?= $form->field($model, 'adjust_amount')->textInput(['class' => 'form-control number-validate-negative']) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'final_amount')->textInput(['disabled' => TRUE, 'class' => 'form-control final_amount_cal']) ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'adjust_remark')->textarea() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?php
                        if (!empty($dataProvider->getModels())) {
                            echo Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'adjust']);
                            echo Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock-dcs-data']);
                        }
                        ?>


                        <?php //Yii::$app->controls->save('CONFIRM', $model); ?>  
                        <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = "
    $('.kv-panel-before').hide();
    $('#tblpartypayment-adjust_amount').on('change',function(){
        var net = " . $net_amount . ";
        var adjust = $('#tblpartypayment-adjust_amount').val();
        adjust = parseFloat(adjust);
        net = parseFloat(net);
        var final_amount =  net + adjust;
       $('#tblpartypayment-final_amount').val(parseFloat(final_amount));
       $('.final_amount_cal').val(parseFloat(final_amount));
    });
    $('#adjust-lock-dcs-data').click(function() {
        $('.process_lock_flag').val('locked');
        var message = '" . $message . "';
         bootbox.confirm({
            message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
            buttons: {
                confirm: {
                    label: '" . Yii::t('app', 'Yes') . " ',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '" . Yii::t('app', 'No') . "' ,
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){                           
                         $('#loadercontent').show();
                         $('#pageloader').show();
                         postProcessData(); 
                }
            }
        });                          
    });
    $('#adjust').click(function() {
        $('.process_lock_flag').val('processed');
        $('#loadercontent').show();
        $('#pageloader').show();            
        postProcessData();               

    });
    function postProcessData(){
        var postProcessData = $('#party-payment-adjust').serializeArray();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['payment-adjust']) . "',
            data: postProcessData,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {  
                    window.location=data.url;
                } else {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+data.msg+\"</span></div></div>\");
                }
            },
            error:function(data){
                $('#loadercontent').hide();
                $('#pageloader').hide();
                return false;
            }
        });
        return false; 
    }
";
$this->registerJs($script, View::POS_END, 'party-payment-adjust-script');
?>
