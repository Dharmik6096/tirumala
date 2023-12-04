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
$script = "
    gridChange();
    $(document).on('change', '#tblmilkcollection-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblmilkcollection-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblmilkcollection-bmc_code', function() { 
         gridChange();
    });
    $(document).on('change', '#tblmilkcollection-dcs_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblmilkcollection-date_time_of_collection', function() {  
        gridChange();
    });
    $(document).on('change', '#tblmilkcollection-shift_code', function() {  
        gridChange();
    });
    
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#milk-collection-from .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tblmilkcollection-plant_code').val();
        var bmc = $('#tblmilkcollection-mcc_plant_code').val();
        var mcc = $('#tblmilkcollection-bmc_code').val();
        var dcs = $('#tblmilkcollection-dcs_code').val();
        var date = $('#tblmilkcollection-date_time_of_collection').val();
        var shift = $('#tblmilkcollection-shift_code').val();
        if(setData(plant) && setData(mcc) && setData(bmc) && setData(dcs) && setData(date) && setData(shift)){
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
    $('#tblmilkcollection-rtpl').change(function(){
        amount();
    });
    $('#tblmilkcollection-qty').change(function(){
        amount();
    });
    function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblmilkcollection-rtpl').val());
        var qty = parseFloat($('#tblmilkcollection-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblmilkcollection-amount').val(amount.toFixed(2));
    }
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/collection/tbl-milk-collection/list-grid']) . "'+ '?' + $('#milk-collection-from').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#tblmilkcollection-member_code').focus();
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    

      $('#tblmilkcollection-snf').change(function(){
        calculateClr();
    });
    
     $('#tblmilkcollection-fat').change(function(){
        checkFatRange();
        calculateClr();
    });
    
    function calculateClr(){
        var union = $('#tblmilkcollection-union_code').val();
        var fat = $('#tblmilkcollection-fat').val();
        var snf = $('#tblmilkcollection-snf').val();
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblmilkcollection-clr').val(obj.data.toFixed(2));
                            $('#tblmilkcollection-clr').trigger('change');
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
        var dcs = $('#tblmilkcollection-dcs_code').val();
        var milk_type = $('#tblmilkcollection-milk_type_code').val();
        var dt_date = $('#tblmilkcollection-date_time_of_collection').val();
        var shift = $('#tblmilkcollection-shift_code').val();
        var fat = $('#tblmilkcollection-fat').val();
        var snf = $('#tblmilkcollection-snf').val();
        var milk_quality_type = $('#tblmilkcollection-milk_quality_type_code').val();

        var member = $('#tblmilkcollection-member_code').val();
        var member_code = dcs.concat(member);
        if(dcs != '' && milk_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && milk_quality_type != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift_code':shift,'fat':fat,'snf':snf,'member':member_code},
                success: function(data) {   
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        var rtpl = parseFloat(obj.data.list.rtpl);
                        $('#tblmilkcollection-actual_rate').val(rtpl.toFixed(2));
                        if(obj.data.list.scheme_rate_rtpl != '' && obj.data.list.scheme_rate_rtpl != null){
                            var scheme_rate_rtpl = parseFloat(obj.data.list.scheme_rate_rtpl);
                            rtpl = rtpl + scheme_rate_rtpl;
                            $('#tblmilkcollection-scheme_rate').val(scheme_rate_rtpl);
                            $('#tblmilkcollection-scheme_rate_code').val(obj.data.list.scheme_rate_code);
                        }
                        $('#tblmilkcollection-rtpl').val(rtpl);
                        $('#tblmilkcollection-purchase_rate_code').val(obj.data.list.purchase_rate_code);
                        $('#tblmilkcollection-rtpl').trigger('change');
                    } else {
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                        $('#tblmilkcollection-rtpl').val('');
                        $('#tblmilkcollection-scheme_rate').val('');
                        $('#tblmilkcollection-scheme_rate_code').val('');
                        $('#tblmilkcollection-actual_rate').val('');
                        $('#tblmilkcollection-rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblmilkcollection-rtpl').val('');
            $('#tblmilkcollection-rate_code').val('');
        }
    }
    $(document).ready(function () {
        milkQualityType();
    });
    $(document).on('change', '#tblmilkcollection-union_code', function() {  
          milkQualityType();
    });

    function milkQualityType(){
        var union = $('#tblmilkcollection-union_code').val();
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
                            $('#tblmilkcollection-milk_quality_type_code').val(1);
                        }
                      }else{
                           $('.milk_quality_type_div').hide();
                           $('#tblmilkcollection-milk_quality_type_code').val(1);
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('.milk_quality_type_div').hide();
            $('#tblmilkcollection-milk_quality_type_code').val(1);
        }
    }


    
    $('#tblmilkcollection-milk_type_code').change(function(){
        checkFatRange();
    });
    
    function checkFatRange(){
        var union = $('#tblmilkcollection-union_code').val();
        var fat = $('#tblmilkcollection-fat').val();
        var bmc = $('#tblmilkcollection-bmc_code').val();
        var milk_type = $('#tblmilkcollection-milk_type_code').val();
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
                            $('#tblmilkcollection-milk_type_code').val(obj.data);
                            $('#tblmilkcollection-milk_type_code').trigger('change');
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