<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$message = !empty($message) ? $message : 'Product Sale';
$this->title = Yii::$app->label->title('create', $message);
$type = !empty($type) ? $type : '';
$cashSale = isset($cashSale) ? $cashSale : '';
//memberWiseSale
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$batchNoWiseProductRate = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_product_rate', 'PORTAL');
$setProductRateBatchWise = ($batchNoWiseInventory == 1 && $batchNoWiseProductRate == 1) ? 'TRUE' : 'FALSE';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'create-product-sale-form'],
                        'validateOnBlur' => FALSE,
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
                    if ($type == 'memberWiseSale') {
                        echo Html::activeHiddenInput($model, 'customer_type');
                        echo Yii::$app->dropdown->bmc_society($model, $form, 'tblproductsale-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'));
                    } else {
                        if ($cashSale) {
                            echo Html::activeHiddenInput($model, 'is_cash_sale');
                            $where = json_encode(['is_cash_sale' => 1]);
                        } else {
                            $where = json_encode(['is_product_sale' => 1]);
                        }
                        $notInArr = json_encode(['Member']);
                        echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
                        echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
                        echo Yii::$app->dropdown->customerType($model, $form, 'tblproductsale-union_code,customer_type_depends,customer_type_depends_not_in', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE, FALSE);
                    }
                    ?>
                    <?php // Yii::$app->dropdown->customer_type($model, $form, 'tblproductsale-bmc_code', 'customer_type', TRUE, FALSE);   ?>
                </div>
                <div class="col-sm-2 no_pointer">
                    <?php
                    $model->invoice_date = !empty($model->invoice_date) ? $model->invoice_date : date('d-m-Y');
                    echo Yii::$app->controls->date($model, $form, 'invoice_date', '', true, date('d-m-Y'));
                    ?>
                </div>
                <div class="clearfix"></div>
                <?php
                $lable = Yii::t('app', 'Code');
                if ($type === 'memberWiseSale') {
                    $lable = Yii::t('app', 'Member Code');
                }
                ?>
                <div class="col-sm-2 reset_field ex_code">
                    <?= $form->field($model, 'ex_code')->textInput()->label($lable) ?>
                </div>
                <div class="col-sm-2 reset_field party">
                    <?= Yii::$app->dropdown->generalPartyMaster($model, $form, 'tblproductsale-bmc_code', 'general_party_master_code', $model->getAttributeLabel('party_code')); ?>
                </div>
                <div class="col-sm-2 reset_field">
                    <?php
                    $lable = Yii::t('app', 'name');
                    if ($type === 'memberWiseSale') {
                        $lable = Yii::t('app', 'Member name');
                    }
                    ?>
                    <?= Html::activeHiddenInput($model, 'customer_code') ?>
                    <?= $form->field($model, 'customer_name')->textInput(['readOnly' => true]) ?>
                    <?php // Yii::$app->dropdown->customer_code($model, $form, 'tblproductsale-bmc_code,tblproductsale-customer_type', 'customer_code', TRUE, FALSE);  ?>
                </div>
                <!--<div class="clearfix"></div>-->

                <?php if ($cashSale) { ?>
                    <div class="col-sm-1 reset_field">
                        <?= Yii::$app->dropdown->dropdownStatic('cash_payment', $model, $form, 'form-group', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', false); ?>
                    </div>
                    <?php
                } else {
                    $removeKey = FALSE;
                    if ($batchNoWiseInventory == 1) {
                        $removeKey = TRUE;
                    }
                    ?>
                    <div class="col-sm-1 reset_field">
                        <?= Yii::$app->dropdown->dropdownStatic('payment_mode', $model, $form, 'form-group', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', false, $removeKey); ?>
                    </div>
                <?php } ?>
                <div class="col-sm-1 avlCredit reset_field">
                    <?= $form->field($model, 'avl_credit')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-3 reset_field">
                    <?php Yii::$app->dropdown->depend_dropdown('product', $detailModel, $form, 'tblproductsale-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
                </div>
                <?php
                if ($batchNoWiseInventory == 1) {
                    $sale_type = $type == 'memberWiseSale' ? 'DCS' : 'BMC';
                    echo Html::hiddenInput('type', $sale_type, ['id' => 'batch_type_depends']);
                    echo Html::hiddenInput('check_is_mcc', TRUE, ['id' => 'check_is_mcc_depends']);
                    $depends = $type == 'memberWiseSale' ? 'tblproductsale-dcs_code' : 'tblproductsale-bmc_code';
                    ?>
                    <div class="col-sm-2 reset_field">
                        <?= Yii::$app->dropdown->productBatch($detailModel, $form, 'batch_type_depends,' . $depends . ',tblproductsaletransaction-product_code,check_is_mcc_depends', 'sap_batch_no', $detailModel->getAttributeLabel('sap_batch_no'), FALSE); ?> 
                    </div>
                <?php } ?>
                <?= Html::hiddenInput('batch_no_wise_rate', $setProductRateBatchWise, ['id' => 'batch_no_wise_rate']); ?>
                <div class=" col-sm-1 reset_field unit disabledDiv">
                    <?= Yii::$app->dropdown->dropdown('unit_code', $detailModel, $form, 'form-group col-sm-2', $detailModel->getAttributeLabel('unit_code'), FALSE, 'unit_code'); ?>    
                </div>

                <div class="col-sm-1 reset_field">
                    <?= $form->field($detailModel, 'rate')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="clearfix">  </div>
                <div class="col-sm-1 reset_field">
                    <?= $form->field($detailModel, 'available_stock')->textInput(['readonly' => TRUE]) ?>
                </div>
                <div class="col-sm-1 reset_field number-validate">
                    <?= $form->field($detailModel, 'quantity')->textInput() ?>
                </div>
                <div class="col-sm-1 reset_field">
                    <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-1 reset_field">
                    <?php Yii::$app->dropdown->depend_dropdown('depend_tax_code', $detailModel, $form, 'tblproductsale-union_code', 'form-group col-sm-1 padding-right-5 padding-left-0', $detailModel->getAttributeLabel('tax_code'), 'tax_code'); ?>
                    <?php // Yii::$app->dropdown->dropdown('tax_code', $detailModel, $form, 'form-group col-sm-1', $detailModel->getAttributeLabel('tax_code'), false, 'tax_code');  ?>
                </div>
                <div class="col-sm-1 reset_field">
                    <?= $form->field($model, 'discount')->textInput() ?>
                </div>
                <div class="col-sm-1 reset_field">
                    <?= Html::activeHiddenInput($detailModel, 'x_col1'); ?>   
                    <?= $form->field($detailModel, 'tax_amount')->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-sm-1 reset_field">
                    <?= $form->field($model, 'amount_due')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-1 noOfInstallment reset_field">
                    <?= Yii::$app->dropdown->dropdownStatic('no_of_installment', $model, $form, '', TRUE); ?>
                </div>  
                <div class="col-sm-2 dedStartDate reset_field">
                    <?= Yii::$app->controls->date($model, $form, 'deduction_start_date', '', date('Y-m-d'), false, false); ?>
                </div>  
                <?php if ($cashSale) { ?>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($detailModel, 'transaction_no')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($detailModel, 'sales_order_no')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($detailModel, 'delivery_no')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($detailModel, 'billing_no')->textInput() ?>
                    </div>
                <?php }
                ?>
                <div class="col-sm-2">
                    <?= $form->field($detailModel, 'remarks')->textInput() ?>
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
                                                                   
                                                                    $("#create-product-sale-form .reset_field input").val("");
                                                                    $("#create-product-sale-form .reset_field select").val("");
                                                                    $("#tblproductsale-payment_mode").val("");
                                                                    $("#tblproductsale-payment_mode").trigger("select2:select");
                                                                    $("#tblproductsale-payment_mode").trigger("change");
                                                                    $("#tblproductsaletransaction-product_code").val("");
                                                                    $("#tblproductsaletransaction-product_code").trigger("select2:select");
                                                                    $("#tblproductsaletransaction-product_code").trigger("change");
                                                                    $("#tblproductsaletransaction-tax_code").val("");
                                                                    $("#tblproductsaletransaction-tax_code").trigger("select2:select");
                                                                    $("#tblproductsaletransaction-tax_code").trigger("change");
                                                                    
                                                                    var customerType = $("#tblproductsale-customer_type").val();
                                                                    $(".party").hide();
                                                                    $(".ex_code").show();
                                                                    var customerType = $(this).val();
                                                                    if (customerType && customerType.toLowerCase() == "party") {
                                                                        $(".ex_code").hide();
                                                                        $(".party").show();
                                                                    }
//                                                                 
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
            $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'cashSale' => $cashSale])
            ?>
        </div>
    </div>
</div>


<?php
$script = "
    $('.party').hide();
    var batchNoWiseRate = $('#batch_no_wise_rate').val();
    $('#tblproductsale-invoice_date').prop('readonly', true);
    $(document).on('change','#tblproductsale-invoice_date',function(){
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
        setDeductionStartDate();
        reloadGrid('show_loader');
    });
    $(document).on('change','#tblproductsale-bmc_code',function(){
        $('#tblproductsale-ex_code').val('');
        $('#tblproductsale-ex_code').trigger('change');
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
       reloadGrid('show_loader');
    });
    $(document).on('change','#tblproductsale-dcs_code',function(){
        $('#tblproductsale-ex_code').val('');
        $('#tblproductsale-ex_code').trigger('change');
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
        reloadGrid('show_loader');
    });
    $(document).on('change','#tblproductsale-customer_type',function(){
        $('#tblproductsale-ex_code').val('');
        $('#tblproductsale-ex_code').trigger('change');
        $('.party').hide();
        $('.ex_code').show();
        var customerType = $(this).val();
        if (customerType && customerType.toLowerCase() == 'party') {
            $('.ex_code').hide();
            $('.party').show();
        }
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
        reloadGrid();
    });
    $(document).on('change','#tblproductsale-customer_code',function(){
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
        if(setData($(this).val())){
            reloadGrid();
        }
    });
    $(document).on('change','#tblproductsaletransaction-product_code',function(){
        if(batchNoWiseRate == 'FALSE') {
            setRate();
        }
    setTax();
    });
    $(document).on('change','#tblproductsaletransaction-quantity',function(){
        setAmount();
        setAmtFields();
        setTaxAmount();
    });
    
    $('#tblproductsaletransaction-rate').on('change', function(){
        var rate = parseFloat($('#tblproductsaletransaction-rate').val());
        if(rate==0){
            $('#tblproductsaletransaction-amount').val(rate.toFixed(2));
            $('#tblproductsaletransaction-tax_amount').val(rate.toFixed(2));
            $('#tblproductsaletransaction-total_amount').val(rate.toFixed(2));
        }else{
            $(this).attr('data-val', $(this).val());
//            $('#tblproductsaletransaction-x_col1').val($(this).val());
            setAmtFields();
            setTaxAmount();
        }
    });
    
    $(document).on('change','#tblproductsale-discount',function(){
        setAmount();
        setTaxAmount();
    });
    

    $('#tblproductsaletransaction-tax_code').on('change', function(){
        $('#tblproductsale-discount').val('');
//        $('#tblproductsale-discount').trigger('change');
        setAmtFields();
        setTaxAmount();
    });

    function setAmtFields(){
        var Qty = parseFloat($('#tblproductsaletransaction-quantity').val());
        var rate = parseFloat($('#tblproductsaletransaction-rate').attr('data-val'));
        if(isNaN(Qty)){
            Qty = 0;
        }
        if(isNaN(rate)){
            rate = 0;
        }
        var amount = Qty * rate;
        $('#tblproductsaletransaction-amount').val(amount.toFixed(2));
    }
    function setTaxAmount(){
        var unionCode = $('#tblproductsale-union_code').val();
        var taxCode = $('#tblproductsaletransaction-tax_code').val();
        var amountValue = $('#tblproductsale-amount').val();
        var rateValue = $('#tblproductsaletransaction-rate').val();
        var discountValue = $('#tblproductsale-discount').val();
        var recQty = $('#tblproductsaletransaction-quantity').val();
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
                        $('#tblproductsaletransaction-rate').val(chagnedRate.toFixed(2));
                        $('#tblproductsale-amount').val(changeamount);
                        $('#tblproductsaletransaction-tax_amount').val(taxAmount);
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
        var taxCode = $('#tblproductsaletransaction-tax_code').val();
        var amountValue = $('#tblproductsale-amount').val();
        var rateValue = $('#tblproductsaletransaction-rate').attr('data-val');
        var discountValue = $('#tblproductsale-discount').val();
        var recQty = $('#tblproductsaletransaction-quantity').val();
        var taxAmt = $('#tblproductsaletransaction-tax_amount').val();

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
        var existtotalAmt = $('#tblproductsaletransaction-rate').attr('data-val');
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
                        $('#tblproductsaletransaction-tax_amount').val(Math.abs(taxAmt.toFixed(2)));
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
        $('.avlCredit').hide();
        $('.noOfInstallment').hide();
         $('.dedStartDate').hide();
        if($('#tblproductsale-payment_mode').val() == 1) {
            $('.noOfInstallment').show();
            $('.avlCredit').show();
            $('.dedStartDate').show();
        }
    }
    function setRate(){
        $('#tblproductsaletransaction-rate').val('');
        //        $('#tblproductsaletransaction-rate').attr('data-val', d.sale_rate);
        $('#tblproductsaletransaction-x_col1').val('');
        $('#tblproductsaletransaction-unit_code').val('');
        $('#tblproductsaletransaction-product_sale_rate_applicability_code').val('');
        var product_code=$('#tblproductsaletransaction-product_code').val();
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var customer_type=$('#tblproductsale-customer_type').val();
        var customer_code=$('#tblproductsale-customer_code').val();
        var bmc_code=$('#tblproductsale-bmc_code').val();
        var union_code=$('#tblproductsale-union_code').val();
        var invoice_date=$('#tblproductsale-invoice_date').val();
        var is_member_rate = 0;
        var dcs_code = '';
        var formType = '" . $type . "';
        if(formType == 'memberWiseSale') {
            is_member_rate = 1;
            customer_code=$('#tblproductsale-dcs_code').val();
            customer_type='DCS';
        }
        if (customer_type && customer_type.toLowerCase() == 'party') {
            customer_type = 'BMC';
            customer_code = bmc_code;
        }
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-product-sale/load-rate']) . "',
            data: {product_code: product_code, is_member_rate: is_member_rate, invoice_date: invoice_date, _csrf : csrfToken, customer_type: customer_type, customer_code: customer_code, bmc_code: bmc_code, union_code: union_code},
            success: function(data) {
                var d=JSON.parse(data);
                $('#tblproductsaletransaction-rate').val(d.sale_rate);
        //                $('#tblproductsaletransaction-rate').attr('data-val', d.sale_rate);
                $('#tblproductsaletransaction-x_col1').val(d.sale_rate);
                $('#tblproductsaletransaction-unit_code').val(d.unit_code);
                $('#tblproductsaletransaction-unit_code').trigger('change');
                $('#tblproductsaletransaction-unit_code').trigger('select2:select');
                $('#tblproductsaletransaction-product_sale_rate_applicability_code').val(d.product_sale_rate_applicability_code);
                setAmount();
            },
            error:function(data){
                    }
        });
        //        $('#tblproductsaletransaction-rate').val('10');
    }
    
    function setAmount(){
        var quantity = $('#tblproductsaletransaction-quantity').val();
        if(quantity == '' || isNaN(quantity)) {
            quantity = 0;
        }
        var rate = $('#tblproductsaletransaction-rate').val();
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
        if(checkData()) {
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

    function checkData() {
        var bmc_code = $('#tblproductsale-bmc_code').val();
        var invoice_date = $('#tblproductsale-invoice_date').val();
        var dcs_code = $('#tblproductsale-dcs_code').val();
        var customer_type = $('#tblproductsale-customer_type').val();
        // if(setData(bmc_code) && setData(invoice_date) && setData(customer_type) && (customer_type == 'Member' ? setData(dcs_code) : true){
        if(setData(bmc_code) && setData(invoice_date) && setData(customer_type) && ((customer_type == 'Member' ? setData(dcs_code) : true))){
            return true;
        } else {
            return false;
        }
    }
    

    $(document).on('change', '#tblproductsale-ex_code', function() {  
        setVendorCode();
            $('#tblproductsale-payment_mode').val('');
            $('#tblproductsale-payment_mode').trigger('change');
            $('#tblproductsale-avl_credit').val(0);
    });
    $(document).on('change', '#tblproductsale-general_party_master_code', function() {  
        setVendorCode();
            $('#tblproductsale-payment_mode').val('');
            $('#tblproductsale-payment_mode').trigger('change');
            $('#tblproductsale-avl_credit').val(0);
    });
    $('#tblproductsale-invoice_date').change(function(){
        $('#tblproductsale-ex_code').val('');
        if($('#tblproductsale-payment_mode').val() == 1) {
            setAvailableCredit();
        }
    });
    
    function setVendorCode(){
        $('#tblproductsale-customer_code').val('');
        $('#tblproductsale-customer_name').val('');
        var type= $('#tblproductsale-customer_type').val(); 
        var code = $('#tblproductsale-ex_code').val();
        if (type && type.toLowerCase() == 'party') {
            code = $('#tblproductsale-general_party_master_code').val();
        }
        var union= $('#tblproductsale-union_code').val(); 
        var bmc= $('#tblproductsale-bmc_code').val(); 
        var date= $('#tblproductsale-invoice_date').val(); 
        var plant= $('#tblproductsale-plant_code').val(); 
        var mcc= $('#tblproductsale-mcc_plant_code').val(); 
        var dcsCode = '';
        var formType = '" . $type . "';
        if(formType == 'memberWiseSale') {
            dcsCode = $('#tblproductsale-dcs_code').val(); 
        }
        if(code != '' && code != null && code != undefined) {
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-customer']) . "',
                data: {'customer_code':code, 'dcsCode': dcsCode,'customer_type':type,'union_code':union,'bmc_code':bmc,'date':date,'plant':plant,'mcc':mcc},
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
    $('#tblproductsaletransaction-product_code').on('change', function(){
        getAvailableStock();
    });
    
    $('#tblproductsale-customer_type').on('change', function(){
        getAvailableStock();
        if($('#tblproductsale-payment_mode').val() == 1) {
            setAvailableCredit();
        }
    });
    
    $('#tblproductsale-bmc_code').on('change', function(){
        getAvailableStock();
    });
    
    $('#tblproductsale-dcs_code').on('change', function(){
        getAvailableStock();
    });
    
    $('#tblproductsaletransaction-sap_batch_no').on('change', function(){
        var sap_batch_no = $('#tblproductsaletransaction-sap_batch_no').val();
        if(setData(sap_batch_no)){
            getAvailableStock();
        }
    });
    $('#tblproductsaletransaction-sap_batch_no').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        let varVal = $('#tblproductsaletransaction-sap_batch_no option:nth-child(2)').val();
        if(varVal == undefined) {
            varVal = '';
        }
        $('#tblproductsaletransaction-sap_batch_no').val(varVal);
        $('#tblproductsaletransaction-sap_batch_no').trigger('change');
        $('#tblproductsaletransaction-sap_batch_no').trigger('select2:select');
    });
    function getAvailableStock(){
        var type = $('#tblproductsale-customer_type').val();
        var product = $('#tblproductsaletransaction-product_code').val();
        var union = $('#tblproductsale-union_code').val();
        var sap_batch_no = $('#tblproductsaletransaction-sap_batch_no').val();
        var code ='';
        $('#tblproductsaletransaction-x_col1').val('');
        if(type=='Member'){
            var code = $('#tblproductsale-dcs_code').val();
        }else{
            var code = $('#tblproductsale-bmc_code').val();
        }
       
        if(setData(type) && setData(code) && setData(product)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-available-stock']) . "',
                data: {'product':product,'type':type,'code':code,'union_code':union,'sap_batch_no':sap_batch_no},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        $('#tblproductsaletransaction-available_stock').val(obj.stock);
                        if(batchNoWiseRate == 'TRUE') {
                            $('#tblproductsaletransaction-rate').val(obj.sale_rate);
                            $('#tblproductsaletransaction-x_col1').val(obj.sale_rate);
                            $('#tblproductsaletransaction-unit_code').val(obj.unit_code);
                            $('#tblproductsaletransaction-unit_code').trigger('change');
                            $('#tblproductsaletransaction-unit_code').trigger('select2:select');
                        }
                    }
                },
                error:function(data){

                }
            });
        } 
    
    }
    $('#tblproductsale-payment_mode').on('change', function(){
        if($('#tblproductsale-payment_mode').val() == 1 && $('#tblproductsale-customer_type').val() != 'PARTY') {
            setAvailableCredit();
        }
    });
    function setAvailableCredit(){
        var type = $('#tblproductsale-customer_type').val();
        var date = $('#tblproductsale-invoice_date').val();
           var union = $('#tblproductsale-union_code').val();
           var bmc =   $('#tblproductsale-bmc_code').val();
           var pay_mode=$('#tblproductsale-payment_mode').val();
           var amount_due=$('#tblproductsale-amount_due').val();
           var noi=$('#tblproductsale-no_of_installment').val();
           var code = $('#tblproductsale-customer_code').val();


            //        var code ='';
            //        if(type=='Member'){
            //            var code = $('#tblproductsale-dcs_code').val();
            //        }else{
            //            var code = $('#tblproductsale-bmc_code').val();
            //        }

        if(setData(date) && setData(type) && setData(code)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['set-available-credit']) . "',
                data: {'date':date,'type':type,'code':code,'union':union,'bmc':bmc,'pay_mode':pay_mode,'amount_due':amount_due,'noi':noi},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblproductsale-avl_credit').val(obj.credit);
                    } else {
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Payment Cycle aplicability not available for Sale Date.') . "</span></div></div>', function(result){
                            setTimeout(function(){
                                $('#tblproductsale-ex_code').focus();
                            },100);
                        });                         
                    }
                },
                error:function(data){

                }
            });
        }
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }  
    
    function setDeductionStartDate()
    {
        var invoice_date=$('#tblproductsale-invoice_date').val();
        $('#tblproductsale-deduction_start_date').kvDatepicker({
                        format: 'dd-mm-yyyy', // Set your desired date format
                        todayHighlight: true,
                        autoclose: true,
                        startDate: invoice_date
                    });
                   
    }
    
    function setTax() {
        var productCode=$('#tblproductsaletransaction-product_code').val();
        var unionCode = $('#tblproductsale-union_code').val();
        if(setData(productCode)&& setData(unionCode)){
           $.ajax({
                type: 'POST',
                url: '" . Url::to(['get-tax']) . "',     
                data: 'productCode='+productCode+'&unionCode='+unionCode,
                success: function(data)
                {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        if(setData(obj1.tax_code)){
                            $('#tblproductsaletransaction-tax_code').val(obj1.tax_code).trigger('change').trigger('select2:select');
                        }
                    }
                }
            });
        }
    }
";
$this->registerJs($script, View::POS_END, 'create-product-sale-form');
?>
