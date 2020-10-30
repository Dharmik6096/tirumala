<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$button = Yii::$app->label->button($type);
$model->union_code = !empty($selected) ? $selected : $model->union_code;
?>

<?php
$form = ActiveForm::begin(['id' => 'purchase-rate-form',
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>           
    <div class="col-sm-3 change">
        <?= Yii::$app->dropdown->dropdown('rate_gen_method_code', $model, $form, '', 'Rate Method'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-3 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'form-group padding-right-5 col-sm-12 shift', 'Shift', false, 'shift_id'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', 'Shift Applicability'); ?>
    </div>
    <div class="col-sm-3  mt25">
        <?= $form->field($model, 'for_rmrd', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'description')->textArea(['rows' => 2]) ?>
    </div>
    <?= Html::hiddenInput('file_name', '', ['id' => 'file_name']); ?>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([

                'label' => Yii::t('app', 'Next'),
                'id' => 'submit',
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){  
                        
                           var tx=($('#tblpurchaserate-rate_gen_method_code option:selected').text()).toLowerCase();                                
                           if(tx=='excel' && ($('#file_name').val())==''){                         
                               $('#excelImport').modal('toggle');                                                          
                               return false;
                           }
                           $('#loadercontent').show();
                           $('#pageloader').show();
                    }"),
                    'success' => new JsExpression('function(data){  
                            //console.log(data);
                                    if (data.status == "success"){
                                            var purchaseRate = [];
                                            purchaseRate = {"originating_org_type":data.originating_org_type,"originating_org_code":data.originating_org_code,"union_code":data.union_code,"rate_method":data.rate_method,"wef_date":data.wef_date,"shift":data.shift,"description":data.description,"shift_id":data.shift_id,"for_rmrd":data.for_rmrd};
                                            localStorage.setItem("purchaseRate", JSON.stringify(purchaseRate));
                                            window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                    }else{
                                     $(\'#loadercontent\').hide();
                                     $(\'#pageloader\').hide();
                                        $("div.help-block").remove();
                                        $.each(data, function(key, val) {
                                            $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                            $("#"+key).closest(".form-group").addClass("has-error");
                                             if(key=="tblpurchaserate-originating_org_code" || key=="message"){
                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+val+"</span></div></div>",function(){ location.reload() });
                                                     
                                            }
                                        });
                                    }
                     }'),
                    'error' => new JsExpression('function(){
                            $("#importModal").modal("toggle");
                             $("#import-form")[0].reset();
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
        localStorage.removeItem('purchaseRate');
           $('.shift select option:contains(\'All\')').remove();
    });
";
/* $script = "
  $('#tblpurchaserate-rate_gen_method_code').on('change',function(){
  var tx=($('#tblpurchaserate-rate_gen_method_code option:selected').text()).toLowerCase();

  var rateType = ($('#tblpurchaserate-rate_type option:selected').text()).toLowerCase();

  if(rateType==''){
  bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select Rate type.</span></div></div>');
  return false;
  }
  if(tx=='excel'){
  $('#excelImport').modal('toggle');
  $('#rate_type').val(rateType);
  }
  });
  "; */
$this->registerJs($script, View::POS_END, 'village-code');
?>