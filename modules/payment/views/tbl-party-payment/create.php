<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Party Payment Process : Step 1');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('party_master', $model, $form, '', 'Party'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdownStatic('party_payment_type', $model, $form, '', 'Payment Type'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'from_date'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'to_date'); ?>
            </div>
            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Next'),
                        'id' => 'party-payment',
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['create']),
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
                        'options' => ['class' => 'btn btn-primary',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
