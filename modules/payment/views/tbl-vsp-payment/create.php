<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = isset($title) ? $title : 'Vendor Payment Process : Step 1';
$post_url = isset($post_url) ? $post_url : Url::to(['create']);
$multiple =  Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') =='1' ? TRUE : FALSE;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    //'action' => ['list-payment'],
                    //'method' => 'GET',
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-12">
            <div class="col-sm-3" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code', TRUE,$multiple); ?>
                <?php
                $where = json_encode(['data_lock_bmc' => 1, 'billing_lock_bmc' => 0]);
                echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                echo Html::hiddenInput('member_billing_lock_check', 1, ['id' => 'member_billing_lock_check']);
                ?>
            </div>    
              <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code', TRUE,$multiple); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-bmc_code', 'customer_type', TRUE, FALSE); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-bmc_code,tblvsppayment-customer_type,applicable_for,data_lock_bmc,member_billing_lock_check', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code') . ' *', FALSE, FALSE); ?>
                </div>
           
            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Next'),
                        'id' => 'vendor-payment',
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => $post_url,
                            'beforeSend' => new JsExpression("function(data){  
                                        $('#loadercontent').show();
                                        $('#pageloader').show();
                                    }"),
                            'success' => new JsExpression('function(data){ 
                                                    $(".help-block").text("");
                                                    $(".form-group").removeClass("has-error");
                                                    if (data.status == "success"){
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        window.location=data.url;
                                                    } else if (data.status == "displayConfirmPopup"){
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        bootbox.confirm({
                                                            message: "<div class=\"bg-danger\"><i class=\"fa fa-question-circle\"></i></div><span>"+data.msg+"</span>",
                                                            buttons: {
                                                                confirm: {
                                                                    label: "' . Yii::t('app', 'Yes') . '",
                                                                    className: "btn-primary"
                                                                },
                                                                cancel: {
                                                                    label: "' . Yii::t('app', 'No') . '",
                                                                    className: "btn-danger"
                                                                }
                                                            },
                                                            callback: function (result) {
                                                                if(result){
                                                                    window.location=data.url_regenerate;
                                                                } else {
                                                                    window.location=data.url;
                                                                }
                                                            }
                                                        });
                                                    } else if (data.status == "displayPopup"){
                                                        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+data.msg+"</span>");
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                    } else {
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        var cnt=0;
                                                        $.each(data, function(key, val) {
                                                            var parent_div = $("#"+key).parent("div");
                                                            parent_div.find(".help-block").remove();
                                                            $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                            $("#"+key).closest(".form-group").addClass("has-error");                                          
                                                        });
                                                    }
                                     }'),
                        ],
                        'options' => ['class' => 'btn-login btn btn-primary',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
                </div>
            </div>            
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>