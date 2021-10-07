<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Member Payment Process : Step 3');
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'to_date'));

$bmc_info = Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' > ' .
        $fromDate . ' to ' . $toDate;
$message = Yii::t('app', 'Payment data of  all society will be locked and considered as final for ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$config = (isset(Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member']) && Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member'] == 1) ? TRUE : FALSE;

$urlForPost = ['member-payment-adjust', 'union_code' => $model->union_code, 'payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code];
?>
<?php
//$array = $dataProvider->getModels();
$array = $dataProvider; //->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'action' => $urlForPost
        ]);
        ?>
        <div class="table-responsive kv-grid-container">
            <table class="table table-bordered table-hover kv-grid-table kv-table-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= Yii::t('app', 'DCS Code') ?></th>
                        <th><?= Yii::t('app', 'Code Ex.') ?></th>
                        <th><?= Yii::t('app', 'DCS') ?></th>
                        <th><?= Yii::t('app', 'Member Code') ?></th>
                        <th><?= Yii::t('app', 'Member') ?></th>
                        <th><?= Yii::t('app', 'KgFAT') ?></th>
                        <th><?= Yii::t('app', 'KgSNF') ?></th>
                        <th><?= Yii::t('app', 'Total Qty') ?></th>
                        <th><?= Yii::t('app', 'Milk Amount(+)') ?></th>
                        <th><?= Yii::t('app', 'Addition(+)') ?></th>
                        <th><?= Yii::t('app', 'Deduction(-)') ?></th>
                        <th><?= Yii::t('app', 'Previous Hold(+)') ?></th>
                        <th><?= Yii::t('app', 'Previous Due(-)') ?></th>
                        <th><?= Yii::t('app', 'Final Pay') ?></th>
                        <th><?= Yii::t('app', 'Hold Amount(-)') ?></th>
                        <th><?= Yii::t('app', 'Additional Pay(+)') ?></th>
                        <th><?= Yii::t('app', 'Adjust Recovery') ?></th>
                        <th><?= Yii::t('app', 'Recovery') ?></th>
                        <th><?= Yii::t('app', 'Net Payable') ?></th>
                        <th><?= Yii::t('app', 'Remarks') ?></th>
                        <th><?= Yii::t('app', 'Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQty = 0;
                    $totalMilkAmt = 0;
                    $totalAddition = 0;
                    $totalDeduction = 0;
                    $totalPrevHold = 0;
                    $totalPrevDue = 0;
                    $totalFinalPay = 0;
                    ?>
                    <?php foreach ($dataProvider as $index => $model) { ?>
                        <?php
                        $dcs = $model->dcsCode;
                        $member = $model->memberCode;
                        $totalQty = $totalQty + $model->qty;
                        $totalMilkAmt = $totalMilkAmt + $model->total_amount;
                        $totalAddition = $totalAddition + $model->total_addition;
                        $totalDeduction = $totalDeduction + $model->total_deduction;
                        $totalPrevHold = $totalPrevHold + $model->previous_hold;
                        $totalPrevDue = $totalPrevDue + $model->previous_due;
                        $totalFinalPay = $totalFinalPay + $model->net_payable;
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $model->dcs_code ?></td>
                            <td><?= !empty($dcs) ? $dcs->dcs_code_ex : ''; ?></td>
                            <td><?= !empty($dcs) ? $dcs->dcs_name : ''; ?></td>
                            <td><?= substr($model->member_code, -4) ?></td>
                            <td><?= !empty($member) ? $member->member_name : ''; ?></td>
                            <td><?= $model->kg_fat ?></td>
                            <td><?= $model->kg_snf ?></td>
                            <td><?= $model->qty ?></td>
                            <td><?= $model->total_amount ?></td>
                            <td><?= $model->total_addition ?></td>
                            <td><?= $model->total_deduction ?></td>
                            <td><?= $model->previous_hold ?></td>
                            <td><?= $model->previous_due ?></td>
                            <td class='final-amount'><?= $model->net_payable ?></td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['class' => 'alis_code', 'value' => $model->member_payment_alias_code]);
                                echo $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                                ?>
                            </td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']);
                                echo $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $model->additional_pay, 'class' => 'adjust-amount form-control cal-amount number-validate',])->label(FALSE)
                                ?>
                            </td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo Html::activeHiddenInput($model, 'payment_cycle_code[' . $index . ']', ['class' => 'payment_cycle', 'value' => $model->payment_cycle_code]);
                                echo Html::activeHiddenInput($model, 'plant_code[' . $index . ']', ['class' => 'plant', 'value' => $model->plant_code]);
                                echo Html::activeHiddenInput($model, 'mcc_plant_code[' . $index . ']', ['class' => 'mcc', 'value' => $model->mcc_plant_code]);
                                echo Html::activeHiddenInput($model, 'bmc_code[' . $index . ']', ['class' => 'bmc', 'value' => $model->bmc_code]);
                                echo Html::activeHiddenInput($model, 'dcs_code[' . $index . ']', ['class' => 'dcs', 'value' => $model->dcs_code]);
                                echo Html::activeHiddenInput($model, 'member_code[' . $index . ']', ['class' => 'member', 'value' => $model->member_code]);
                                echo $form->field($model, 'adjust_recovery[' . $index . ']')->textInput(['class' => 'adjust-recovery form-control number-validate', 'value' => $model->adjust_recovery])->label(FALSE)
                                ?>
                            </td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo $form->field($model, 'recovery[' . $index . ']')->textInput(['class' => 'recovery form-control', "readOnly" => TRUE, 'value' => $model->recovery])->label(FALSE);
                                ?>
                            </td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo $form->field($model, 'final_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $model->final_amount])->label(FALSE);
                                ?>
                            </td>
                            <td class="no_padding_input hide_help_block">
                                <?php
                                echo $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $model->adjust_remark])->label(FALSE);
                                ?>
                            </td>
                            <td class="action-cell skip-export kv-align-center kv-align-middle">
                                <?php
                                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
                                echo GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/member-bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
                                ?>

                                <?php
                                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'memberinstallments', 'data-original-title' => 'Member Installment', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
                                echo GhostHtml::a_alert('<i class="fa fa-plus"></i>', ['/payment/tbl-member-payment/member-installment', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($dataProvider)) { ?>
                    <tbody class="kv-page-summary-container">
                        <tr class="kv-page-summary warning">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><?= $totalQty ?></td>
                            <td><?= $totalMilkAmt ?></td>
                            <td><?= $totalAddition ?></td>
                            <td><?= $totalDeduction ?></td>
                            <td><?= $totalPrevHold ?></td>
                            <td><?= $totalPrevDue ?></td>
                            <td><?= $totalFinalPay ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
//        $attribute = [
//                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
//                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
//                }],
//                ['attribute' => 'dcs_code', 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
//                }],
//                ['attribute' => 'member_code', 'value' => function($model) {
//                    return substr($model->member_code, -4);
//                }, 'label' => Yii::t('app', 'Member Code')],
//                ['attribute' => 'member_code', 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
//                }],
//                ['attribute' => 'kg_fat'],
//                ['attribute' => 'kg_snf'],
//                ['attribute' => 'qty', 'pageSummary' => true],
//                ['attribute' => 'total_amount', 'value' => 'total_amount', 'pageSummary' => true],
//                ['attribute' => 'total_addition', 'value' => 'total_addition', 'pageSummary' => true],
//                ['attribute' => 'total_deduction', 'value' => 'total_deduction', 'pageSummary' => true],
//                ['attribute' => 'previous_hold', 'pageSummary' => true],
//                ['attribute' => 'previous_due', 'pageSummary' => true],
//                ['attribute' => 'net_payable', 'pageSummary' => true, 'contentOptions' => ['class' => 'final-amount'],],
//                ['attribute' => 'hold_amount',
//                'format' => 'raw',
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                'value' => function ($model, $key, $index) use ($form) {
//                    return Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['class' => 'alis_code', 'value' => $model->member_payment_alias_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
//                },
//            ],
//                ['attribute' => 'additional_pay',
//                'format' => 'raw',
//                //  'pageSummary' => true,
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                'value' => function ($model, $key, $index) use ($form) {
//                    return Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']) . $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $model->additional_pay, 'class' => 'adjust-amount form-control cal-amount number-validate',])->label(FALSE);
//                },
//            ],
//                ['attribute' => 'adjust_recovery',
//                'format' => 'raw',
//                'visible' => $config,
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                //  'pageSummary' => true,
//                'value' => function ($model, $key, $index) use ($form) {
//                    return Html::activeHiddenInput($model, 'payment_cycle_code[' . $index . ']', ['class' => 'payment_cycle', 'value' => $model->payment_cycle_code]) . Html::activeHiddenInput($model, 'plant_code[' . $index . ']', ['class' => 'plant', 'value' => $model->plant_code]) . Html::activeHiddenInput($model, 'mcc_plant_code[' . $index . ']', ['class' => 'mcc', 'value' => $model->mcc_plant_code]) . Html::activeHiddenInput($model, 'bmc_code[' . $index . ']', ['class' => 'bmc', 'value' => $model->bmc_code]) . Html::activeHiddenInput($model, 'dcs_code[' . $index . ']', ['class' => 'dcs', 'value' => $model->dcs_code]) . Html::activeHiddenInput($model, 'member_code[' . $index . ']', ['class' => 'member', 'value' => $model->member_code]) . $form->field($model, 'adjust_recovery[' . $index . ']')->textInput(['class' => 'adjust-recovery form-control number-validate', 'value' => $model->adjust_recovery])->label(FALSE);
//                },
//            ],
//                ['attribute' => 'recovery',
//                'format' => 'raw',
//                'visible' => $config,
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                //  'pageSummary' => true,
//                'value' => function ($model, $key, $index) use ($form) {
//                    return $form->field($model, 'recovery[' . $index . ']')->textInput(['class' => 'recovery form-control', "readOnly" => TRUE, 'value' => $model->recovery])->label(FALSE);
//                },
//            ],
//                ['attribute' => 'final_amount',
//                'format' => 'raw',
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                //  'pageSummary' => true,
//                'value' => function ($model, $key, $index) use ($form) {
//                    return $form->field($model, 'final_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $model->final_amount])->label(FALSE);
//                },
//            ],
//                ['attribute' => 'adjust_remark',
//                'format' => 'raw',
//                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
//                'value' => function ($model, $key, $index) use ($form) {
//                    return $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $model->adjust_remark])->label(FALSE);
//                },
//            ],
//        ];
//
//        $grid_option = [
//            'id' => 'member-payment-adjust-grid',
//            'attributes' => $attribute,
//            'active_column' => false,
//            'showPageSummary' => true,
//            'actions' => [
//                'member-bill-head' => function ($url, $model) {
//                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
//                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/member-bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
//                },
//                'member-installment' => function ($url, $model) {
//                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'memberinstallments', 'data-original-title' => 'Member Installment', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
//                    return GhostHtml::a_alert('<i class="fa fa-plus"></i>', ['/payment/tbl-member-payment/member-installment', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
//                },
//            ]
//        ];
//
//        Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
        ?>
    </div>
    <div class="panel-footer" >
        <?php //Yii::$app->controls->save('Confirm', $model);                ?>
        <?= Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
        <?= Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?> 
    </div>
</div>
<?php ActiveForm::end(); ?>
<!--<div id='member_installment'></div>
<div id="recoverOtherMember"></div>-->



<?php
$script = " 
function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
   $('.hold-amount').each(function() {
      var hold =  parseFloat($(this).val());
  if(hold != '' &&  !isNaN(hold)){
          total = total - hold;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }     
function SumAmountold()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 } 
 $('.kv-panel-before').hide();
 

$('#loadercontent').hide();
$('#pageloader').hide();  
";
$this->registerJs($script, View::POS_END, 'payment-adjust-script-list');
?>