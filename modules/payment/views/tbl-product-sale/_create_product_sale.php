<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::$app->label->title('create', 'Product Sale');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'create-product-sale-form'],
                        'validateOnBlur' => FALSE,
                        'validateOnEnter' => TRUE,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <?php echo $form->errorSummary($model); ?>
            <div class="row">
                <div class="col-sm-2" id="union">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductsale-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
                </div> 
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductsale-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
                </div>      
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductsale-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
                </div>

                <div class="col-sm-2">
                    <?php
                    $where = json_encode(['is_product_sale' => 1]);
                    $notInArr = json_encode(['Member']);
                    echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
                    echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
                    ?>    
                    <?= Yii::$app->dropdown->customerType($model, $form, 'tblproductsale-union_code,customer_type_depends,customer_type_depends_not_in', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE, FALSE); ?>
                    <?php // Yii::$app->dropdown->customer_type($model, $form, 'tblproductsale-bmc_code', 'customer_type', TRUE, FALSE);  ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->date($model, $form, 'invoice_date', '', true); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'ex_code')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= Html::activeHiddenInput($model, 'customer_code') ?>
                    <?= $form->field($model, 'customer_name')->textInput(['readOnly' => true]) ?>
                    <?php // Yii::$app->dropdown->customer_code($model, $form, 'tblproductsale-bmc_code,tblproductsale-customer_type', 'customer_code', TRUE, FALSE); ?>
                </div>
                <!--<div class="clearfix"></div>-->
                <div class="col-sm-2 reset_field">
                    <?= Yii::$app->dropdown->dropdownStatic('payment_mode', $model, $form, 'form-group', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', false); ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?php Yii::$app->dropdown->depend_dropdown('product', $detailModel, $form, 'tblproductsale-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
                </div>

                <div class=" col-sm-2 reset_field unit disabledDiv">
                    <?= Yii::$app->dropdown->dropdown('unit_code', $detailModel, $form, 'form-group col-sm-2', $detailModel->getAttributeLabel('unit_code'), FALSE, 'unit_code'); ?>    
                </div>
                <div class="col-sm-2 reset_field">
                    <?= $form->field($detailModel, 'rate')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= $form->field($detailModel, 'quantity')->textInput() ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= Yii::$app->dropdown->dropdown('tax_code', $detailModel, $form, 'form-group col-sm-3', $detailModel->getAttributeLabel('tax_code'), false, 'tax_code'); ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= $form->field($model, 'discount')->textInput() ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= Html::activeHiddenInput($detailModel, 'x_col1'); ?>   
                    <?= $form->field($detailModel, 'tax_amount')->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?= $form->field($model, 'amount_due')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-2 noOfInstallment reset_field">
                    <?= $form->field($model, 'no_of_installment')->textInput() ?>
                </div>
                <?= $form->field($detailModel, 'product_sale_rate_applicability_code', ['template' => '{input}'])->hiddenInput()->label(false) ?>
                <div class="clearfix"></div>
                <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?php
                        AjaxSubmitButton::begin([
                            'label' => Yii::t('app', 'Save'),
                            'ajaxOptions' => [
                                'type' => 'POST',
                                'url' => Url::to(['create-product-sale']),
                                'beforeSend' => new JsExpression("function(data){
                                                $('.error-summary').hide();
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    reloadGrid();
//                                                                  $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $(".panel-body").scrollTop(0);
                                                                    $(".reset_field input").val("");
                                                                    $(".reset_field select").val("");
//                                                                    $("#tblproductsale-payment_mode").val("");
//                                                                    $("#tblproductsaledetails-product_code").val("");
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                        setTimeout(function(){
                                                                            $("#tblbillheaddetail-customer_type").focus();
                                                                        },100);
                                                                    });
                                                                }else{
                                                                
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                            ],
                            'options' => ['class' => 'btn btn-default btn-raised',
                                'type' => 'submit'],
                        ]);
                        AjaxSubmitButton::end();
                        ?>
                        <?= Yii::$app->controls->reset(); ?>
                        <?= Yii::$app->controls->cancel($model); ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <?=
            $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
            ?>
        </div>
    </div>
</div>


<?php
$script = "
    $(document).on('change','#tblproductsale-invoice_date',function(){
        setRate();
        reloadGrid('show_loader');
    });
    $(document).on('change','#tblproductsale-bmc_code',function(){
        $('#tblproductsale-ex_code').val('');
        $('#tblproductsale-ex_code').trigger('change');
        setRate();
        reloadGrid('show_loader');
    });
    $(document).on('change','#tblproductsale-customer_type',function(){
        $('#tblproductsale-ex_code').val('');
        $('#tblproductsale-ex_code').trigger('change');
        setRate();
        reloadGrid();
    });
    $(document).on('change','#tblproductsale-customer_code',function(){
        setRate();
        reloadGrid();
    });
    $(document).on('change','#tblproductsaledetails-product_code',function(){
        setRate();
    });
    $(document).on('change','#tblproductsaledetails-quantity',function(){
        setAmount();
        setAmtFields();
        setTaxAmount();
    });
    
    $('#tblproductsaledetails-rate').on('change', function(){
        var rate = parseFloat($('#tblproductsaledetails-rate').val());
        if(rate==0){
            $('#tblproductsaledetails-amount').val(rate.toFixed(2));
            $('#tblproductsaledetails-tax_amount').val(rate.toFixed(2));
            $('#tblproductsaledetails-total_amount').val(rate.toFixed(2));
        }else{
            $(this).attr('data-val', $(this).val());
//            $('#tblproductsaledetails-x_col1').val($(this).val());
            setAmtFields();
            setTaxAmount();
        }
    });
    
    $(document).on('change','#tblproductsale-discount',function(){
        setAmount();
        setTaxAmount();
    });
    

    $('#tblproductsaledetails-tax_code').on('change', function(){
        $('#tblproductsale-discount').val('');
//        $('#tblproductsale-discount').trigger('change');
        setAmtFields();
        setTaxAmount();
    });

    function setAmtFields(){
        var Qty = parseFloat($('#tblproductsaledetails-quantity').val());
        var rate = parseFloat($('#tblproductsaledetails-rate').attr('data-val'));
        if(isNaN(Qty)){
            Qty = 0;
        }
        if(isNaN(rate)){
            rate = 0;
        }
        var amount = Qty * rate;
        $('#tblproductsaledetails-amount').val(amount.toFixed(2));
    }
    function setTaxAmount(){
        var unionCode = $('#tblproductsale-union_code').val();
        var taxCode = $('#tblproductsaledetails-tax_code').val();
        var amountValue = $('#tblproductsale-amount').val();
        var rateValue = $('#tblproductsaledetails-rate').attr('data-val');
        var discountValue = $('#tblproductsale-discount').val();
        var recQty = $('#tblproductsaledetails-quantity').val();
        if(isNaN(amountValue)){
            amountValue = 0;
        }
        if(isNaN(recQty)){
            recQty = 0;
        }
        if(isNaN(discountValue)){
            discountValue = 0;
        }
        
        if(amountValue != 0 && taxCode != ''){
            $.ajax({
                type: 'POST',
                url: '" . Url::to(['get-calculation']) . "',     
                data: 'amount='+rateValue+'&tax='+taxCode+'&unionCode='+unionCode,
                success: function(data)
                {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        var totalAmt = obj1.total;
                        var changed_amount = obj1.changedAmount;
                        var taxAmt = (rateValue - totalAmt) * recQty;
                        var disc = obj1.totalDiscount;
                        var chagnedRate = obj1.changedAmount;
                        var changeamount = (obj1.changedAmount * recQty).toFixed(2);
                        var taxAmount = Math.abs(taxAmt.toFixed(2));
                        $('#tblproductsaledetails-rate').val(chagnedRate.toFixed(2));
                        $('#tblproductsale-amount').val(changeamount);
                        $('#tblproductsaledetails-tax_amount').val(taxAmount);
                        var totalAmount = parseFloat(changeamount) + parseFloat(taxAmount);
                        $('#tblproductsale-amount_due').val(totalAmount.toFixed(0));
                        setTaxAmountDisc();
                    }
                }
            });
        }
    }

    function setTaxAmountDisc() {
        var unionCode = $('#tblproductsale-union_code').val();
        var taxCode = $('#tblproductsaledetails-tax_code').val();
        var amountValue = $('#tblproductsale-amount').val();
        var rateValue = $('#tblproductsaledetails-rate').attr('data-val');
        var discountValue = $('#tblproductsale-discount').val();
        var recQty = $('#tblproductsaledetails-quantity').val();
        var taxAmt = $('#tblproductsaledetails-tax_amount').val();

        if(isNaN(amountValue)){
            amountValue = 0;
        }
        if(isNaN(taxAmt)){
            taxAmt = 0;
        }
        if(isNaN(recQty)){
            recQty = 0;
        }
        if(isNaN(discountValue) || discountValue == '' || discountValue == undefined){
            discountValue = 0;
        }
        var existtotalAmt = $('#tblproductsaledetails-rate').attr('data-val');
        var totalAmount = existtotalAmt - discountValue;
        if(taxCode != ''){
            $.ajax({
                type: 'POST',
                url: '" . Url::to(['get-calculation']) . "',     
                data: 'amount='+discountValue+'&tax='+taxCode+'&flag=check&&unionCode='+unionCode,
                success: function(data)
                {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        var totalAmt = obj1.total;
                        var diffAmt = totalAmt - discountValue;
                        taxAmt = taxAmt - diffAmt;
                        var existToralAmt = $('#tblproductsale-amount_due').val();
                        var chagneAmt = existToralAmt - diffAmt - discountValue;
                        $('#tblproductsale-amount_due').val(chagneAmt.toFixed(0));
                        $('#tblproductsaledetails-tax_amount').val(Math.abs(taxAmt.toFixed(2)));
                    }
                }
            });
        }
    }
    
    setNoOfInstallment();
    $(document).on('change','#tblproductsale-payment_mode',function(){
        setNoOfInstallment();
    });
    function setNoOfInstallment(){
        $('.noOfInstallment').hide();
        if($('#tblproductsale-payment_mode').val() == 1) {
            $('.noOfInstallment').show();
        }
    }
    function setRate(){
        var product_code=$('#tblproductsaledetails-product_code').val();
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var customer_type=$('#tblproductsale-customer_type').val();
        var customer_code=$('#tblproductsale-customer_code').val();
        var bmc_code=$('#tblproductsale-bmc_code').val();
        var union_code=$('#tblproductsale-union_code').val();
        var invoice_date=$('#tblproductsale-invoice_date').val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-product-sale/load-rate']) . "',
            data: {product_code: product_code, is_member_rate: 0, invoice_date: invoice_date, _csrf : csrfToken, customer_type: customer_type, customer_code: customer_code, bmc_code: bmc_code, union_code: union_code},
            success: function(data) {
                var d=JSON.parse(data);
                $('#tblproductsaledetails-rate').val(d.sale_rate);
                $('#tblproductsaledetails-rate').attr('data-val', d.sale_rate);
                $('#tblproductsaledetails-x_col1').val(d.sale_rate);
                $('#tblproductsaledetails-unit_code').val(d.unit_code);
                $('#tblproductsaledetails-product_sale_rate_applicability_code').val(d.product_sale_rate_applicability_code);
                setAmount();
            },
            error:function(data){
                    }
        });
//        $('#tblproductsaledetails-rate').val('10');
    }
    
    function setAmount(){
        var quantity = $('#tblproductsaledetails-quantity').val();
        if(quantity == '' || isNaN(quantity)) {
            quantity = 0;
        }
        var rate = $('#tblproductsaledetails-rate').val();
        if(rate == '' || isNaN(rate)) {
            rate = 0;
        }
        var discount = $('#tblproductsale-discount').val();
        if(discount == '' || isNaN(discount)) {
            discount = 0;
        }
        var amount = parseFloat(quantity) *  parseFloat(rate);
        if(amount == '' || isNaN(amount)) {
            amount = 0;
        }
        $('#tblproductsale-amount').val(amount);
        
        var amountDue = parseFloat(amount) -  parseFloat(discount);
        if(amountDue == '' || isNaN(amountDue)) {
            amountDue = 0;
        }
        $('#tblproductsale-amount_due').val(amountDue);
    }
    
    function reloadGrid(loaderType = 'hide_loader') {
        var saleDate = $('#tblproductsale-invoice_date').val();
        if(saleDate != '' && saleDate != undefined && saleDate != null) {
            var url = '" . Url::to(['/payment/tbl-product-sale/list-grid']) . "'+ '?' + $('#create-product-sale-form').serialize();
            $.ajax({
                type: 'get',
                url: url,
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#gridcontentSet').html(data);
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                },
            });
        } else {
            $('#gridcontentSet').html('');
        }
    }
    

    $(document).on('change', '#tblproductsale-ex_code', function() {  
        setVendorCode();
    });

    function setVendorCode(){
        $('#tblproductsale-customer_code').val('');
        $('#tblproductsale-customer_name').val('');
        var code = $('#tblproductsale-ex_code').val();
        var type= $('#tblproductsale-customer_type').val(); 
        var union= $('#tblproductsale-union_code').val(); 
        var bmc= $('#tblproductsale-bmc_code').val(); 
        if(code != '' && code != null && code != undefined) {
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-customer']) . "',
                data: {'dcs_code':code,'customer_type':type,'union_code':union,'bmc_code':bmc},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblproductsale-customer_name').val(obj.data); 
                        $('#tblproductsale-customer_code').val(obj.code); 
                        $('#tblproductsale-customer_code').trigger('change');
                    }else{
                        //Please enter valid Code(Last 4 digit)
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Please enter valid Code.') . "</span></div></div>', function(result){
                            setTimeout(function(){
                                $('#tblproductsale-ex_code').focus();
                            },100);
                        });           
                        $('#tblproductsale-customer_code').val('');                    
                        $('#tblproductsale-customer_name').val('');                    
                        $('#tblproductsale-customer_code').focus();

                    }
                },
                error:function(data){

                }
            });
        }
    }
    
";
$this->registerJs($script, View::POS_END, 'create-product-sale-form');
?>
