<?php

use demogorgorn\ajax\AjaxSubmitButton;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

$society_name = !empty($dataProvider->getModels()) ?
        ' of ' . Yii::$app->general->getforeignkey($searchModel->memberCode, 'member_name') . ' (' .
        $searchModel->member_code . ')' : '';
?>
<div class="modal modal-default fade" id="MemberInstallmentModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Installment') . $society_name ?></h4>
            </div>
            <div class="popup-header bg_white">
                <?php
                $attribute = [
                        ['attribute' => 'bill_head_code', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
                        }
                    ],
                        ['attribute' => 'bill_head_type',
                        'value' => function($model) {
                            return isset($model->billHeadCode->bill_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->billHeadCode->bill_head_type] : 'N/A';
                        },],
                        ['attribute' => 'amount'],
                        ['attribute' => 'is_hold',
                        'value' => function($model) {
                            return $model->is_hold == 1 ? 'Yes' : 'No';
                        },],
                ];
                $grid_option = [
                    'id' => 'bill-head-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'member-installment-form-aaa',
                                ],
                                'action' => Url::to(['/payment/tbl-member-payment/member-installment'])
                    ]);
                    ?>
                    <div class="row">
                        <div class="grid-search no-effect" >

                            <?php
                            $attribute = [
                                    ['class' => 'kartik\grid\CheckboxColumn',
                                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                                    'checkboxOptions' => function($model) use ($searchModel, $selectedCheckbox) {
                                        $checked = false;
                                        $dataVal = 0;
                                        if (in_array($model['product_sale_installment_code'], $selectedCheckbox)) {
                                            $checked = true;
                                            $dataVal = $model['installment_amount'];
                                        }
                                        return ['class' => 'checkbox-collection', 'checked' => $checked, 'value' => $model['product_sale_installment_code'] . '###' . $searchModel['payment_cycle_code'] . '###' . $model['bmc_code'] . '###' . $model['dcs_code'] . '###' . $model['customer_code'] . '###' . $model['main_amount'] . '###' . $model['installment_amount'], 'data-installment_amount' => $model['installment_amount'], 'data-existInstallment' => $dataVal];
                                    }],
                                    ['label' => 'Sale Date', 'attribute' => 'invoice_date',
                                    'filterType' => GridView::FILTER_DATE,
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                            'autoclose' => true]
                                    ],
                                    'value' => function($model) {
                                        return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->saleCode, 'invoice_date'));
                                    }, 'filter' => false],
                                    ['attribute' => 'main_amount', 'filter' => FALSE],
                                    ['attribute' => 'installment_amount', 'filter' => FALSE],
                            ];

                            $grid_options = [
                                'id' => 'installation-list-aaa',
                                'attributes' => $attribute,
                                'active_column' => false,
                                'showPageSummary' => false,
                            ];

                            Yii::$app->grid->bind($idataProvider, $instalSearch, $grid_options, ['#'], false);
                            ?>
                        </div>

                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            if (!empty($idataProvider->getModels())) {
                                echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'id' => 'delete']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
    var netPay ='$netPay';
         if(netPay == '' || isNaN(netPay)){
            netPay = 0;
        }
        $('#MemberInstallmentModal .kv-panel-before').hide();$('#MemberInstallmentModal .filters').hide();
        $(document).on('click','#delete',function(){
            var paymentData = [];
            var paymentAmount = 0;
            $('#member-installment-form-aaa .checkbox-collection').each(function () {
                if(this.checked){
                    paymentData.push($(this).val());
                    $(this).attr('data-installment_amount');
//                    $(this).attr('data-existInstallment');
                    paymentAmount= parseFloat(paymentAmount) + parseFloat($(this).attr('data-installment_amount')) - parseFloat($(this).attr('data-existInstallment'));
                }
            });
            var len = paymentData.length;
//            if(len == 0){
//                bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-danger\"><i class=\"fa fa-times\"></i></div><span>" . Yii::t("app", "Please select at least one Installment.") . "</span></div></div>');
//                return false;
//            } else 
         //   if(paymentAmount > netPay){
            if(false){
                bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-danger\"><i class=\"fa fa-times\"></i></div><span>" . Yii::t("app", "Installment Must Not More than NetPay.") . "</span></div></div>');
                return false;
            } else {
//            +'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code
                $.ajax({
                    type: 'post',
                    url: '" . Url::to($redirectUrl) . "',
                    data: 'paymentData='+paymentData+'&netPay='+netPay,
                    success: function(data) {
                         var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            location.reload();
                        }else{
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted..Please try again');
                    }
                });
//                $('#delete-milk-collection').submit();
            }
        });

";


//$("#delete").click(function() {
//        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
//            if(len == 0){
//             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
//                return false;
//            } else {
//            $("#delete-milk-collection").submit();
//            }
//         });
$this->registerJs($script, View::POS_END, 'panel-before-hide-asdasd');
?>
