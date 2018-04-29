<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSaleDetails */
/* @var $form yii\widgets\ActiveForm */
$model->rate_app_code;
?>

<?php  $form = ActiveForm::begin();?>
<?= Html::hiddenInput('sales_date', '', ['id' => 'sales-data']); ?>
<?= Html::hiddenInput('sales_details', '', ['id' => 'sales-details']); ?>
<?= Html::hiddenInput('union_code', '', ['id' => 'tblproductsale-union_code']); ?>
<div class="row">
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblproductsale-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
    </div>
    <?= $form->field($model, 'rate_app_code', ['template' => '{input}'])->hiddenInput()->label(false) ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'rate')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Html::button($model->isNewRecord ? Yii::t('app', 'Add to Sale') : Yii::t('app', 'Update'), ['id' => 'add-sale', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<!--<h5 class="panel-subtitle">Items</h5>-->
<div class="row mt35">
    <div class="form-grid">
        <div class="table-responsive">
            <table id="sales-items" class="table table-bordered table-hover">
                <thead>
                <th>Product</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Amount</th>
                <th></th>
                </thead>
            </table>
        </div>
    </div>
    <div class="clearfix mt35"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Html::button($model->isNewRecord ? Yii::t('app', 'Create Sale') : Yii::t('app', 'Update'), ['id' => 'sale-form', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php
$script = "
    var i=0;
    $(document).ready(function() {
        i=0;
        var union=JSON.parse(localStorage.getItem('sale_data')).union_code;
        $('#tblproductsale-union_code').val(union);
        localStorage.removeItem('sale_item');
        $('#sale-form').on('click',function(){
             if (localStorage.getItem('sale_item') !== null) {
                 $('#sales-data').val(localStorage.getItem('sale_data'));
                 $('#sales-details').val(localStorage.getItem('sale_item'));
                 $('#w0').unbind().submit();
             }
             else{
                 alert('no item added');
             }
        });
        $('#add-sale').on('click',function(){
        $('#tblproductsaledetails-product_code,#tblproductsaledetails-qty').blur();
//        if($('#tblproductsaledetails-product_code').val()=='' || $('#tblproductsaledetails-qty').val()=='')
//        {
//            return false;
//        }
        $('#w0').yiiActiveForm('validate');
        var length = $('#w0').find('.has-error').length;
        if(length > 0){   return false;  }
        if($('#tblproductsaledetails-rate').val()==0)
        {
            bootbox.confirm({
                            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want add product with total 0?</span></div></div>',
                            buttons: {
                                'cancel': {
                                                label: 'Cancel',
                                                className: 'btn btn-danger'
                                  },
                                'confirm': {
                                                label: 'Ok',
                                                className: 'btn btn-primary'
                                 }
                            },
                            callback: function(result) {
                                if (result) {
                                    calcAmount();
                                    addToCart(); 
                                }
                                else{
                                    
                                }
                            }
                        });
        }
        else
        {
            calcAmount();
            addToCart(); 
//            $('form#w0').trigger('reset');
        }
        });
        
        $('#tblproductsaledetails-product_code').on('change',function(){
            var code=$(this).val();
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var dcs=JSON.parse(localStorage.getItem('sale_data')).dcs_code;
            var union=JSON.parse(localStorage.getItem('sale_data')).union_code;
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/payment/tbl-product-sale-details/load-rate']) . "',
                    data: {product_code: code, _csrf : csrfToken, dcs_code: dcs, union_code: union},
                    success: function(data) {
                            var d=JSON.parse(data);
                            $('#tblproductsaledetails-rate_app_code').val(d.product_rate_applicability_code);
                            $('#tblproductsaledetails-rate').val(d.rate);
                    },
                    error:function(data){
                                //alert('Your data has not been submitted..Please try again');
                            }
                });
        });
        $('#tblproductsaledetails-qty').on('change',function(){
            calcAmount();
        });
    });
    
    function calcAmount()
    {
     var qty=$('#tblproductsaledetails-qty').val();
     var rt=  $('#tblproductsaledetails-rate').val();
     var amt=qty*rt;
     $('#tblproductsaledetails-amount').val(amt);
    }
    
    function addToCart()
    {
      var obj=new Object();
      obj.product_code=$('#tblproductsaledetails-product_code').val();
      obj.rate_app_code=$('#tblproductsaledetails-rate_app_code').val();
      obj.rate=$('#tblproductsaledetails-rate').val();
      obj.qty=$('#tblproductsaledetails-qty').val();
      obj.amount=$('#tblproductsaledetails-amount').val();
      if (localStorage.getItem('sale_item') !== null) {
            var items = [];
            items=JSON.parse(localStorage.getItem('sale_item'));
            items[i]=obj;
            localStorage.setItem('sale_item', JSON.stringify(items));
        }
        else{
            var item = [];
            item[i]=obj;
            localStorage.setItem('sale_item', JSON.stringify(item));
        }
        var product= $('#tblproductsaledetails-product_code option:selected').text(); 
        $('#sales-items').append('<tr id=\"item-'+i+'\"><td>'+product+'</td><td>'+obj['rate']+'</td><td>'+obj['qty']+'</td><td>'+obj['amount']+'</td><td><a href=\"javascript:void(0)\" data-index=\"'+i+'\" class=\"rem-item\" onclick=\"removeCartItems(this)\"><i class=\"fa fa-trash\"></i></a></td></tr>');
        $('#w0').trigger('reset');
        $('#tblproductsaledetails-product_code').focus();
        i++;
    }
    
    function removeCartItems(obj){            
        var index=parseInt($(obj).attr('data-index'));
        removeFromCart(index); 
        $(obj).parents('tr').remove();
    }
    
    function removeFromCart(index)
    {
      var obj=new Object();
      if (localStorage.getItem('sale_item') !== null) {
            var items = [];
            items=JSON.parse(localStorage.getItem('sale_item'));
            items.splice(index,1);
            if(isNull(items))
            {
              localStorage.removeItem('sale_item');
            }
            else {
                localStorage.setItem('sale_item', JSON.stringify(items));
            }
        }
    }
    function isNull(inputArray) {
        if (inputArray.length) {
            var currentElement = inputArray[0];
            for (var i = 1, len = inputArray.length; i < len && currentElement === null; i += 1) {
                currentElement = currentElement || inputArray[i];
            }
            if (currentElement !== null) {
                return false;
            }
        }
        return true;
    }
";
$this->registerJs($script, View::POS_END, 'union');
