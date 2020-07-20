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
    $this->render('_collection', ['model' => $model, 'type' => 'create', 'txModel' => $txModel])
    ?>
</div>
<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel])
        ?>
    </div>
</div>
<?php
$script = "
    gridChange();
   
    $(document).on('change', '#tbldcsmilkdispatch-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tbldcsmilkdispatch-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tbldcsmilkdispatch-bmc_code', function() { 
         gridChange();
    });
    
    $(document).on('change', '#tbldcsmilkdispatch-date_time_of_dispatch', function() {  
        gridChange();
    });
    $(document).on('change', '#tbldcsmilkdispatch-shift_code', function() {  
        gridChange();
    });
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#milk-dispatch-form .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tbldcsmilkdispatch-plant_code').val();
        var bmc = $('#tbldcsmilkdispatch-bmc_code').val();
        var mcc = $('#tbldcsmilkdispatch-mcc_plant_code').val();
        var date = $('#tbldcsmilkdispatch-date_time_of_dispatch').val();
        var shift = $('#tbldcsmilkdispatch-shift_code').val();
        if(setData(plant) && setData(mcc) && setData(bmc) && setData(date) && setData(shift)){
            $('.add-collection').removeAttr('disabled');
        } 
        
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }
    
    amount();
    $('#tbldcsmilkdispatchtxn-rtpl').change(function(){
        amount();
    });
    $('#tbldcsmilkdispatchtxn-dispatch_qty').change(function(){
        amount();
//        calculateCan();
    });
   function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tbldcsmilkdispatchtxn-rtpl').val());
        var qty = parseFloat($('#tbldcsmilkdispatchtxn-dispatch_qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tbldcsmilkdispatchtxn-total_amount').val(amount);
    }
    
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/collection/tbl-dcs-milk-dispatch/list-grid']) . "'+ '?' + $('#milk-dispatch-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#tbldcsmilkdispatch-dcs').focus();
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    

    $('#tbldcsmilkdispatchtxn-avg_snf').change(function(){
        calculateClr();
    });
    
     $('#tbldcsmilkdispatchtxn-avg_fat').change(function(){
        calculateClr();
    });
    
    function calculateClr(){
        var union = $('#tbldcsmilkdispatch-union_code').val();
        var fat = $('#tbldcsmilkdispatchtxn-avg_fat').val();
        var snf = $('#tbldcsmilkdispatchtxn-avg_snf').val();
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tbldcsmilkdispatchtxn-avg_clr').val(obj.data.toFixed(2));
                            $('#tbldcsmilkdispatchtxn-avg_clr').trigger('change');
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
    

    $('.rtpl_validate select').change(function(){
        rtpl();
    });
    $('.rtpl_validate input').change(function(){
        rtpl();
    });
      function rtpl(){
        var dcs = $('#tbldcsmilkdispatch-dcs_code').val();
        var milk_type = $('#tbldcsmilkdispatchtxn-milk_type_code').val();
        var milk_quality_type = $('#tbldcsmilkdispatchtxn-milk_quality_type_code').val();
        var dt_date = $('#tbldcsmilkdispatch-date_time_of_dispatch').val();
        var shift = $('#tbldcsmilkdispatch-shift_code').val();
        var fat = $('#tbldcsmilkdispatchtxn-avg_fat').val();
        var snf = $('#tbldcsmilkdispatchtxn-avg_snf').val();
        var clr = $('#tbldcsmilkdispatchtxn-avg_clr').val();
        var union = $('#tbldcsmilkdispatch-union_code').val();
        var bmc = $('#tbldcsmilkdispatch-bmc_code').val();

        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && union != '' && clr != '' && bmc != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift':shift,'fat':fat,'snf':snf,'union_code':union,'clr':clr,'bmc_code':bmc},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tbldcsmilkdispatchtxn-rtpl').val(obj.data.list.rtpl);
                            $('#tbldcsmilkdispatchtxn-purchase_rate_code').val(obj.data.list.purchase_rate_code);
                            amount();
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tbldcsmilkdispatchtxn-rtpl').val('');
                            $('#tbldcsmilkdispatchtxn-purchase_rate_code').val('');
                            $('#tbldcsmilkdispatchtxn-total_amount').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tbldcsmilkdispatch-rtpl').val('');
            $('#tbldcsmilkdispatch-rate_code').val('');
        }
    }
    $('#tbldcsmilkdispatch-customer_type').change(function(){
          $('#tbldcsmilkdispatch-customer_code').val('');
          $('#tbldcsmilkdispatch-rtpl').val('');
    });
     
    function calculateCan(){
        var bmcid = $('#collectionvillage-bmcid').val();
        var qty = $('#collectionvillage-qty').val();
            if(bmcid !='' && qty !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-can']) . "',
                    data: {'bmcid':bmcid,'qty':qty},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#collectionvillage-can').val(obj.data);
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
    
     $('#tbldcsmilkdispatch-dcs').change(function(){
        var dcs = $(this).val();
        var union= $('#tbldcsmilkdispatch-union_code').val(); 
        var bmc= $('#tbldcsmilkdispatch-bmc_code').val(); 
        var plant= $('#tbldcsmilkdispatch-plant_code').val(); 
        var mcc= $('#tbldcsmilkdispatch-mcc_plant_code').val(); 
        var date= $('#tbldcsmilkdispatch-date_time_of_dispatch').val(); 
        $.ajax({
            type: 'post',
            url:'" . Url::to(['validate-dcs']) . "',
            data: {'dcs_code':dcs,'union_code':union,'bmc_code':bmc,'mcc':mcc,'plant':plant,'date':date},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {
                    $('#tbldcsmilkdispatch-name').val(obj.data); 
                    $('#tbldcsmilkdispatch-dcs_code').val(obj.code); 
                }else{
                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please enter valid Code</span></div></div>');
                        $('#tbldcsmilkdispatch-dcs_code').val('');                    
                        $('#tbldcsmilkdispatch-dcs').val('');                    
                        $('#tbldcsmilkdispatch-name').val('');                    
                        $('#tbldcsmilkdispatch-dcs').focus();
                }
            },
            error:function(data){
		
	    }
	});
    });
   
    

";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
