<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="modal modal-default fade" id="apply-formula" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Set Value For Formula'); ?></h4>
            </div>

            <div class="modal-body">         
                <div class="row">
                    <div class="col-sm-12 mb15" id="subtitle">

                        <?php
                        //$key = key($formulaArray);
                        //echo Html::radioList('formula', '', $formulaArray, ['class' => 'radio radio-list', 'itemOptions' => ['class' => ''],]);
                        ?>
                    </div>
                    <div id="div_formula" class="col-sm-10 mb15 form-group field ">
                        <?= Html::input('text', 'value', '', ['class' => 'form-control', 'id' => 'replace_value']) ?>

                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Ok'), ['class' => 'btn btn-primary', 'id' => 'formula-submit']); ?>

                <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>

        </div>
    </div>
</div>


<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'bill_head_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?php
        echo Yii::$app->dropdown->dropdownStatic('calc_type', $model, $form, 'form-group', $model->getAttributeLabel('bill_head_type'), false, 'bill_head_type', false);
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', $model->getAttributeLabel('milk_type_code'), FALSE, 'milk_type_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('mcc_general_formula_code', $model, $form, 'tblmccbillhead-union_code', '', $model->getAttributeLabel('general_formula_code')); ?>
        <?php // Yii::$app->dropdown->dropdown('general_formula_code', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('general_formula_code'), false, 'general_formula_code'); ?>
        <?= Html::activeHiddenInput($model, 'general_formula', ['id' => 'general_formula']); ?>
    </div>

    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'sequence_no')->textInput() ?>       
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_default'); ?>
    </div>   
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'has_slab', ['checkboxTemplate' => '<div class="checkbox">{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox(); ?>
    </div>
    <div class="col-sm-2" id="defaultbill">
        <?= Yii::$app->dropdown->dropdown('mcc_default_bill_head_code', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('default_bill_head_code'), false, 'default_bill_head_code'); ?>
    </div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<!--form ends-->

<?php
$script = "
    $(document).ready(function(){
       dispDefBillHead();
    });
    $('#tblmccbillhead-is_default').on('change',function(){
        dispDefBillHead();
    });
    
    function dispDefBillHead(){
        $('#defaultbill').hide();
        $('#isHold').show();
        $('#paymentCycleType').show();
        
        if($('#tblmccbillhead-is_default').is(':checked')){
          $('#defaultbill').show();
          $('#isHold').hide();
          $('#paymentCycleType').hide();  
          $('#tblmccbillhead-is_hold').val('');
          $('#tblmccbillhead-payment_cycle_type').val('');
        } else {
         $('#defaultbill').hide();
         $('#isHold').show();
         $('#paymentCycleType').show();
         $('#tblmccbillhead-default_bill_head_code').val('');         
        }
    }
        var formula_value='';
        var formula='';
        function removeError(){
            $('#div_formula').removeClass('has-error');
            $('#error_message').remove();
        }
        function addError(message){
            $('#div_formula').addClass('has-error');
            $('#replace_value').after('<p id=\"error_message\" class=\"help-block help-block-error\">'+message+'</p>');
        }
        $('#tblmccbillhead-general_formula_code').on('change',function(e){
            $('#general_formula').val('');
             if($(this).val()){
             var str=$(this).find(\"option:selected\").text();
             formula=str;
             $.ajax({
            type: 'post',
            url: '" . Url::to(['/vsp/tbl-mcc-bill-head/keyword']) . "',                    
            success: function(data) {
                var type = $.parseJSON(data).join('|')+'|-|\\\+|\\\*|/|\\\[|\\\(|\\\)';//              
                var re = new RegExp(type,\"g\");
                formula_value=formula.replace(re,'').replace(/\]/g,',').slice(0,-1);
                $('#subtitle').empty().append(\"<span>Add Coma(,) Seperated Value for [\"+formula_value+\"]</span>\");
                $('#replace_value').val('');
                $('#apply-formula').modal('toggle');
            },
        });
            }
         });
         
        $('#formula-submit').on('click',function(e){
            if($('#replace_value').val()==''){
                removeError();
                addError('Value can not be blank');
            }else{
                var tmp1=formula_value.split(',');
                var tmp2=$('#replace_value').val().split(',');             
                if(tmp1.length==tmp2.length){                    
                     $.each(tmp1, function (key, val) {
                        formula=formula.replace('['+val+']',tmp2[key]);
                     });
                    removeError();  
                    $('#general_formula').val(formula);
                    $('#apply-formula').modal('toggle');
                }else{
                    removeError();
                    addError('Value is not in proper Format');
                }
            }                          
         });       
";

$this->registerJs($script, View::POS_END, 'formula');
?>