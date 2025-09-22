<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductRequisitionTransaction */
/* @var $form yii\widgets\ActiveForm */

$button = Yii::$app->label->button($type);

$start_date = Yii::$app->request->get('date');
?>
<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'form-group col-sm-3',
            ],
            'validateOnBlur' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row panel-subheading">
    <?= Html::activeHiddenInput($model, 'product_name'); ?>   
    <?php
    $data = [];
    if (Yii::$app->request->get('id') != -1) {
        if (!empty($model->product_code))
            $data = [$model->product_code => Yii::$app->general->getforeignkey($model->productCode, 'product_name')];
    }
    ?>

    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>

    <div class="col-sm-2 reset_field">
        <?php
        echo Yii::$app->dropdown->product_master($model, $form, 'tblproductrequisitiontransaction-union_code', 'product_code', $model->getAttributeLabel('product_code'), FALSE, '', FALSE, TRUE);
        ?>
    </div>
    <!--    <div class="col-sm-2 reset_field">
    <?php // Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblproductrequisitiontransaction-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
        </div>-->
    <?= $form->field($model, 'uom', ['options' => ['placeholder' => $model->uom, 'class' => 'form-group col-sm-2']])->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'quantity', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

    <?= $form->field($model, 'provisional_rate', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['readonly' => true]) ?>

    <!-- <div class="clearfix"></div> -->

    <?= $form->field($model, 'provisional_amount', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['readonly' => true]) ?>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'requisition_on_date', '', false, $start_date); ?>
    </div>

    <?= Html::hiddenInput('product_req', '', ['id' => 'product_req']); ?>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Html::submitButton(($model->isNewRecord) ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => 'btn btn-default apply-shortcut', 'value' => 'save', 'name' => 'submit']) ?>
        <?= Yii::$app->controls->reset(); ?>
        <?php
        echo Yii::$app->controls->cancel($model, 'tbl-product-requisition/index');
        ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php
$selectscript = "";
$script = " 
    
    var records;
    var jsonEncoded = '" . $jsonEncoded . "';
    if(jsonEncoded==''){    
        records = localStorage.getItem('productRequisition');
    }else{
        records = jsonEncoded;
    }
    $('#product_req').val(records);
    
    $('#tblproductrequisitiontransaction-product_code').on('change',function(){   
        var id = $('#tblproductrequisitiontransaction-product_code').val();
        var jsn = $('#product_req').val();
        
        var local =  $.parseJSON(jsn);
        customer_type = local.vendor_type;
        customer_code = local.vendor_code;
        if(id!=''){
            checkDispatchCenter(id, local.dcs_code);
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/product/tbl-product-requisition-transaction/validate-product-data']) . "',    
                data: 'date='+local.req_date+'&customer_type='+customer_type+'&customer_code='+customer_code+'&id='+id+'&rid=" . Yii::$app->getRequest()->getQueryParam('id') . "',
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        $('#tblproductrequisitiontransaction-product_name').val(obj1.name);
                        $('#tblproductrequisitiontransaction-provisional_rate').val(obj1.rate);
                        $('#tblproductrequisitiontransaction-uom').val(obj1.unit);
                        $('#tblproductrequisitiontransaction-product_name').attr('readonly',true);
                        $('#tblproductrequisitiontransaction-provisional_rate').attr('readonly',true);
                        $('#tblproductrequisitiontransaction-provisional_amount').attr('readonly',true);
                        $('#tblproductrequisitiontransaction-uom').attr('readonly',true);
                        calc();
                    }else{
                        $('#tblproductrequisitiontransaction-product_name').val('');
                        $('#tblproductrequisitiontransaction-provisional_rate').val('');
                        $('#tblproductrequisitiontransaction-provisional_amount').val('');
                        $('#tblproductrequisitiontransaction-product_name').attr('readonly',false);
                        $('#tblproductrequisitiontransaction-provisional_rate').attr('readonly',false);
                        $('#tblproductrequisitiontransaction-provisional_amount').attr('readonly',false);
                    }
                }
            });   
        }
    });
    
    $('#tblproductrequisitiontransaction-quantity').on('blur',function(){   
        calc();
    });
    
    $('#tblproductrequisitiontransaction-provisional_rate').on('blur',function(){   
        calc();
    });
    
    function calc(){
    
        var qty = $('#tblproductrequisitiontransaction-quantity').val();
        var rate = $('#tblproductrequisitiontransaction-provisional_rate').val();
        
        var amt = parseFloat(qty)*parseFloat(rate);
        
        if(amt!='0.00' && !isNaN(amt)){
            amt=amt.toFixed(2);
            $('#tblproductrequisitiontransaction-provisional_amount').val(amt);
        }
    }
    
    function checkDispatchCenter(product_code, dcs_code) {
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/product/tbl-product-requisition-transaction/check-dispatch-center-applicability']) . "',    
            data: 'product_code='+product_code+'&dcs_code='+dcs_code,
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'error')
                {
                    bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-3x fa-times-circle aria-hidden=true\"></i></div><span>'+obj1.message+'</span></div></div>',function(){
                        bootbox.hideAll();
                        $('#tblproductrequisitiontransaction-product_code').focus().select();
                    });
                    return false;
                }
            }
        });  
    }
";
$this->registerJs($script, View::POS_END, 'product-req-txn-form-one');
?>