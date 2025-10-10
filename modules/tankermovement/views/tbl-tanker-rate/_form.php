<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;

$button = Yii::$app->label->button($type);
$model->union_code = !empty($selected) ? $selected : $model->union_code;
?>

<?php
$form = ActiveForm::begin(['id' => 'purchase-rate-form',
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>           
    <div class="col-sm-2 change">
        <?php
            echo Yii::$app->dropdown->dropdownStatic('tanker_rate_gen_method', $model, $form,'', $model->getAttributeLabel('rate_gen_method_code')); 
        ?>
    </div>
    <div class="col-sm-2 change">
        <?php
         echo Yii::$app->dropdown->dropdownStatic('tanker_rate_for', $model, $form,'', $model->getAttributeLabel('rate_for')); 
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'form-group padding-right-5 col-sm-12 shift', 'Shift', false, 'shift_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textArea(['rows' => 2]) ?>
    </div>
    <?= Html::hiddenInput('file_name', '', ['id' => 'file_name']); ?>

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Next'),
                'id' => 'submit',
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){  
                        
                           var tx=($('#tbltankerrate-rate_gen_method_code option:selected').text()).toLowerCase();                                
                           if(tx=='import' && ($('#file_name').val())==''){                         
                               $('#excelImport').modal('toggle');                                                          
                               return false;
                           }
                           $('#loadercontent').show();
                           $('#pageloader').show();
                    }"),
                    'success' => new JsExpression('function(data){
                                    if (data.status == "success"){
                                            var purchaseRate = [];
                                            purchaseRate = {"originating_org_type":data.originating_org_type,"originating_org_code":data.originating_org_code,"union_code":data.union_code,"rate_method":data.rate_method,"rate_for":data.rate_for,"wef_date":data.wef_date,"description":data.description,"shift_code":data.shift_code};
                                            localStorage.setItem("purchaseRate", JSON.stringify(purchaseRate));
                                            window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                    }else{
                                $(\'#file_name\').val(\'\');
                                     $(\'#loadercontent\').hide();
                                     $(\'#pageloader\').hide();
                                       $("div.help-block").remove();
                                        var cnt=0;
                                        $.each(data, function(key, val) {
                                            $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                            $("#"+key).closest(".form-group").addClass("has-error");
                                             if(key=="tbltankerrate-originating_org_code"){
                                                cnt++;
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+val+"</span></div></div>",function(){ location.reload() });
                                            }
                                        });
                                        if(cnt==0 && typeof data.message != "undefined")                                           
                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+data.message+"</span></div></div>",function(){ location.reload() });
                                            
                                    }
                     }'),
                    'error' => new JsExpression('function(){
                            $("#importModal").modal("toggle");
//                             $("#import-form")[0].reset();
                                     bootbox.alert("You have error in your file");
                     }'),
                ],
                'options' => ['class' => 'btn btn-primary',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?= $this->render('_excel_popup') ?>
<?php
$script = "
    $( document ).ready(function() {
           $('.shift select option:contains(\'All\')').remove();
    });
";

$this->registerJs($script, View::POS_END, 'shift');
?>