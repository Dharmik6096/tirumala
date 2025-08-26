<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;
?>
<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'complain-escalation-form'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Complain Escalation</h4>
        </div>
        <div class="col-md-10 col-sm-10">
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'escalation_name')->textInput() ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'escalation_remarks')->textarea() ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Complain Escalation Transaction</h4>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdownStatic('login_type', $txnModel, $form, 'form-group', $txnModel->getAttributeLabel('user_type'), false, 'user_type'); ?>     
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('department', $txnModel, $form, 'form-group', $model->getAttributeLabel('department'), false, 'department'); ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($txnModel, 'escalation_time')->textInput() ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($txnModel, 'level')->textInput(['min' => 1]) ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main">
            <?=
            Html::a(Yii::t('app', 'Add'), 'javascript:void(0)', ['class' => 'btn btn-primary add-complain-record disabled no_pointer', 'id' => 'add_complain_escalation'])
            ?>
        </div>        
    </div>
</div>
<div class="col-sm-12">
    <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
        <thead>
            <tr>
                <th>User Type</th>
                <th>Department</th>
                <th>Escalation Time</th>
                <th>Level</th>
            </tr> 
        </thead>
        <tbody id="complain_escalation_list">

        </tbody>
    </table>
</div>
<div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Save'),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create']),
                'beforeSend' => new JsExpression("function(data){
                    $('#loadercontent').show();
                    $('#pageloader').show();
                }"),
                'success' => new JsExpression('function(data){
                        var data=$.parseJSON(data);
                        $(\'#loadercontent\').hide();
                        $(\'#pageloader\').hide();
                        if (data.status == "success"){ 
                            $(".help-block").text("");
                            $(".form-group").removeClass("has-error");         
                             $(".error-summary").hide();
                            $(".error-summary li").remove();
                             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                        }
                }'),
            ],
            'options' => ['class' => 'btn btn-default btn-save-txn disabled no_pointer',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>

    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
var tr_count = 0;
$('#tblcomplainescalation-escalation_name').on('change', function(){
    addBtnEnable();
});

$('#tblcomplainescalationtxn-user_type').on('change', function(){
    addBtnEnable();
});

$('#tblcomplainescalationtxn-escalation_time').on('keyup', function(){
    if($(this).val().match(/^0/)){
        $(this).val('');
        return false;
    }
    addBtnEnable();
});

$('#tblcomplainescalationtxn-level').on('keyup', function(){
    if($(this).val().match(/^0/)){
        $(this).val('');
        return false;
    }
    addBtnEnable();
});

function addBtnEnable(){
    var escalation_name = $('#tblcomplainescalation-escalation_name').val();
    var user_type = $('#tblcomplainescalationtxn-user_type').val();
    var escalation_time = $('#tblcomplainescalationtxn-escalation_time').val();    
    var level = $('#tblcomplainescalationtxn-level').val();

    if(escalation_name != '' && user_type != '' && escalation_time != '' && level != '') {
        $('.add-complain-record').removeClass('disabled no_pointer');
    } else {
        $('.add-complain-record').addClass('disabled no_pointer');
    }
}
 $('#add_complain_escalation').on('click', function(){
        var err = '';
 
        var escalation_name = $('#tblcomplainescalation-escalation_name').val();
        var user_type = $('#tblcomplainescalationtxn-user_type option:selected').val();
        var user_type_name = $('#tblcomplainescalationtxn-user_type option:selected').text();
        var department = $('#tblcomplainescalationtxn-department option:selected').val();
        var department_name = $('#tblcomplainescalationtxn-department option:selected').text();
        var escalation_time = $('#tblcomplainescalationtxn-escalation_time').val();
        var level = $('#tblcomplainescalationtxn-level').val();
        
        if(escalation_name == ''){
            err += '\\nEscalation Name can not be Blank.';
        }
    
        if(user_type == ''){
            err += '\\nUser Type can not be Blank.';
        }
        
        if(department == ''){
            err += '\\nDepartment can not be Blank.';
        }
        
        if(escalation_time == ''){
            err += '\\nEscalation Time can not be Blank.';
        }
        
        if(level == ''){
            err += '\\nLevel can not be Blank.';
        }
    
        if(err == ''){
            var tr_class;
            var level_tr_class;
            tr_class = user_type;
            level_tr_class = level;
            
            if($('.'+tr_class).length > 0){
                err += '\\nAlready exist User Type. Please select another User Type.';
                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+err+\"</span></div></div>\");
                return false;
            } else if($('#'+level_tr_class).length > 0){
                err += '\\nAlready exist Level. Please select another Level.';
                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+err+\"</span></div></div>\");
                return false;
            }else {                
                var append_data = '<tr class='+tr_class+' id='+level_tr_class+'>';              
                append_data += '<td>'+user_type_name+'<input type=\'hidden\' name=\'TblComplainEscalationTxn['+tr_count+'][user_type]\' value=\''+user_type+'\'></td>';
                append_data += '<td>'+department_name+'<input type=\'hidden\' name=\'TblComplainEscalationTxn['+tr_count+'][department]\' value=\''+department+'\'></td>';
                append_data += '<td>'+escalation_time+'<input type=\'hidden\' name=\'TblComplainEscalationTxn['+tr_count+'][escalation_time]\' value=\''+escalation_time+'\'></td>';
                append_data += '<td>'+level+'<input type=\'hidden\' name=\'TblComplainEscalationTxn['+tr_count+'][level]\' value=\''+level+'\'></td>';
                append_data += '<td class=\'pb8\'><a href=\'javascript:void(0);\' class=\'remove_product btn btn-default btn-raised\' title=\'Remove\'>Remove</a></td>';
                append_data += '</tr>';
                $('#complain_escalation_list').append(append_data);
                $('#tblcomplainescalationtxn-user_type').val(null).trigger('change');
                $('#tblcomplainescalationtxn-department').val(null).trigger('change');
                $('#tblcomplainescalationtxn-escalation_time').val('');
                $('#tblcomplainescalationtxn-level').val('');
                $('.btn-save-txn').removeClass('disabled no_pointer');
                tr_count++;
            }
            
        } else {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+err+'</div></div>');
        }
    });
    $(document).on('click', '.remove_product', function () {
        $(this).closest('tr').remove();
    });
";
$this->registerJs($script, View::POS_END, 'complain-escalation');
?>


