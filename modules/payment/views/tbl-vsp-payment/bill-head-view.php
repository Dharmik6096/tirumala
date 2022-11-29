<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;

$society_name = !empty($dataProvider->getModels()) ?
        Yii::$app->general->getCustomer($searchModel->vspPaymentCode, $searchModel->vspPaymentCode->customer_type) . ' (' .
        Yii::$app->general->getCustomer($searchModel->vspPaymentCode, $searchModel->vspPaymentCode->customer_type, TRUE) . '-' .
        Yii::$app->general->getforeignkey($searchModel->vspPaymentCode->customerType, 'customer_desc') . ')' : '';
?>
<div class="modal modal-default fade" id="BillHeadModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Bill Head Detail of ') . $society_name ?></h4>
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
                                    'id' => 'vendor-installment-form',
                                ],
                                'action' => Url::to(['/payment/tbl-vsp-payment/bill-head', 'code' => $model->vsp_payment_code])
                    ]);
                    ?>
                    <div class="row">
                        <div class="grid-search no-effect" >

                            <?php
                            $attribute = [
                                    ['class' => 'kartik\grid\CheckboxColumn',
                                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                                    'checkboxOptions' => function($model) {
                                        return ['class' => 'checkbox-installment', 'checked' => !empty($model->installment_date) ? TRUE : FALSE, 'value' => $model->product_sale_installment_code];
                                    }],
                                    ['label' => 'Sale Date', 'attribute' => 'invoice_date',
                                    'value' => function($model) {
                                        return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->saleCode, 'invoice_date'));
                                    }, 'filter' => false],
                                    ['attribute' => 'main_amount', 'filter' => FALSE],
                                    ['attribute' => 'installment_amount', 'filter' => FALSE],
                            ];

                            $grid_options = [
                                'id' => 'vendor-installation-list',
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
                                echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary add-installment', 'id' => 'add-installment']);
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
$script = "$(document).on('click','#add-installment',function(e){
        $('#error-summary').hide();
        var paymentData = [];
            $('#vendor-installment-form .checkbox-installment').each(function () {
             if(this.checked){
                    paymentData.push($(this).val()); 
                }
            });
        $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/payment/tbl-vsp-payment/bill-head', 'code' => $model->vsp_payment_code]) . "',
                    data: 'paymentData='+paymentData,
                    success: function(data) {
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            location.reload();
                        }else{
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.msg+'</span>');
                        }
                    }
                });           
    })";
$this->registerJs($script, View::POS_END, 'vendor-installment-form-submit');
$script = "$('.kv-panel-before').hide();$('.filters').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
