<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = 'Remuneration Payment Process : Step 1';
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
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblremunerationsummary-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblremunerationsummary-plant_code', 'mcc_plant_code', TRUE); ?>
            </div>      
            <div  id="single-bmc" class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblremunerationsummary-mcc_plant_code', 'bmc_code', 'BMC *'); ?>
            </div>
            <div id="multiple-bmc" class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblremunerationsummary-mcc_plant_code', 'p_bmc_code', 'BMC *', TRUE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'from_datetime', '', '', false, false); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'to_datetime', '', '', false, false); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'calculate_milk_recovey'); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'calculate_other_head'); ?>
            </div>

            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Next'),
                        'id' => 'remuneration-summary',
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
                    <?= Yii::$app->controls->custombutton('Cancel', 'tbl-vsp-payment/index','','btn-login'); ?> 
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
     $('#single-bmc').hide();
     $('#multiple-bmc').hide();  
$('#tblremunerationsummary-mcc_plant_code').on('change',function(){
     var mcc_plant_code= $(this).val();
     if(mcc_plant_code !='' && mcc_plant_code != null){
            $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-mcc-type']) . "',
            data: {'mcc_plant_code' : mcc_plant_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var multiple_bmc = data.multiple_bmc;
               if(multiple_bmc == '1'){
                 $('#single-bmc').hide();
                 $('#multiple-bmc').show();
               }else{
                 $('#multiple-bmc').hide();
                 $('#single-bmc').show();
               }   
            }
        });
      }
});
";
$this->registerJs($script, View::POS_END, 'check-mcc-type');
?>
