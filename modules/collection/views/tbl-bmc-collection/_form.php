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
    $this->render('_collection', ['model' => $model, 'type' => 'create',])
    ?>
</div>
<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
        ?>
    </div>
</div>
<?php
$hasBmc = Yii::$app->session->get('hasBMC');
$script = "
    var eiplCode = '" . Yii::$app->session->get('eiplCode') . "';
    gridChange();
    visible();
    $('#tblbmccollection-collection_type').change(function(){
        visible();
    });
    var hasBMC = '" . $hasBmc . "';
    $('#tblbmccollection-bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        var bmc = $('#tblbmccollection-own_bmc_code').val();
        $('#tblbmccollection-bmc_code').val(bmc);
        $('#tblbmccollection-bmc_code').trigger('select2:select');
        $('#tblbmccollection-bmc_code').trigger('change');
        if(hasBMC == 0){
             $('#tblbmccollection-bmc_code').parent('div').parent().hide();  
        }
    });

    $(document).ready(function () {
        pouredBmc();
    });
    $(document).on('change', '#tblbmccollection-union_code', function() {  
          pouredBmc();
    });

    function pouredBmc(){
        var union = $('#tblbmccollection-union_code').val();
        if(union != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['poured-bmc-config']) . "',
                data: {'union':union},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                        if(obj.config==1){
                            $('#tblbmccollection-bmc_code').parent('div').parent().show();
                        }else{
                            $('#tblbmccollection-bmc_code').parent('div').parent().hide();
                        }
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('.milk_quality_type_div').hide();
            $('#tblbmccollection-milk_quality_type_code').val(1);
        }
    }

    function visible(){
        var coll_type = $('#tblbmccollection-collection_type').val();
        if(coll_type == 1 || coll_type == ''){
            $('.transporter').hide();
            $('#tblbmccollection-transporter_code').prop('disabled', true); 
            $('#tblbmccollection-transporter_code').val(''); 
            $('#tblbmccollection-vehicle_code').val(''); 
        }else{
            $('.transporter').show();
            $('#tblbmccollection-transporter_code').prop('disabled', false); 
        }
    }


    $(document).on('change', '#tblbmccollection-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblbmccollection-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblbmccollection-bmc_code', function() { 
         gridChange();
        $('#tblbmccollection-bmc_silos_info_code').val('');
        $('#tblbmccollection-bmc_silos_info_code').trigger('select2:select');
         var bmc = $('#tblbmccollection-bmc_code').val();
        if(setData(bmc)){
        $('#tblbmccollection-bmc_silos_info_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#tblbmccollection-bmc_silos_info_code').val($('#tblbmccollection-bmc_silos_info_code option:nth-child(2)').val());
            $('#tblbmccollection-bmc_silos_info_code').trigger('select2:select');
        });
        } 
    });
    
    $(document).on('change', '#tblbmccollection-date_time_of_collection', function() {  
        gridChange();
    });
    $(document).on('change', '#tblbmccollection-shift_code', function() {  
        gridChange();
    });
//    $(document).on('change', '#tblbmccollection-antibiotic', function() {  
//        if(eiplCode =='PRABHAT'){
//            gridChange();
//        }
//    });
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#bmc-coll-form .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tblbmccollection-plant_code').val();
        var bmc = $('#tblbmccollection-bmc_code').val();
        var mcc = $('#tblbmccollection-mcc_plant_code').val();
        var date = $('#tblbmccollection-date_time_of_collection').val();
        var shift = $('#tblbmccollection-shift_code').val();
//        var antibiotic = $('#tblbmccollection-antibiotic').val();
//         if(eiplCode =='PRABHAT'){
//            if(setData(plant) && setData(mcc) && setData(bmc) && setData(date) && setData(shift) && setData(antibiotic)){
//                $('.add-collection').removeAttr('disabled');
//            } 
//        } else{
            if(setData(plant) && setData(mcc) && setData(bmc) && setData(date) && setData(shift)){
                $('.add-collection').removeAttr('disabled');
            } 
//        }
        
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined){
            return true;
        }else {
            return false;
        }
    }
    
    amount();
    $('#tblbmccollection-rtpl').change(function(){
        amount();
    });
    $('#tblbmccollection-qty').change(function(){
        amount();
//        calculateCan();
    });
   function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblbmccollection-rtpl').val());
        var qty = parseFloat($('#tblbmccollection-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblbmccollection-amount').val(amount.toFixed(2));
    }
    
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
//         $('#tblbmccollection-bmc_code').trigger('change');
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/collection/tbl-bmc-collection/list-grid']) . "'+ '?' + $('#bmc-coll-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#collectionvillage-vlccid').focus();
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
   
    
    $('#tblbmccollection-fat').change(function(){
        calculateClr();
        checkFatRange();
    });
    
    $(document).change('#tblbmccollection-clr,#tblbmccollection-snf',function(){
        calculateClr();
    });

    function calculateClr(){
        var union = $('#tblbmccollection-union_code').val();
        var fat = $('#tblbmccollection-fat').val();
        var snf = $('#tblbmccollection-snf').val();
        var clr = $('#tblbmccollection-clr').val();
        var is_clr_input = $('#is_clr_input').val()
        var bmcCode = $('#tblbmccollection-own_bmc_code').val();

            if((is_clr_input ==0 && fat !='' && snf !='') || (is_clr_input ==1 && fat !='' && clr !='')){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf,'clr':clr,'is_clr_input':is_clr_input,'bmcCode':bmcCode},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            if(is_clr_input==0){
                                $('#tblbmccollection-clr').val(obj.data.toFixed(2));
                            }else{
                                $('#tblbmccollection-snf').val(obj.data.toFixed(2));
                            }
                            rtpl();
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
        var dcs = $('#tblbmccollection-customer_code').val();
        var milk_type = $('#tblbmccollection-milk_type_code').val();
        var milk_quality_type = $('#tblbmccollection-milk_quality_type_code').val();
        var dt_date = $('#tblbmccollection-date_time_of_collection').val();
        var shift = $('#tblbmccollection-shift_code').val();
        var fat = $('#tblbmccollection-fat').val();
        var snf = $('#tblbmccollection-snf').val();
        var clr = $('#tblbmccollection-clr').val();
        var type = $('#tblbmccollection-customer_type').val();
        var union = $('#tblbmccollection-union_code').val();
        var bmc = $('#tblbmccollection-bmc_code').val();
        var qty = $('#tblbmccollection-qty').val();
        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && union != '' && clr != '' && bmc != '' && qty != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift':shift,'fat':fat,'snf':snf,'customer_type':type,'union_code':union,'clr':clr,'bmc_code':bmc,'qty':qty},
                success: function(data) {   
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        var rtpl = parseFloat(obj.data.list.rtpl);
                        $('#tblbmccollection-actual_rate').val(rtpl.toFixed(2));
                        $('#tblbmccollection-scheme_rate').val('');
                        $('#tblbmccollection-scheme_rate_code').val('');
                        if(obj.data.list.scheme_rate_rtpl != '' && obj.data.list.scheme_rate_rtpl != null){
                            var scheme_rate_rtpl = parseFloat(obj.data.list.scheme_rate_rtpl);
                            rtpl = rtpl + scheme_rate_rtpl;
                            $('#tblbmccollection-scheme_rate').val(scheme_rate_rtpl);
                            $('#tblbmccollection-scheme_rate_code').val(obj.data.list.scheme_rate_code);
                        }
                        $('#tblbmccollection-rtpl').val(rtpl);
                        $('#tblbmccollection-rate_code').val(obj.data.list.purchase_rate_code);
                        amount();
                    }else{
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                        $('#tblbmccollection-rtpl').val('');
                        $('#tblbmccollection-rate_code').val('');
                        $('#tblbmccollection-amount').val('');
                        $('#tblbmccollection-scheme_rate').val('');
                        $('#tblbmccollection-scheme_rate_code').val('');
                        $('#tblbmccollection-actual_rate').val('');
                    }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblbmccollection-rtpl').val('');
            $('#tblbmccollection-rate_code').val('');
        }
    }
    $('#tblbmccollection-customer_type').change(function(){
          $('#tblbmccollection-customer_code').val('');
          $('#tblbmccollection-rtpl').val('');
          isClrInput();
    });
     
    function isClrInput(){
        var customer_type = $('#tblbmccollection-customer_type').val();
        var union = $('#tblbmccollection-union_code').val();
            if(customer_type !='' && union != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-clr-input']) . "',
                    data: {'customer_type':customer_type,'union_code':union},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success' && obj.data != null)
                        {
                            var exist_is_clr_input = $('#is_clr_input').val();
                            var is_clr_input = obj.data.is_clr_input;
                            $('#is_clr_input').val(is_clr_input);
                            var snf_html = $('.snf_calculate').html();
                            var crl_html = $('.clr_calculate').html();
                            if(exist_is_clr_input != is_clr_input){
                                $('.snf_calculate').html(crl_html);
                                $('.clr_calculate').html(snf_html);
                            } 
                            $('#tblbmccollection-snf').attr('readonly', true);
                            $('#tblbmccollection-clr').attr('readonly', false);
                            if(is_clr_input==0){
                                $('#tblbmccollection-snf').attr('readonly', false);
                                $('#tblbmccollection-clr').attr('readonly', true);
                            }
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
    
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
     
    $('#tblbmccollection-milk_type_code').change(function(){
        checkFatRange();
    });
    
    function checkFatRange(){
        var union = $('#tblbmccollection-union_code').val();
        var fat = $('#tblbmccollection-fat').val();
        var milk_type = $('#tblbmccollection-milk_type_code').val();
        var bmc = $('#tblbmccollection-bmc_code').val();
            if(union !='' && fat !='' && milk_type !='' && bmc !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['check-fat-range']) . "',
                    data: {'union_code':union,'fat':fat,'milk_type':milk_type,'bmc':bmc},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+obj.msg+'</span></div></div>');
                            $('#tblbmccollection-milk_type_code').val(obj.data);
                            $('#tblbmccollection-milk_type_code').trigger('change');
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
    

";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
