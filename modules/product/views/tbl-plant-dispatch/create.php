<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::$app->label->title('create', 'Plant Dispatch');
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$grnWithoutStockEntry = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'grn_without_stock_entry', 'PORTAL');
Yii::$app->disable->getDisableFields($txModel);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'txModel' => $txModel,
        ])
        ?>
    </div>
</div>
<?php
$script = "
    var batchNoWiseInventory = '" . $batchNoWiseInventory . "';
    var grnWithoutStockEntry = '" . $grnWithoutStockEntry . "';
    $('#tblplantdispatch-dispatch_date').on('change', function(){
        addBtnEnable();
    });
    $('#tblassettransaction-plant_code').on('change', function(){
        addBtnEnable();
    });
    $('#tblplantdispatch-mcc_plant_code').on('change', function(){
        addBtnEnable();
    });
    $('#tblplantdispatch-document_no').on('change', function(){
        checkUniqueDocNo();
        addBtnEnable();
    });
    $('#tblplantdispatch-document_date').on('change', function(){
        addBtnEnable();
    });
    $('#tblplantdispatchtxn-product_code').on('change', function(){
        setUnit();
        addBtnEnable();
    });
    $('#tblplantdispatchtxn-unit_code').on('change', function(){
        addBtnEnable();
    });
    $('#tblplantdispatchtxn-rate').on('change', function(){
        addBtnEnable();
    });
    $('#tblplantdispatchtxn-qty').on('change', function(){
        addBtnEnable();
    });
   
    $('#tblplantdispatchtxn-amount').on('change', function(){
        addBtnEnable();
    });
    
    $('#tblplantdispatchtxn-sap_batch_no').on('change', function(){
        // checkPlantBatchNoExist();
        addBtnEnable();
    });

    $('#tblplantdispatchtxn-product_mrp').on('change', function(){
        addBtnEnable();
    });

    $('#tblplantdispatchtxn-distributor_landing_rate').on('change', function(){
        addBtnEnable();
    });

    $('#tblplantdispatchtxn-sachiv_price').on('change', function(){
        addBtnEnable();
    });

    $('#tblplantdispatchtxn-member_price').on('change', function(){
        addBtnEnable();
    });
   
    function setUnit(){
        var product = $('#tblplantdispatchtxn-product_code').val();
         if(setData(product)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-unit']) . "',
                    data: {'product':product},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblplantdispatchtxn-unit_code').val(obj.unit);
                            $('#tblplantdispatchtxn-unit_code').trigger('select2:select');
                            $('#tblplantdispatchtxn-unit_code').trigger('change');
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
    

    $('#tblplantdispatchtxn-qty').on('change', function(){
        setBasicAmount();
    });
    $('#tblplantdispatchtxn-rate').on('change', function(){
        setBasicAmount();
    });

    function setBasicAmount(){
        var amount = 0;
        var qty = parseFloat($('#tblplantdispatchtxn-qty').val());
        var rate = parseFloat($('#tblplantdispatchtxn-rate').val());
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        if(rate == '' || isNaN(rate)){
            rate = 0;
        }
        amount = qty * rate;
        $('#tblplantdispatchtxn-amount').val(amount.toFixed(2));
        $('#tblplantdispatchtxn-amount').trigger('change');
    }


    function addBtnEnable(){
        var dispatch_date = $('#tblplantdispatch-dispatch_date').val();
        var plant_code = $('#tblplantdispatch-plant_code').val();
        var mcc_plant_code = $('#tblplantdispatch-mcc_plant_code').val();
        var document_no = $('#tblplantdispatch-document_no').val();
        var document_date = $('#tblplantdispatch-document_date').val();
        var product_code = $('#tblplantdispatchtxn-product_code').val();
        var unit_code = $('#tblplantdispatchtxn-unit_code').val();
        var rate = $('#tblplantdispatchtxn-rate').val();
        var qty = $('#tblplantdispatchtxn-qty').val();
        var amount = $('#tblplantdispatchtxn-amount').val();
        var sap_batch_no = $('#tblplantdispatchtxn-sap_batch_no').val();
        var product_mrp = $('#tblplantdispatchtxn-product_mrp').val();
        var distributor_landing_rate = $('#tblplantdispatchtxn-distributor_landing_rate').val();
        var sachiv_price = $('#tblplantdispatchtxn-sachiv_price').val();
        var member_price = $('#tblplantdispatchtxn-member_price').val();
        var commonFields = (dispatch_date != '' && plant_code != '' && mcc_plant_code != '' && product_code != '' && unit_code != '' && qty != '' && rate != '' && amount != '');
        var isClientFieldsMandatory = !$('.field-tblplantdispatchtxn-distributor_landing_rate').hasClass('disabled');
        var clientFieldsValidate = false;
        if(isClientFieldsMandatory) {
            var clientFields = (product_mrp != '' && distributor_landing_rate != '' && sachiv_price != '' && member_price != '');
            if(commonFields && clientFields) {
                clientFieldsValidate = true;
            }
        } else {
            if(commonFields) {
                clientFieldsValidate = true;
            }
        }

        if(clientFieldsValidate) {
            if(batchNoWiseInventory == '1' && (grnWithoutStockEntry == '1' || sap_batch_no != '')) {
                $('.add-asset-record').removeClass('disabled no_pointer');
            }
            else if(batchNoWiseInventory == '0') {
                 $('.add-asset-record').removeClass('disabled no_pointer');
            }
        } else {
            $('.add-asset-record').addClass('disabled no_pointer');
        }
    }
    
    $('.add-asset-record').on('click', function(){
        var product_code = $('#tblplantdispatchtxn-product_code').val();
        var unit_code = $('#tblplantdispatchtxn-unit_code').val();
        var productName = $('#tblplantdispatchtxn-product_code option:selected').text();
        var unitName = $('#tblplantdispatchtxn-unit_code option:selected').text();        
        var rate = $('#tblplantdispatchtxn-rate').val();
        var qty = $('#tblplantdispatchtxn-qty').val();
        var amount = $('#tblplantdispatchtxn-amount').val();
        var sap_batch_no = $('#tblplantdispatchtxn-sap_batch_no').val();
        var lr_no = $('#tblplantdispatchtxn-lr_no').val();
        var product_mrp = $('#tblplantdispatchtxn-product_mrp').val();
        var distributor_landing_rate = $('#tblplantdispatchtxn-distributor_landing_rate').val();
        var sachiv_price = $('#tblplantdispatchtxn-sachiv_price').val();
        var member_price = $('#tblplantdispatchtxn-member_price').val();

        if(product_code != ''){
            var existData = $('.selected_'+product_code).not('.edit_product').text().length;
            if(parseInt(existData) > 0){
                var msg = '" . Yii::t('app', 'Product already added') . "';
                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                return false;
            }
        }
        var flag=true;
        if(batchNoWiseInventory == '1' && (grnWithoutStockEntry == '1' || sap_batch_no != '')){
            $('.added_sap_batch_no').each(function (index, field){
                if(field.value == sap_batch_no){
                    var msg = '" . Yii::t('app', 'SAP batch no already exists for another product') . "';
                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                    flag=false;
                }
            });       
        }
        if(product_code != '' && qty != '' && flag){
            var add_row = '';
            var add_class = 'test';
            add_class = 'disabled';
            add_row += '<tr class=\"selected_'+product_code+'\">';
            add_row += '<td>' + product_code + '<input type=\"hidden\" class=\"added_product_code\" value=\"'+product_code+'\" name=\"TblPlantDispatchTxn['+product_code+'][product_code]\" ></td>';
            add_row += '<td class=\"product_name\">' + productName + '</td>';
            add_row += '<td class=\"unit_name\">' + unitName + '<input type=\"hidden\" class=\"added_unit_code\" value=\"'+unit_code+'\" name=\"TblPlantDispatchTxn['+product_code+'][unit_code]\" ></td>';
            if(batchNoWiseInventory == '1') {
                add_row += '<td>' + sap_batch_no + '<input type=\"hidden\" class=\"added_sap_batch_no\" value=\"'+sap_batch_no+'\" name=\"TblPlantDispatchTxn['+product_code+'][sap_batch_no]\" ></td>';
            }
            add_row += '<td>' + rate + '<input type=\"hidden\" class=\"added_rate\" value=\"'+rate+'\" name=\"TblPlantDispatchTxn['+product_code+'][rate]\" ></td>';
            add_row += '<td>' + qty + '<input type=\"hidden\" class=\"added_qty\" value=\"'+qty+'\" name=\"TblPlantDispatchTxn['+product_code+'][qty]\" ></td>';
            add_row += '<td>' + amount + '<input type=\"hidden\" class=\"added_amount\" value=\"'+amount+'\" name=\"TblPlantDispatchTxn['+product_code+'][amount]\" ></td>';
            add_row += '<td>' + lr_no + '<input type=\"hidden\" class=\"added_lr_no\" value=\"'+lr_no+'\" name=\"TblPlantDispatchTxn['+product_code+'][lr_no]\" ></td>';
            add_row += '<td>' + product_mrp + '<input type=\"hidden\" class=\"added_product_mrp\" value=\"'+product_mrp+'\" name=\"TblPlantDispatchTxn['+product_code+'][product_mrp]\" ></td>';
            add_row += '<td>' + distributor_landing_rate + '<input type=\"hidden\" class=\"added_distributor_landing_rate\" value=\"'+distributor_landing_rate+'\" name=\"TblPlantDispatchTxn['+product_code+'][distributor_landing_rate]\" ></td>';
            add_row += '<td>' + sachiv_price + '<input type=\"hidden\" class=\"added_sachiv_price\" value=\"'+sachiv_price+'\" name=\"TblPlantDispatchTxn['+product_code+'][sachiv_price]\" ></td>';
            add_row += '<td>' + member_price + '<input type=\"hidden\" class=\"added_member_price\" value=\"'+member_price+'\" name=\"TblPlantDispatchTxn['+product_code+'][member_price]\" ></td>';
            add_row += '<td><a href=\'javascript:void(0)\' onClick=\'editTransaction(\"'+product_code+'\")\' class=\'edit\' title=\'Edit\'><span class=\"fa fa-pencil\"></span></a><a href=\'javascript:void(0)\' onClick=\'deleteTransaction(\"'+product_code+'\")\' class=\'view ml15\' title=\'Delete\'><span class=\"fa fa-remove\"></span></a></td>';
            add_row += '</tr>';
            $('tbody').append(add_row);
            $('#tblplantdispatchtxn-product_code').val('');
            $('#tblplantdispatchtxn-product_code').trigger('change');
            $('#tblplantdispatchtxn-product_code').trigger('select2:select');
            $('#tblplantdispatchtxn-uniot_code').val('');
            $('#tblplantdispatchtxn-uniot_code').trigger('change');
            $('#tblplantdispatchtxn-uniot_code').trigger('select2:select');
            $('#tblplantdispatchtxn-rate').val('');
            $('#tblplantdispatchtxn-qty').val('');
            $('#tblplantdispatchtxn-amount').val('');
            if(batchNoWiseInventory == '1'){
                $('#tblplantdispatchtxn-sap_batch_no').val('');
            }
            $('#tblplantdispatchtxn-lr_no').val('');
            $('#tblplantdispatchtxn-product_mrp').val('');
            $('#tblplantdispatchtxn-distributor_landing_rate').val('');
            $('#tblplantdispatchtxn-sachiv_price').val('');
            $('#tblplantdispatchtxn-member_price').val('');
            $('tbody tr.edit_product').remove();
            $('.single_entry_area').addClass('disabled no_pointer');
//            $('.sap_batch_no').addClass('disabled no_pointer');
            $('.btn-save-txn').removeClass('disabled no_pointer');
        }
    });

  
    function editTransaction(product_code) {
        $('tbody tr').removeClass('edit_product');
        var select_raw_class = 'selected_'+product_code;
        $('.'+select_raw_class).addClass('edit_product');
        $('#tblplantdispatchtxn-product_code').val($('.'+select_raw_class+' .added_product_code').val());
        $('#tblplantdispatchtxn-product_code').trigger('change');
        $('#tblplantdispatchtxn-product_code').trigger('select2:select');
        $('#tblplantdispatchtxn-unit_code').val($('.'+select_raw_class+' .added_unit_code').val());
        $('#tblplantdispatchtxn-unit_code').trigger('change');
        $('#tblplantdispatchtxn-unit_code').trigger('select2:select');
        $('#tblplantdispatchtxn-sap_batch_no').val($('.'+select_raw_class+' .added_sap_batch_no').val());
        $('#tblplantdispatchtxn-qty').val($('.'+select_raw_class+' .added_qty').val());
        $('#tblplantdispatchtxn-rate').val($('.'+select_raw_class+' .added_rate').val());
        $('#tblplantdispatchtxn-amount').val($('.'+select_raw_class+' .added_amount').val());
        $('#tblplantdispatchtxn-lr_no').val($('.'+select_raw_class+' .added_lr_no').val());
        $('#tblplantdispatchtxn-product_mrp').val($('.'+select_raw_class+' .added_product_mrp').val());
        $('#tblplantdispatchtxn-distributor_landing_rate').val($('.'+select_raw_class+' .added_distributor_landing_rate').val());
        $('#tblplantdispatchtxn-sachiv_price').val($('.'+select_raw_class+' .added_sachiv_price').val());
        $('#tblplantdispatchtxn-member_price').val($('.'+select_raw_class+' .added_member_price').val());
        $('.add-asset-record').removeClass('disabled no_pointer');
    }
    
    function deleteTransaction(product_code) {
        var productName = $('.selected_'+product_code+' .product_name').text();
        bootbox.confirm(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Are you sure you want to remove ') . "\"+productName+\".</span></div></div>\", 
        function(result){                   
         if(result){
                $('.selected_'+product_code).remove();
                var rowCount = $('tbody tr').length;
                if(rowCount == 0){
                    $('.btn-save-txn').addClass('disabled no_pointer');
                }
         }});   
    }
      
    function checkUniqueDocNo(){
        var docNo = $('#tblplantdispatch-document_no').val();
         if(setData(docNo)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['check-unique-doc-no']) . "',
                    data: {'docNo':docNo},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'error')
                        {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Document No. Is Already available.') . "</span></div></div>', function(result){
                            setTimeout(function(){
                                $('#tblplantdispatch-document_no').focus();},100);
                            }); 
                            $('#tblplantdispatch-document_no').val('');
                                   }
                               },
                    error:function(data){

                    }
                });
        } 
        
    }
    // function checkPlantBatchNoExist() {
    //     var sapNo = $('#tblplantdispatchtxn-sap_batch_no').val();

    //     if (setData(sapNo)) {
    //         $.ajax({
    //             type: 'post',
    //             url: '" . Url::to(['check-unique-sap-no']) . "',
    //             data: {'sapNo': sapNo},
    //             success: function(data) {
    //                 var obj = $.parseJSON(data);
    //                 if (obj.status == 'error') {
    //                     bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Sap No. Is Already available.') . "</span></div></div>', function(result) {
    //                         setTimeout(function() {
    //                             $('#tblplantdispatchtxn-sap_batch_no').focus();
    //                         }, 100);
    //                     });
    //                     $('#tblplantdispatchtxn-sap_batch_no').val('');
    //                 }
    //             },
    //             error: function(data) {
    //                 // Handle error if needed
    //             }
    //         });
    //     }
    // }
";
$this->registerJs($script, View::POS_END, 'create-plant-dispatch');
?>
