<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Member Payment Process : Step 2');
$bmc_info = Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . ' > ' .
        Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>     
        </div>


        <div class="panel-body">    
            <div class="grid-search large-search hidden-print">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'member-wise-payment-summary-form',
                            'validateOnBlur' => false,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'action' => Url::to(['list-member-payment'])
                ]);
                ?>
                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($model, 'union_code'); ?>
                <?= Html::activeHiddenInput($model, 'plant_code'); ?>
                <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
                <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']); ?>
                <?php
                $attribute = [
                        ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                        'checkboxOptions' => function($model) {
                            return ['value' => $model['dcs_code']];
                        }],
                        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
                        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                        }],
                        ['attribute' => 'dcs_code', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                        }],
                        ['attribute' => 'member_count'],
                        ['attribute' => 'kg_fat'],
                        ['attribute' => 'kg_snf'],
                        ['attribute' => 'qty', 'pageSummary' => true],
                        ['attribute' => 'total_amount', 'value' => 'total_amount',
                        'pageSummary' => true
                    ],
                        ['attribute' => 'addition', 'value' => 'addition',
                        'pageSummary' => true
                    ],
                        ['attribute' => 'total_deduction', 'value' => 'total_deduction',
                        'pageSummary' => true
                    ],
                        ['attribute' => 'previous_hold', 'pageSummary' => true
                    ],
                        ['attribute' => 'previous_due', 'pageSummary' => true
                    ],
                        ['attribute' => 'final_amount',
                        'pageSummary' => true,
                        'value' => function ($model) {
                            $addition = !empty($model->addition) ? $model->addition : 0;
                            $deduction = !empty($model->total_deduction) ? $model->total_deduction : 0;
                            return $model->net_payable + $addition - $deduction;
                        },
                        'contentOptions' => ['class' => 'final-amount'],
                    ],
                ];

                $grid_option = [
                    'id' => 'confirm-society',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                        //'actions' => []
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false);
                ?>
                <div class="col-md-12" >
                    <?php if (!empty($dataProvider->getModels())) { ?>
                        <?php foreach ($dataProvider->getModels() as $data) { ?>
                            <?= Html::activeHiddenInput($model, 'dcs_code[]', ['value' => $data['dcs_code']]); ?>
                        <?php } ?>
                        <?= Html::button(Yii::t('app', 'Adjust'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
                        <?= Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
                        <?php // Yii::$app->controls->save('Next', $model); ?>
                    <?php } ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?>        
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>
<?php
$script = "
$('.kv-panel-before').hide(); 
$('#adjust').click(function() {
    $('.process_lock_flag').val('Process');
    var checkBoxCount = $('.kv-row-checkbox:checked').length;
    if(checkBoxCount > 0) {
        $('#flag').val($(this).prop('name'));
        $('#member-wise-payment-summary-form').submit();
//        $('form#w1').submit();
    } else {
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
    }
});
$('#adjust-lock').click(function() {
    $('.process_lock_flag').val('Lock');
    $('#member-wise-payment-summary-form').submit();
//    $('form#w1').submit();
});";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>