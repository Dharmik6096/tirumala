<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>
<?php
$form = ActiveForm::begin([
            'method' => 'post'
        ]);
?>
<?= Html::activeHiddenInput($model, 'union_code'); ?>
<?= Html::activeHiddenInput($model, 'month'); ?>
<?php if (!empty($dataProvider->getModels())) { ?>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'disbursement_date', 'form-group col-sm-3', true, '', false); ?>
    </div>
    <div class="">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Disburse'),
            'id' => 'disburse',
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create', 'type' => 'disburse']),
                'beforeSend' => new JsExpression("function(data){
                                var month = $('#tblstaffsalaryprocess-month').val();
                                var date = $('#tblstaffsalaryprocess-disbursement_date').val();
                                var process = $('#tblstaffsalaryprocess-salary').val();
                                if(month == '' || process == '') {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Disburse Not Allow before Process.</span></div></div>');
                                    return false;                        
                                }
                                if(date == '') {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Enter Disburse Date.</span></div></div>');
                                    return false;                        
                                }
                                var data_ok=0;
                                 $('.net-amount').each(function() {
                                    var netamount =  parseFloat($(this).val());
                                    if(netamount<0){
                                      data_ok=1; 
                                    }
                                 });
                                if(data_ok==1){
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Net Payable should not be less than 0.</span></div></div>');
                                    return false;   
                                }
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $(".error-summary").hide();
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    reloadGrid();
                                                                     $("#tblstaffsalaryprocess-salary").val("");
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }else{
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    var message = "";
                                                                    $.each(data, function(key, val) {
//                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        message = message + val + "\r\n";
                                                                    });
//                                                                    $(".error-summary").show();
                                                                    
                                                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+message+"</span></div></div>");

                                                                }
                                                 }'),
            ],
            'options' => ['class' => 'btn btn-default btn-raised mt20',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>

    <?php } ?>
    <div>
        <?php
        $attribute = [
            ['attribute' => 'staff_member_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
                }, 'filter' => FALSE],
            [
                'attribute' => 'disbursement_date',
                'width' => '200px',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->disbursement_date);
                }, 'filter' => FALSE],
            ['attribute' => 'Status', 'value' => function($model) {
                    return empty($model->disbursement_date) ? 'Process' : 'Disburse';
                }, 'filter' => FALSE],
            ['attribute' => 'month',
                'value' => function($model) {
                    return date('m/Y', strtotime($model->month));
                }, 'filter' => FALSE
            ],
            ['attribute' => 'value',
                'value' => function($model) {
                    return Yii::$app->general->decimalformat($model->value);
                }, 'filter' => FALSE, 'contentOptions' => ['class' => 'final-amount'],
            ],
            ['attribute' => 'previous_hold', 'value' => function($model) {
                    return empty(Yii::$app->general->getforeignkey($model->holdDue, 'hold_amount')) ? '0.00' : Yii::$app->general->getforeignkey($model->holdDue, 'hold_amount');
                }, 'pageSummary' => true, 'filter' => FALSE, 'contentOptions' => ['class' => 'previous-hold']],
            ['attribute' => 'previous_due', 'value' => function($model) {
                    return empty(Yii::$app->general->getforeignkey($model->holdDue, 'due_amount')) ? '0.00' : Yii::$app->general->getforeignkey($model->holdDue, 'due_amount');
                }, 'pageSummary' => true, 'filter' => FALSE, 'contentOptions' => ['class' => 'previous-due']],
            ['attribute' => 'hold_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'staff_member_code[' . $index . ']', ['value' => $model->staff_member_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                }, 'filter' => FALSE
            ],
            ['attribute' => 'additional_pay',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'staff_member_code[' . $index . ']', ['value' => $model->staff_member_code]) . $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $model->additional_pay, 'class' => 'number-validate adjust-amount cal-amount form-control',])->label(FALSE);
                }, 'filter' => FALSE
            ],
            ['attribute' => 'net_payable',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'net_payable[' . $index . ']')->textInput(['class' => 'number-validate net-amount form-control', "disabled" => TRUE, 'value' => $model->net_payable])->label(FALSE);
                },
            ],
            ['attribute' => 'effective_working_days', 'filter' => FALSE],
            ['attribute' => 'lwp', 'filter' => FALSE],
            ['attribute' => 'bank_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
                }, 'filter' => FALSE],
            ['attribute' => 'branch_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
                }, 'filter' => FALSE],
            ['attribute' => 'designation_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
                }, 'filter' => FALSE],
        ];
        $grid_option = [
            'id' => 'salary-process-inner-list',
            'attributes' => $attribute,
            'active_column' => false,
            'actions' => [
                'view' => TRUE
            ]
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
