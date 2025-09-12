<?php

use yii\web\View;
use yii\helpers\Url;
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
$script = "
    gridChange();
    $(document).on('change', '#tblsamplemilkcollection-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblsamplemilkcollection-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblsamplemilkcollection-bmc_code', function() { 
         gridChange();
    });
    $(document).on('change', '#tblsamplemilkcollection-date_time_of_collection', function() {  
        gridChange();
    });
    $(document).on('change', '#tblsamplemilkcollection-shift_code', function() {  
        gridChange();
    });
    
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#sample-milk-collection-from .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tblsamplemilkcollection-plant_code').val();
        var bmc = $('#tblsamplemilkcollection-mcc_plant_code').val();
        var mcc = $('#tblsamplemilkcollection-bmc_code').val();
        var date = $('#tblsamplemilkcollection-date_time_of_collection').val();
        var shift = $('#tblsamplemilkcollection-shift_code').val();
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
    $('#tblsamplemilkcollection-rtpl').change(function(){
        amount();
    });
    $('#tblsamplemilkcollection-qty').change(function(){
        amount();
    });
    function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblsamplemilkcollection-rtpl').val());
        var qty = parseFloat($('#tblsamplemilkcollection-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblsamplemilkcollection-amount').val(amount.toFixed(2));
    }
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/collection/tbl-sample-milk-collection/list-grid']) . "'+ '?' + $('#sample-milk-collection-from').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#tblsamplemilkcollection-member_code').focus();
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    

      $('#tblsamplemilkcollection-snf').change(function(){
        calculateClr();
    });
    
     $('#tblsamplemilkcollection-fat').change(function(){
        checkFatRange();
        calculateClr();
    });
    
    function calculateClr(){
        var union = $('#tblsamplemilkcollection-union_code').val();
        var fat = $('#tblsamplemilkcollection-fat').val();
        var snf = $('#tblsamplemilkcollection-snf').val();
        var bmcCode = $('#tblsamplemilkcollection-bmc_code').val();
        var dcsCode = $('#tblsamplemilkcollection-dcs_code').val();
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf,'bmcCode':bmcCode,'dcsCode':dcsCode},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblsamplemilkcollection-clr').val(obj.data.toFixed(2));
                            $('#tblsamplemilkcollection-clr').trigger('change');
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
        var dcs = $('#tblsamplemilkcollection-dcs_code').val();
        var milk_type = $('#tblsamplemilkcollection-milk_type_code').val();
        var dt_date = $('#tblsamplemilkcollection-date_time_of_collection').val();
        var shift = $('#tblsamplemilkcollection-shift_code').val();
        var fat = $('#tblsamplemilkcollection-fat').val();
        var snf = $('#tblsamplemilkcollection-snf').val();
        var milk_quality_type = $('#tblsamplemilkcollection-milk_quality_type_code').val();
        
        if(dcs != '' && milk_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && milk_quality_type != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift_code':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                    console.log(obj.data);
                        var rtpl = parseFloat(obj.data.list.rtpl);
                        $('#tblsamplemilkcollection-rtpl').val(rtpl);
                        $('#tblsamplemilkcollection-purchase_rate_code').val(obj.data.list.purchase_rate_code);
                        $('#tblsamplemilkcollection-rtpl').trigger('change');
                    } else {
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                        $('#tblsamplemilkcollection-rtpl').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblsamplemilkcollection-rtpl').val('');
        }
    }
    $(document).ready(function () {
        milkQualityType();
    });
    $(document).on('change', '#tblsamplemilkcollection-union_code', function() {  
          milkQualityType();
    });

    function milkQualityType(){
        var union = $('#tblsamplemilkcollection-union_code').val();
        if(union != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['qlty-type-config']) . "',
                data: {'union':union},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                        if(obj.config==1){
                            $('.milk_quality_type_div').show();
                        }else{
                            $('.milk_quality_type_div').hide();
                            $('#tblsamplemilkcollection-milk_quality_type_code').val(1);
                        }
                      }else{
                           $('.milk_quality_type_div').hide();
                           $('#tblsamplemilkcollection-milk_quality_type_code').val(1);
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('.milk_quality_type_div').hide();
            $('#tblsamplemilkcollection-milk_quality_type_code').val(1);
        }
    }


    
    $('#tblsamplemilkcollection-milk_type_code').change(function(){
        checkFatRange();
    });
    
    function checkFatRange(){
        var union = $('#tblsamplemilkcollection-union_code').val();
        var fat = $('#tblsamplemilkcollection-fat').val();
        var bmc = $('#tblsamplemilkcollection-bmc_code').val();
        var milk_type = $('#tblsamplemilkcollection-milk_type_code').val();
            if(union !='' && fat !='' && milk_type !='' && bmc!=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['check-fat-range']) . "',
                    data: {'union_code':union,'fat':fat,'milk_type':milk_type,'bmc':bmc},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+obj.msg+'</span></div></div>');
                            $('#tblsamplemilkcollection-milk_type_code').val(obj.data);
                            $('#tblsamplemilkcollection-milk_type_code').trigger('change');
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