<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;
?>
<div id="maincontent">
    <?=
    $this->render('_main_form_other', ['model' => $model, 'type' => 'create', 'txModel' => $txModel])
    ?>
</div>
<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid_other', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel, 'model' => $model])
        ?>
    </div>
</div>
<?php
$script = "
    var currentDocDate = null;

    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }
    function reloadGrid(id){
            var postVspDisbData = $('#grn-form-other').serializeArray();
                $.ajax({
                    type: 'get',
                    url: '" . Url::to(['/product/tbl-grn/list-grid-other']) . "',
                    data: postVspDisbData,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
//                        $('#tbldcsmilkdispatch-dcs').focus();
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    
    $('#tblgrn-ref_no').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        setRefData();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
    $('#tblgrn-grn_date,#tblgrn-invoice_date,#tblgrn-remarks,#tblgrn-invoice_no,#tblgrn-payment_mode,#tblgrn-no_of_installment,#tblgrn-deduction_start_date').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });

//    $(document).on('change','span.received_qty_change input', function() { 
//        $('tr').removeClass('changeTr');
//         var tr_key = $(this).closest('tr').addClass('changeTr');
//        var received_qty = parseFloat($('tr.changeTr .received_qty_change input').val());
//        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
//        var dispatch_qty = parseFloat($('tr.changeTr .dispatch_qty_change input').val());
//        if(rejected_qty == '' || isNaN(rejected_qty)){
//            rejected_qty = 0;
//        }
//        if(dispatch_qty == '' || isNaN(dispatch_qty)){
//            dispatch_qty = 0;
//        }
//        if(!setData(received_qty)){
//           bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Received Qty is More than 0.</span></div></div>');
//            $('tr.changeTr .received_qty_change input').focus();
//        }
//        if(received_qty == '' || isNaN(received_qty)){
//            received_qty = 0;
//        }
//        var totalQty = received_qty + rejected_qty;
//        if(totalQty > dispatch_qty){
//           bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Received Qty Not Match Dispatch Qty.</span></div></div>');
//            $('tr.changeTr .received_qty_change input').focus();
//            $('tr.changeTr .received_qty_change input').val('');
//        }else if(received_qty < rejected_qty){
//         
//            bootbox.confirm({
//                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>',
//                buttons: {
//                    'confirm': {
//                                    label: 'Ok',
//                                    className: 'btn btn-primary'
//                     }
//                },
//                callback: function(result) {
//                    console.log('asdasd');
//                    $('tr.changeTr .received_qty_change input').focus();
//                }
//            });
////            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>');
//            
//        }else{
//            amount();
//                var missingQty = dispatch_qty - (received_qty + rejected_qty);
//                $('tr.changeTr .missing_qty_change input').val(missingQty);
//        }
//    });
//    $(document).on('change','span.rejected_qty_change input', function() { 
//        $('tr').removeClass('changeTr');
//        var tr_key = $(this).closest('tr').addClass('changeTr');
//        var received_qty = parseFloat($('tr.changeTr .received_qty_change input').val());
//        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
//        var dispatch_qty = parseFloat($('tr.changeTr .dispatch_qty_change input').val());
//        if(received_qty == '' || isNaN(received_qty)){
//            received_qty = 0;
//        }
//        if(rejected_qty == '' || isNaN(rejected_qty)){
//            rejected_qty = 0;
//        }
//        if(dispatch_qty == '' || isNaN(dispatch_qty)){
//            dispatch_qty = 0;
//        }
//        var totalQty = received_qty + rejected_qty;
//        if(totalQty > dispatch_qty){
//           bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Rejected Qty Not Match Dispatch Qty.</span></div></div>');
//            $('tr.changeTr .rejected_qty_change input').focus();
//            $('tr.changeTr .rejected_qty_change input').val('');
//        }else if(received_qty < rejected_qty){
//            bootbox.confirm({
//                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>',
//                buttons: {
//                    'confirm': {
//                                    label: 'Ok',
//                                    className: 'btn btn-primary'
//                     }
//                },
//                callback: function(result) {
//                    $('tr.changeTr .rejected_qty_change input').focus();
//                }
//            });
////            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>');
////            $('tr.changeTr .rejected_qty_change input').focus();
//        }else{
//            amount();
//                var missingQty = dispatch_qty - (received_qty + rejected_qty);
//                $('tr.changeTr .missing_qty_change input').val(missingQty);
//        }
//    });


    $(document).on('change','span.missing_qty_change input', function() { 
        $('tr').removeClass('changeTr');
        var tr_key = $(this).closest('tr').addClass('changeTr');
        var missing_qty = parseFloat($('tr.changeTr .missing_qty_change input').val());
        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
        var dispatch_qty = parseFloat($('tr.changeTr .dispatch_qty_change input').val());
        if(missing_qty == '' || isNaN(missing_qty)){
            missing_qty = 0;
        }
        if(rejected_qty == '' || isNaN(rejected_qty)){
            rejected_qty = 0;
        }
        if(dispatch_qty == '' || isNaN(dispatch_qty)){
            dispatch_qty = 0;
        }
       
        var receivedQty = dispatch_qty -( rejected_qty + missing_qty);
        if(receivedQty < 0){
           bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Received Qty Must More than 0.</span></div></div>');
            $('tr.changeTr .missing_qty_change input').focus();
            $('tr.changeTr .missing_qty_change input').val('');
        }else{
            $('tr.changeTr .received_qty_change input').val(receivedQty);
            amount();
        }
    });
    $(document).on('change','span.rejected_qty_change input', function() { 
        $('tr').removeClass('changeTr');
        var tr_key = $(this).closest('tr').addClass('changeTr');
        var missing_qty = parseFloat($('tr.changeTr .missing_qty_change input').val());
        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
        var dispatch_qty = parseFloat($('tr.changeTr .dispatch_qty_change input').val());
        if(missing_qty == '' || isNaN(missing_qty)){
            missing_qty = 0;
        }
        if(rejected_qty == '' || isNaN(rejected_qty)){
            rejected_qty = 0;
        }
        if(dispatch_qty == '' || isNaN(dispatch_qty)){
            dispatch_qty = 0;
        }
        var receivedQty = dispatch_qty -( rejected_qty + missing_qty);
        
        if(receivedQty < 0){
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Received Qty Must More than 0.</span></div></div>');
            $('tr.changeTr .rejected_qty_change input').focus();
            $('tr.changeTr .rejected_qty_change input').val('');
        }else{
            $('tr.changeTr .received_qty_change input').val(receivedQty);
            amount(); 
        }
    });
    
    function amount(tr_key = ''){
        var amount = 0;
        var received_qty = parseFloat($('tr.changeTr .received_qty_change input').val());
        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
        var rate = parseFloat($('tr.changeTr  input.rateField').val());
        if(received_qty == '' || isNaN(received_qty)){
            received_qty = 0;
        }
        if(rejected_qty == '' || isNaN(rejected_qty)){
            rejected_qty = 0;
        }
        if(rate == '' || isNaN(rate)){
            rate = 0;
        }
//        amount = rate * (received_qty - rejected_qty);
        amount = rate * (received_qty);
        $('tr.changeTr  input.amountField').val(amount.toFixed(2));
    }

    $(document).on('click', '#update', function(e) {
        e.preventDefault();
        $('#product-wise-detail').submit();
    }); 
    
    function setRefData(){
        var ref_no = $('#tblgrn-ref_no').val();
         if(setData(ref_no)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['set-ref-no-data']) . "',
                    data: {'ref_no':ref_no},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            currentDocDate = obj.document_date;
                            $('#tblgrn-invoice_no').val(obj.document_no);
                            $('#tblgrn-invoice_date').parent().kvDatepicker('update',obj.document_date);
                            $('#tblgrn-invoice_date').parent().kvDatepicker('setStartDate',obj.document_date);
                        }
                    },
                    error:function(data){

                    }
                });
        } 
    
    }

    setNoOfInstallment($('#tblgrn-payment_mode').prop('checked'));	
    $('#tblgrn-payment_mode').on('change', function() {
        setNoOfInstallment($(this).prop('checked'));
    });
    
   function setNoOfInstallment(check_value) {
        $('.noOfInstallment').hide();
        $('.dedStartDate').hide();
        if(check_value == true){
            $('.noOfInstallment').show();
            $('.dedStartDate').show();
        }
    }
    
    $('#grn-form-other').on('beforeValidate', function (e) {
        var invoiceDateStr = $('#tblgrn-invoice_date').val();
        if (currentDocDate && invoiceDateStr) {
            var invParts = invoiceDateStr.match(/(\d+)/g);
            var docParts = currentDocDate.match(/(\d+)/g);
            if (invParts && docParts && invParts.length >= 3 && docParts.length >= 3) {
                var invDate = new Date(invParts[2], invParts[1] - 1, invParts[0]);
                var docDate = new Date(docParts[2], docParts[1] - 1, docParts[0]);
                if (invDate < docDate) {
                    $('#grn-form-other').yiiActiveForm('updateAttribute', 'tblgrn-invoice_date', ['Invoice Date cannot be less than Document Date (' + currentDocDate + ')']);
                    return false;
                }
            }
        }
        return true;
    });

";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

