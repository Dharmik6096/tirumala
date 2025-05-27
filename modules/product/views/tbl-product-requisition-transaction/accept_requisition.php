<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;
use kartik\detail\DetailView;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Accept Requisition'));
$is_submit = FALSE;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body padding-0">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                        'id' => 'req-accept-form'
                    ], 'fieldConfig' => [
                        'labelOptions' => ['class' => false],
        ]]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table class="table table-hover table-bordered table-striped table-main table-language">
            <thead>
                <tr>
                    <!--<th><?php //Yii::t('app', '')           ?></th>-->
                    <th><?= Html::checkbox('requisition_checkbox', false, ['label' => '', 'class' => 'allCheckBoxManage reqTxnFieldsNotDisabled']) ?></th>
                    <th><?= Yii::t('app', 'Requisition') ?></th>
                    <th><?= Yii::t('app', 'Type') ?></th>
                    <th><?= Yii::t('app', 'Code') ?></th>
                    <th><?= Yii::t('app', 'Ref Code') ?></th>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <th><?= Yii::t('app', 'SAP Code') ?></th>
                    <th><?= Yii::t('app', 'Product') ?></th>
                    <th><?= Yii::t('app', 'Requested Qty') ?></th>
                    <th><?= Yii::t('app', 'Rate') ?></th>
                    <th><?= Yii::t('app', 'UOM') ?></th>
                    <th><?= Yii::t('app', 'Amount') ?></th>
                    <th><?= Yii::t('app', 'Credit Limit') ?></th>
                    <th><?= Yii::t('app', 'Accepted Qty') ?></th>
                    <th><?= Yii::t('app', 'Discount Amount (Rs.)') ?></th>
                    <th><?= Yii::t('app', 'Status') ?></th>
                    <th><?= Yii::t('app', 'Action') ?></th>
                    <th><?= Yii::t('app', 'Remark') ?></th>
                </tr>     
                <?php
                foreach ($model as $key => $transaction) {
                    $row_id = '';
                    if ($transaction->status == 'Rejected') {
                        $class = 'hidden';
                    } else {
                        $class = '';
                        $addid = '';
                        $class = 'success';
                    }
                    ?>
                    <tr id="<?= $row_id ?>" class="<?= $class ?>">   
                        <?php
                        $requisition = $transaction->productRequisitionCode;
                        ?>
                        <td class="pcheckbox"><?= $form->field($transaction, '[' . $key . ']requisition_transaction_code', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{endLabel}</div>'])->checkbox(['class' => 'reqTxnFields reqTxnFieldsNotDisabled']); ?></td>
                        <td class="pname"><?= !empty($requisition) && !empty($requisition->product_requisition_code) ? $requisition->product_requisition_code : '' ?></td>
                        <td class="pname"><?= !empty($requisition) && !empty($requisition->vendor_type) ? $requisition->vendor_type : '' ?></td>
                        <td class="pname"><?= !empty($requisition) && !empty($requisition->vendor_code) ? $requisition->vendor_code : '' ?></td>
                        <td class="pname"><?= $transaction->getEntityRefCode() ?></td>
                        <td class="pname"><?= $transaction->getEntityName() ?></td>
                        <td class="pname"><?= Yii::$app->general->getforeignkey($transaction->productCode, 'ref_code') ?></td>
                        <td class="pname"><?= Yii::$app->general->getforeignkey($transaction->productCode, 'product_name') ?></td>
                        <td><?= $transaction->quantity ?></td>
                        <td class="pro-rate"><?= $transaction->provisional_rate ?></td>
                        <td class="pro-uom"><?= $transaction->getUom($transaction->product_code) ?></td>
                        <td class="pro-amt"><?= $transaction->provisional_amount ?></td>
                        <td></td>
                        <td class='width10'><?php
                            if ($transaction->approved_quantity != NULL) {
                                $value = $transaction->approved_quantity;
                            } else {
                                $value = $transaction->quantity;
                            }
                            $is_submit = TRUE;
                            $disabled = 'disabled';

                            echo $form->field($transaction, '[' . $key . ']approved_quantity', ['options' => ['class' => '']])->textInput(['maxlength' => true, 'value' => $value, 'class' => 'form-control qty-validate accept-qty', 'data-incr' => $key, "disabled" => $disabled])->label(false);
                            ?></td>
                        <td class='width10'>
                            <?php
                            echo $form->field($transaction, '[' . $key . ']discount_amount', ['options' => ['class' => '']])->textInput(['maxlength' => true, 'value' => $transaction->discount_amount, 'class' => 'form-control discount number-validate', "disabled" => $disabled])->label(false);
                            ?>
                        </td>
                        <td><?= isset($transaction->status) ? Yii::$app->dropdown->getRecords('requisition_status')['data'][$transaction->status] : ''; ?></td>
                        <td><?php
                            if (in_array($transaction->status, ['Rejected'])) {
                                $transaction->req_action = 2;
                            } else if (in_array($transaction->status, ['Under Dispatch'])) {
                                $transaction->req_action = 1;
                            } else {
                                $transaction->req_action = 1;
                            }
                            if (in_array($transaction->status, ['Sent'])) {
                                echo $form->field($transaction, '[' . $key . ']req_action')->dropdownList(['1' => 'Accept', '2' => 'Reject'], ['data-incr' => $key, "disabled" => $disabled, 'class' => 'action-req form-control', 'prompt' => 'Select'])->label(false);
                            }
                            ?></td>
                        <td class='width10'>
                            <?= Html::activeHiddenInput($transaction, '[' . $key . ']requisition_transaction_code', ['value' => $transaction->requisition_transaction_code, 'class' => 'trans', 'disabled' => true]) ?>
                            <?= Html::activeHiddenInput($transaction, '[' . $key . ']quantity', ['value' => $transaction->quantity, 'disabled' => true]) ?>
                            <?= Html::activeHiddenInput($transaction, '[' . $key . ']provisional_rate', ['value' => $transaction->provisional_rate, 'disabled' => true]) ?>
                            <?= Html::activeHiddenInput($transaction, '[' . $key . ']product_code', ['value' => $transaction->product_code, 'class' => 'pcode', 'disabled' => true]) ?>

                            <?php
                            echo $form->field($transaction, '[' . $key . ']x_col2', ['options' => ['class' => '']])->textInput(['maxlength' => true, 'value' => $transaction->x_col2, 'class' => 'form-control discount', "disabled" => $disabled])->label(false);
                            ?>
                        </td>
                    </tr>                
                    <?php
                }
                echo Html::hiddenInput('flag', '1', ['id' => 'flag']);
                ?>
                <?= Html::hiddenInput('scheme_item', '', ['id' => 'scheme_item']); ?>
            </thead>
        </table>  

        <div class="col-sm-12 mt15 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                if ($is_submit) {
                    echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-default apply-shortcut submitForm', 'value' => '1', 'name' => 'accept', 'id' => 'accept']);
                }
                echo Yii::$app->controls->cancel($model);
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = " 
    var oldval='';
    $('.accept-qty').on('click',function(){
        oldval=$(this).val();
    });
    $('.accept-qty').on('blur',function(){        
        var incr = $(this).data('incr');
        var remain = $('#tblproductrequisitiontransaction-'+incr+'-quantity').val();
        var value = $(this).val();
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        //console.log(parent.attr('id'));
        if(((parseInt(value) > parseInt(remain)) || value=='' || value==0) && parent.attr('id')===''){
//            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Accept Qty can not be 0 or more than Remain Qty.') . "</span></div></div>\");
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Accept Qty can not be 0 or more than Remain Qty.') . "</span></div></div>\",function(){
                bootbox.hideAll();
                $('#'+id).val(oldval);
                $('#'+id).focus().select();
            });
            return false;
        }
        calc(parent);
        //var parent = $(this).parents('tr');
        var id =parent.find('.trans').val();
        var accept= parent.find('.action-req').val();
        var qty= $(this).val();
        if(id !='' && accept==1 && parent.attr('id')===''){
            modifyBlock(parent,'',id,false);
//            getMsg(id,qty,parent);  
        }
         $('.discount').trigger('blur');
    });
    $('.action-req').on('change',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.trans').val();
            var prod= parent.find('.pcode').val();
            if($(this).val()==1)
            {
                var qty= parent.find('.accept-qty').val();
                if(id!=''){
//                     getMsg(id,qty,parent);  
                }
            }
            else{
                calcDiscount(parent,'','','');
                modifyBlock(parent,'',id,false);               
            }
             $('#db-'+prod+' .action-req').val($(this).val());
    });
    
function getMsg(id,qty,parent)
{
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/inventory/tbl-product-requisition-transaction/get-product-scheme']) . "',    
        data: 'id='+id+'&qty='+qty,
        success: function(data) {
            var obj1 = $.parseJSON(data);
            var scheme=obj1.scheme;
            if (obj1.status == 'success')
            {
                bootbox.alert({ 
                    message: obj1.msg, 
                });
                switch(scheme.entry){
                    case 'add_product':
                        modifyBlock(parent,scheme,id,true);
                    break;
                    case 'calc_discount_per':
                        calcDiscount(parent,scheme.scheme_value,id,true);
                    break;
                    case 'calc_discount':
                        calcDiscount(parent,scheme.scheme_value,id,false);
                    break;
            }
               
            }
        }
    });   
}

function modifyBlock(parent,obj,id,add)
    {
        var check=id.replace(/\//g,'-');
             check=check.replace(' ','-');
        var prod= parent.find('.pcode').val();
        if(add)
        {
            if ($('#db-'+prod+' .pcode').length > 0 && $('#db-'+prod+' .pcode').val()==obj.product_code){
                 $('#db-'+prod).show();
            }
            else{
                if($('#db-'+prod).length > 0)
                { 
                 $('#db-'+prod+' .action-req').val(2);
                 $('#db-'+prod).hide();
                }
               var block='<tr id=\"added-'+check+'\" class=\"danger\"><td>'+obj.product_name+'</td><td>'+obj.scheme_value+'</td><td>'+obj.product_rate+'</td><td>'+obj.uom+'</td><td>'+obj.product_amount+'</td><td></td><td>'+obj.scheme_value+'</td><td></td><td>Sent</td><td></td><td></td></tr>';
                $('#req-accept-form thead').append(block); 
            }
        }
        else{
           if ($('#added-'+check).length > 0){
                $('#added-'+check).remove();
            }
        }
    }
    
function calcDiscount(parent,value,id,per)
{
    var accept= parent.find('.action-req').val();
    if(accept==1){
        var amt=parent.find('.pro-rate').text()*parent.find('.accept-qty').val();
        if(per)
        {
            var disc=(amt*value)/100;
        }
        else{
            var disc=value;
        }
        disc=disc.toFixed(2);
        parent.find('.discount').val(disc);
       // parent.find('.discount').text(disc);
    }
    else
    {  
        parent.find('.discount').val('');
        //parent.find('.discount').text('');
    }
}
    $('.discount').on('blur',function(){ 
        var id = $(this).attr('id');
        var value = $(this).val();  
        var parent = $(this).parents('tr');
        var amt = parent.find('.pro-amt').text();
        if(isNaN(parseInt(amt))) {
            amt = 0;
        }
        if((parseInt(value) > parseInt(amt))){
//            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Discount amount can not be grater than amount.') . "</span></div></div>\");
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Discount amount can not be grater than amount.') . "</span></div></div>\",function(){
                bootbox.hideAll();
                $('#'+id).focus().select();
            });
            return false;
        }
    });
function calc(parent){
       var rate = parent.find('.pro-rate').text();;       
       var qty = parent.find('.accept-qty').val(); 
       var amt = parseFloat(qty)*parseFloat(rate);   
       if(!isNaN(amt)){
        amt=amt.toFixed(2);
         parent.find('.pro-amt').text(amt);                    
        }
}


$(document).off('click', '.reqTxnFields').on('click', '.reqTxnFields', function(e){
    e.stopPropagation();
    $('.enabDisabFields').removeClass('enabDisabFields');
    $(this).closest('tr').addClass('enabDisabFields');
    var checked_length = $('input:checkbox:checked:not(\'.allCheckBoxManage\')').length;
    var total_length = $('input:checkbox:not(\'.allCheckBoxManage\')').length;
    if($(this).is(':checked')) {
        $('tr.enabDisabFields input').removeAttr('disabled');
        $('tr.enabDisabFields select').removeAttr('disabled');
        if(checked_length == total_length){
            $('.allCheckBoxManage').prop('checked', true);
        }
    } else {
        $('tr.enabDisabFields input:not(\'.reqTxnFieldsNotDisabled\')').prop('disabled', true);
        $('tr.enabDisabFields select').prop('disabled', true);
        $('.allCheckBoxManage').prop('checked', false);
    }
});

$('.submitForm').on('click',function(e) {
    e.preventDefault();
    var checked_length = $('input:checkbox:checked:not(\'.allCheckBoxManage\')').length;
    if(checked_length <= 0){
        bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Please select atleast one record.') . "</span></div></div>\");
        return false;
    }
    $('.reqTxnFields:not(:checked)').closest('tr').addClass('restrictPost');
    $('.reqTxnFieldsNotDisabled:not(:checked)').closest('tr').addClass('restrictPost');
    $('tr.restrictPost input').prop('disabled', true);
    $('tr.restrictPost select').prop('disabled', true);
    $('#req-accept-form').submit();
});

$('.allCheckBoxManage').on('click', function(e) {
    $('.enabDisabFields').removeClass('enabDisabFields');
    $('tr').addClass('enabDisabFields');
    if($(this).is(':checked')) {
        $('tr.enabDisabFields input').removeAttr('disabled');
        $('tr.enabDisabFields select').removeAttr('disabled');
        $('.reqTxnFields').prop('checked', true);
    } else {
        $('tr.enabDisabFields input:not(\'.reqTxnFieldsNotDisabled\')').prop('disabled', true);
        $('tr.enabDisabFields select').prop('disabled', true);
        $('.reqTxnFields').prop('checked', false);
    }
})

";
$this->registerJs($script, View::POS_END, 'product-code');
?>

<?php
$script = "
    localStorage.removeItem('transactionsArray');
    $('#error-summary').hide();
    $('#scheme_item').val('');
    $('.show-sample').on('click',function(e){  
        var parent = $(this).parents('tr');
        var prod= parent.find('.trans').val();
        $('#requisition_transaction_no').val(prod);
        $('#error-summary').hide();                    
        $('#sampleModal').modal('toggle');
    });
";
$this->registerJs($script, View::POS_END, 'scheme-add');

if (!empty($selectedArr)) {
    $selectedArrJ = json_encode($selectedArr);
    $script = "
        var selectedArr = '" . $selectedArrJ . "';
        selectedArr = JSON.parse(selectedArr);
        $.each(selectedArr, function(index, value) {
            $('#tblproductrequisitiontransaction-'+value+'-requisition_transaction_code').trigger('click');
        });
    ";
    $this->registerJs($script, View::POS_END, 'selectDefaultCheckbox');
}
?>