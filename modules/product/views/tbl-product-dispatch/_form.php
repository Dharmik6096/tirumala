<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$title = Yii::$app->label->title($type, 'product material dispatch');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-product-dispatch-form">
    <div class="large-search hidden-print">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    </div>
    <div class="clearfix"></div>
    <?php
    $form = ActiveForm::begin(['options' => [
                    'class' => 'save-form',
                    'id' => 'req-accept-form',
                    'field-class' => 'col-sm-3'
                ],
                'validateOnBlur' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>
    <table class="table table-bordered table-striped table-main table-language">
        <thead>
            <tr>
                <th><?= Html::checkbox('requisition_checkbox', false, ['label' => '', 'class' => 'allCheckBoxManage reqTxnFieldsNotDisabled']) ?></th>
                <th><?= Yii::t('app', 'Type') ?></th>
                <th><?= Yii::t('app', 'Name') ?></th>
                <th><?= Yii::t('app', 'Req No.') ?></th>
                <th><?= Yii::t('app', 'SAP Code') ?></th>
                <th><?= Yii::t('app', 'Product') ?></th>
                <th><?= Yii::t('app', 'Req. Qty') ?></th>
                <th><?= Yii::t('app', 'Accepted Qty') ?></th>   
                <th><?= Yii::t('app', 'Rate') ?></th>
                <th><?= Yii::t('app', 'Amount') ?></th>
                <th><?= Yii::t('app', 'Previous Disp. Qty') ?></th>
                <th><?= Yii::t('app', 'Pending to be Disp.') ?></th>
                <th><?= Yii::t('app', 'Discount Amount (Rs.)') ?></th>
                <th><?= Yii::t('app', 'Disp. Qty') ?></th>
                <th><?= Yii::t('app', 'SO Number') ?></th>
                <th><?= Yii::t('app', 'Delivery Number') ?></th>
                <th><?= Yii::t('app', 'Bill Number') ?></th>
                <th><?= Yii::t('app', 'Remarks') ?></th>
                <th><?= Yii::t('app', 'Close') ?></th>
            </tr> 
        </thead>
        <?php
        $i = 0;
        foreach ($dataProvider->models as $row) {
            $reqTransactions = $row->getApprovedRequisitionTransactions($row->product_requisition_code, $searchModel->dispatch_center_code);
            foreach ($reqTransactions as $transaction) {
                $remainQty = $transaction['approved_quantity'] - $modeltransaction->getPreviousDispQty($transaction['requisition_transaction_code']);
                $class = '';
                if (!empty($transaction['parent_product_code'])) {
//                    if ($transaction['scheme_add_type'] == 1) {
                    $class = 'success';
//                    } else {
//                        $class = 'danger';
//                    }
                }
                ?>
                <tr class="<?= $class ?>">  
                    <!--<td class="pname"><? $form->field($modeltransaction, '[' . $i . ']requisition_transaction_code', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{endLabel}</div>{error}{hint}'])->checkbox(['class' => 'reqTxnFields']); ?></td>-->
                    <td> 
                        <?php echo $form->field($modeltransaction, '[' . $i . ']requisition_transaction_code')->checkbox(['label' => null, 'class' => 'reqTxnFields reqTxnFieldsNotDisabled']); ?>
                    </td>
                    <td class="dcsname"><?= isset($row->vendor_type) ? Yii::$app->dropdown->getRecords('requisition_type')['data'][$row->vendor_type] : '' ?></td>
                    <td class="subcentername"><?= $row->getEntityName() ?></td>
                    <td><?= $row->product_requisition_code ?></td>
                    <td><?= $transaction['ref_code'] ?></td>
                    <td><?= $transaction['product_name'] ?></td>
                    <td><?= $transaction['quantity'] ?></td>
                    <td><?= $transaction['approved_quantity'] ?></td>
                    <td class="pro-rate"><?= $transaction['provisional_rate'] ?></td>
                    <td class="pro-amt"><?= $transaction['provisional_rate'] * $remainQty ?></td>
                    <td><?= $modeltransaction->getPreviousDispQty($transaction['requisition_transaction_code']) ?></td>
                    <td><?= $remainQty ?></td>
                    <?php $disabled = 'disabled'; ?>
                    <td><?= $form->field($modeltransaction, '[' . $i . ']discount_amount')->textInput(['maxlength' => true, 'value' => $transaction['discount_amount'], 'class' => 'form-control number-validate discount', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>

                    <td><?= $form->field($modeltransaction, '[' . $i . ']dispatch_qty')->textInput(['maxlength' => true, 'value' => $remainQty, 'class' => 'form-control qty-validate qty-dispatch', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>
                    <td><?= $form->field($modeltransaction, '[' . $i . ']so_no')->textInput(['maxlength' => true, 'class' => 'form-control number-validate so_no', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>
                    <td><?= $form->field($modeltransaction, '[' . $i . ']delivery_no')->textInput(['maxlength' => true, 'class' => 'form-control number-validate delivery_no', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>
                    <td><?= $form->field($modeltransaction, '[' . $i . ']bill_no')->textInput(['maxlength' => true, 'class' => 'form-control number-validate bill_no', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>
                    <td><?= $form->field($modeltransaction, '[' . $i . ']remarks')->textInput(['maxlength' => true, 'class' => 'form-control remarks', 'data-incr' => $i, "disabled" => $disabled])->label(false) ?></td>

                    <td> 
                        <?php echo $form->field($modeltransaction, '[' . $i . ']is_close')->checkbox(['label' => null, 'class' => 'transaction-close', "disabled" => $disabled]); ?>
                    </td>

                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']product_code', ['value' => $transaction['product_code']]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']union_code', ['value' => $row->union_code]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']plant_code', ['value' => $row->plant_code]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']mcc_plant_code', ['value' => $row->mcc_plant_code]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']bmc_code', ['value' => $row->bmc_code]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']dcs_code', ['value' => $row->dcs_code]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']vendor_type', ['value' => $row->vendor_type]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']vendor_code', ['value' => $row->vendor_code,]) ?>
                    <?php // Html::activeHiddenInput($modeltransaction, '[' . $i . ']dcs_code', ['value' => $row->dcs_code, 'class' => 'dcscode']) ?>                       
                    <?php // Html::activeHiddenInput($modeltransaction, '[' . $i . ']sub_center_code', ['value' => $row->sub_center_code, 'class' => 'subcentercode']) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']product_requisition_code', ['value' => $row->product_requisition_code, 'class' => 'requisitioncode']) ?>
                    <?php // Html::activeHiddenInput($modeltransaction, '[' . $i . ']product_scheme_code', ['value' => $transaction['product_scheme_code']]) ?>
                    <?php // Html::activeHiddenInput($modeltransaction, '[' . $i . ']parent_product_code', ['value' => $transaction['parent_product_code']]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']remain_qty', ['value' => $remainQty]) ?>
                    <?php //Html::activeHiddenInput($modeltransaction, '[' . $i . ']discount_amount', ['value' => $transaction['discount_amount']]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']rate', ['value' => $transaction['provisional_rate']]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']amount', ['value' => $transaction['provisional_amount']]) ?>
                    <?= Html::activeHiddenInput($modeltransaction, '[' . $i . ']requisition_transaction_code', ['value' => $transaction['requisition_transaction_code'], 'class' => 'trans']) ?>
                    <?php // Html::activeHiddenInput($modeltransaction, '[' . $i . ']passon_to_member', ['value' => $transaction['passon_to_member']]); ?>

                </tr>                
                <?php
                $i++;
            }
//            $dscCode = $row->dcs_code;
//            $subCenterCode = $row->sub_center_code;
            $i = $i;
        }
        ?>
    </table>
    <div class="panel-subheading clearfix">
        <?php
        //$model->date = date('Y-m-d');
        ?>
        <?php
        //Yii::$app->controls->date($model, $form, 'date', 'form-group col-sm-3',false, FALSE, false); 
        ?>
        <?= $form->field($model, 'challan_date', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true, 'readonly' => 'readonly', 'value' => date('d-m-Y')])->label('Date'); ?>                      
        <?= Html::activeHiddenInput($model, 'route_code', ['value' => !empty(Yii::$app->request->queryParams['TblProductRequisitionSearch']) ? (!empty(Yii::$app->request->queryParams['TblProductRequisitionSearch']['route_code']) ? Yii::$app->request->queryParams['TblProductRequisitionSearch']['route_code'] : '') : '']) ?>
        <?= Html::activeHiddenInput($model, 'union_code', ['value' => $searchModel->union_code]) ?>
        <?= Html::activeHiddenInput($model, 'plant_code', ['value' => $searchModel->plant_code]) ?>
        <?= Html::activeHiddenInput($model, 'mcc_plant_code', ['value' => $searchModel->mcc_plant_code]) ?>
        <?= Html::activeHiddenInput($model, 'bmc_code', ['value' => $searchModel->bmc_code]) ?>
        <?= Html::activeHiddenInput($model, 'dcs_code', ['value' => $searchModel->dcs_code]) ?>
        <?= Html::activeHiddenInput($model, 'route_code', ['value' => $searchModel->route_code]) ?>

        <?php
        $vendorCode = strtolower($searchModel->vendor_type) == 'dcs' ? $searchModel->dcs_code : $searchModel->bmc_code;
        echo Html::activeHiddenInput($model, 'vendor_type', ['value' => $searchModel->vendor_type]);
        echo Html::activeHiddenInput($model, 'vendor_code', ['value' => $vendorCode]);
        ?>
        <?php // Html::activeHiddenInput($model, 'dcs_code', ['value' => $dscCode]) ?>
        <?php // Html::activeHiddenInput($model, 'sub_center_code', ['value' => $subCenterCode]) ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'vehicle_no', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => true]) ?>            
        </div>
        <?php // $form->field($model, 'vehicle_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true])  ?>                      
        <?= $form->field($model, 'reference_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
        <?php // Html::hiddenInput('scheme_item', '', ['id' => 'scheme_item']); ?>
    </div>

    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Html::submitButton($button, ['class' => 'btn btn-default apply-shortcut submitForm', 'shortcut_key' => $model->isNewRecord ? 'ctrl+alt+s' : 'ctrl+alt+u',]) ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<?php
$script = " 
    
    $('.qty-dispatch').on('blur',function(){
         var parent = $(this).parents('tr');
        var incr = $(this).data('incr');
        var remain = $('#tblproductmaterialdispatchtransaction-'+incr+'-remain_qty').val();
        var value = $(this).val();
        var id = $(this).attr('id');
        if(parseInt(value) > parseInt(remain)){
            bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-3x fa-times-circle aria-hidden=true\"></i></div><span>" . Yii::t('app', 'Dispatch Qty can not be more then Remain Qty.') . "</span></div></div>',function(){
                bootbox.hideAll();
                $('#'+id).focus().select();
            });
            return false;
        }
        calc(parent);
        $('.discount').trigger('blur');

    });
  
$('.transaction-close').on('change', function () {
    chkbx=this;
    if(chkbx.checked){
        bootbox.confirm('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-question\"></i></div><span>" . Yii::t('app', 'Are you sure to close this product?') . "</span></div></div>', 
            function(result){ 
                if(!result){ 
                    chkbx.checked=false;
                }
            }
        );
    }   
}); 
 $('.discount').on('blur',function(){ 
        var id = $(this).attr('id');
        var value = $(this).val();  
        var parent = $(this).parents('tr');
        var amt = parent.find('.pro-amt').text();      
        if((parseInt(value) > parseInt(amt))){
            bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-3x fa-times-circle\"></i></div><span>" . Yii::t('app', 'Discount amount can not be grater than amount.') . "</span></div></div>',function(){
                bootbox.hideAll();
                $('#'+id).focus().select();
            });
            return false;
        }
    });
 function calc(parent){
       var rate = parent.find('.pro-rate').text();;       
       var qty = parent.find('.qty-dispatch').val(); 
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

";
//
//$script = "function closeproduct(val, id) {
//        if (confirm('Are sure to close this product?') == true) {
//            $('#' + val).val('1');
//            var btn = document.getElementById(id);
//            btn.disabled = true;
//        }
//    }";
$this->registerJs($script, View::POS_END, 'material-dispatch');
?>
<?php
$script = "
     localStorage.removeItem('transactionsArray');
     $('#error-summary').hide();
     $('#scheme_item').val('');
     $('.show-sample').on('click',function(e){  
       var parent = $(this).parents('tr');
       
       var prod= parent.find('.trans').val();
       var dcsname= parent.find('.dcsname').text();
       var subcentername= parent.find('.subcentername').text();
       var dcs_code= parent.find('.dcscode').val();
       var sub_center_code= parent.find('.subcentercode').val();
       var product_requisition_code= parent.find('.requisitioncode').val();

       $('#dcs_text').val(dcsname);
       $('#sub_center_text').val(subcentername)
       $('#dcs_code').val(dcs_code)
       $('#sub_center_code').val(sub_center_code)
       $('#product_requisition_code').val(product_requisition_code)
       $('#requisition_transaction_code').val(prod);
       
       $('#error-summary').hide();                    
       $('#sampleModal').modal('toggle');
            });
            

$('#tblproductmaterialdispatch-vehicle_no').change(function(){
    var vehicle_no = $('#tblproductmaterialdispatch-vehicle_no').val();
    var date = $('#tblproductmaterialdispatch-date').val();
    if(vehicle_no != '' && date != ''){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/inventory/tbl-product-material-dispatch/validate-vehicle']) . "',               
            data: 'date='+date+'&vehicle_no='+vehicle_no,
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'error')
                {
                    bootbox.alert('<div class=\"row\"><div class=\"col-sm-2\"><i class=\"fa fa-3x fa-info text-primary aria-hidden=true\"></i></div><div class=\"col-sm-10 padding-left-0\">" . Yii::t('app', 'Dispatch already done for selected vehicle.') . "</div></div>',function(){
                        bootbox.hideAll();
                        $('#tblproductmaterialdispatch-vehicle_no').focus().select();
                    });
                }
            }
        });   
    }
});
";
$this->registerJs($script, View::POS_END, 'scheme-add');
?>