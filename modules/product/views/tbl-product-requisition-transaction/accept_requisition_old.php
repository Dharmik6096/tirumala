<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\detail\DetailView;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Accept Requisition'));
$is_submit = FALSE;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body padding-0">
        <div class="accept-grid-search-inner clearfix">
            <?php
            // DetailView Attributes Configuration
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'product_requisition_code',
                            'label' => Yii::t('app', 'Product Requisition Code'),
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => false,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'deleteOptions' => [// your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
            ]);
            ?>
        </div>

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
<!--                            <th class="width5">Select</th>-->
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
                    <!--<th><?php // Yii::t('app', 'Add')              ?></th>-->
                </tr>     
                <?php
                foreach ($model->tblProductRequisitionTransactions as $key => $transaction) {
                    $row_id = '';
                    if ($transaction->status == 'Rejected') {
                        $class = 'hidden';
                    } else {
                        $class = '';
//                            if (!empty($transaction->parent_product_code)) {
                        $addid = '';
//                            if ($transaction->scheme_add_type == 1) {
//                                $class = 'success';
//                                $addid = '1';
//                            } else {
                        $class = 'success';
//                            }
//                        $row_id = 'db-' . $addid;
//                            } else
//                                $row_id = '';
                    }
                    ?>
                    <tr id="<?= $row_id ?>" class="<?= $class ?>">   
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
//                            if (in_array($transaction->status, array(1, 6, 46, 51))) {
                            if (in_array($transaction->status, ['Sent'])) {
                                $is_submit = TRUE;
                                $disabled = FALSE;
                            } else {
                                $disabled = 'disabled';
                            }

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
                                $transaction->req_action = '';
                            }
                            if (in_array($transaction->status, ['Sent'])) {
//                                    if (empty($transaction->parent_product_code))
                                echo $form->field($transaction, '[' . $key . ']req_action')->dropdownList(['1' => 'Accept', '2' => 'Reject'], ['data-incr' => $key, "disabled" => $disabled, 'class' => 'action-req form-control', 'prompt' => 'Select'])->label(false);
//                                    else {
//                                        echo Html::activeHiddenInput($transaction, '[' . $key . ']req_action', ['value' => '1', 'class' => 'action-req']);
//                                        // echo Html::activeHiddenInput($transaction, '[' . $key . ']parent_product_code', ['value' => 'Yes']);
//                                    }
                            }
                            ?></td>
                        <!--<td>-->
                        <?php
                        if ($row_id == '' && !$disabled) {
//                                    echo Html::a(Yii::t('app', '<span class="glyphicon glyphicon-plus icon-size"></span>'), 'javascript:void(0)', ['class' => 'apply-shortcut show-sample', 'shortcut_key' => 'ctrl+alt+c', 'title' => 'Add Scheme Product']);
                        }
                        ?>
                        <!--</td>-->
                        <?= Html::activeHiddenInput($transaction, '[' . $key . ']requisition_transaction_code', ['value' => $transaction->requisition_transaction_code, 'class' => 'trans']) ?>
                        <?= Html::activeHiddenInput($transaction, '[' . $key . ']quantity', ['value' => $transaction->quantity]) ?>
                        <?= Html::activeHiddenInput($transaction, '[' . $key . ']provisional_rate', ['value' => $transaction->provisional_rate]) ?>
                        <?= Html::activeHiddenInput($transaction, '[' . $key . ']product_code', ['value' => $transaction->product_code, 'class' => 'pcode']) ?>
                        <?php // Html::activeHiddenInput($transaction, '[' . $key . ']parent_product_code', ['value' => $transaction->parent_product_code]) ?>

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
                    echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-default apply-shortcut', 'value' => '1', 'name' => 'accept', 'id' => 'accept']);
                }
                echo Yii::$app->controls->cancel($model);
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php // $this->render('_manual_scheme', ['schememodal' => $schememodal]) ?>
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
?>