<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
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
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
    $('#tblgrn-grn_date').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
    $('#tblgrn-invoice_date').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
   
    $('#tblgrn-remarks').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
    $('#tblgrn-invoice_no').on('change', function(){
        var ref_no = $('#tblgrn-ref_no').val();
        if(setData(ref_no)){
            reloadGrid(ref_no);
        }
    });
   
    
    $(document).on('change','span.received_qty_change input', function() { 
        $('tr').removeClass('changeTr');
        var tr_key = $(this).closest('tr').addClass('changeTr');
        var received_qty = parseFloat($('tr.changeTr .received_qty_change input').val());
        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
        if(!setData(received_qty)){
           bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Received Qty is More than 0.</span></div></div>');
            $('tr.changeTr .received_qty_change input').focus();
        }
        if(received_qty < rejected_qty){
        
            bootbox.confirm({
                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>',
                buttons: {
                    'confirm': {
                                    label: 'Ok',
                                    className: 'btn btn-primary'
                     }
                },
                callback: function(result) {
                    console.log('asdasd');
                    $('tr.changeTr .received_qty_change input').focus();
                }
            });
//            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>');
            
        }else{
            amount();
        }
    });
    $(document).on('change','span.rejected_qty_change input', function() { 
        $('tr').removeClass('changeTr');
        var tr_key = $(this).closest('tr').addClass('changeTr');
        var received_qty = parseFloat($('tr.changeTr .received_qty_change input').val());
        var rejected_qty = parseFloat($('tr.changeTr .rejected_qty_change input').val());
        if(received_qty < rejected_qty){
            bootbox.confirm({
                message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>',
                buttons: {
                    'confirm': {
                                    label: 'Ok',
                                    className: 'btn btn-primary'
                     }
                },
                callback: function(result) {
                    $('tr.changeTr .rejected_qty_change input').focus();
                }
            });
//            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Amount is More than 0.</span></div></div>');
//            $('tr.changeTr .rejected_qty_change input').focus();
        }else{
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
        amount = rate * (received_qty - rejected_qty);
         console.log(amount);
        $('tr.changeTr  input.amountField').val(amount.toFixed(2));
    }

    $(document).on('click', '#update', function(e) {
        e.preventDefault();
        $('#product-wise-detail').submit();
    }); 
    
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

