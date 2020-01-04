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
    <?=
    $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
    ?>
</div>

<?php
$script = "
    visible();
    $('#tblbmccollection-collection_type').change(function(){
        visible();
    });
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

    amount();
    $('#tblbmccollection-rtpl').change(function(){
        amount();
    });
    $('#tblbmccollection-qty').change(function(){
        amount();
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
        $('#tblbmccollection-amount').val(amount);
    }

    $('#tblbmccollection-customer_code').change(function(){
        var dcs = $(this).val();
        var type= $('#tblbmccollection-customer_type').val(); 
        var union= $('#tblbmccollection-union_code').val(); 
        $.ajax({
            type: 'post',
            url:'" . Url::to(['validate-dcs']) . "',
            data: {'dcs_code':dcs,'customer_type':type,'union_code':union},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {
                    $('#tblbmccollection-customer_name').val(obj.data); 
                }else{
                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please enter valid Code</span></div></div>');
                        $('#tblbmccollection-customer_code').val('');                    
                        $('#tblbmccollection-customer_name').val('');                    
                        $('#tblbmccollection-customer_code').focus();
                }
            },
            error:function(data){
		
	    }
	});
    });
    $('.rtpl_validate select').change(function(){
        rtpl();
    });
    $('.rtpl_validate input').change(function(){
        rtpl();
    });
//    $('#tblbmccollection-date_time_of_collection').change(function(){
//        $.pjax.reload('#bmc-collection',{data: $('#bmc-form').serialize(),timeout : false});
//    });
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
        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && union != '' && clr != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift':shift,'fat':fat,'snf':snf,'customer_type':type,'union_code':union,'clr':clr},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tblbmccollection-rtpl').val(obj.data.list.rtpl);
                            $('#tblbmccollection-rate_code').val(obj.data.list.purchase_rate_code);
                            amount();
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tblbmccollection-rtpl').val('');
                            $('#tblbmccollection-rate_code').val('');
                            $('#tblbmccollection-amount').val('');
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
    });
    
    $('#tblbmccollection-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        var length = $('#tblbmccollection-customer_type option[value!=\'\']').length;
            if(length == 0) {
                $('.show_hide_customer_type').hide();
            }else if(length == 1) {
                $('#tblbmccollection-customer_type').val('DCS');
                $('.show_hide_customer_type').hide();
            } else {
                $('.show_hide_customer_type').show();
            }
    });
     $(document).on('change', '#tblbmccollection-date_time_of_collection', function() {  
        reloadGrid();
    });
     $(document).on('change', '#tblbmccollection-shift_code', function() {  
        reloadGrid();
    });
     $(document).on('change', '#tblbmccollection-bmc_code', function() {  
        reloadGrid();
    });
    function reloadGrid(){
//        if($('#tblbmccollection-milk_collection_code').val()==''){
            var url = '" . Url::to(['/collection/tbl-bmc-collection/list-grid']) . "'+ '?' + $('#bmc-coll-form').serialize();
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
//        }    
    }
    

    $(document).on('click','.edit-record',function(e){
        var id= $(this).attr('data-val');
        var name = $(this).attr('data-name');
        editbmcCollection(id);
    });
    
    function editbmcCollection(milk_collection_code){
            if(milk_collection_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/collection/tbl-bmc-collection/update-collection']) . "',
                    data: {'milk_collection_code' : milk_collection_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblbmccollection-'+index).val(value);
                        });
                        $('#tblbmccollection-customer_name').val(data.name);
                        $('#tblbmccollection-plant_code').val(data.modelData.plant_code);
                        $('#tblbmccollection-plant_code').trigger('change');
                        $('#tblbmccollection-mcc_plant_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                            $('#tblbmccollection-mcc_plant_code').val(data.modelData.mcc_plant_code);
                            $('#tblbmccollection-mcc_plant_code').trigger('change');
                        });
                        $('#tblbmccollection-bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                            $('#tblbmccollection-bmc_code').val(data.modelData.bmc_code);
                        });
                        $('#tblbmccollection-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                            $('#tblbmccollection-customer_type').val(data.modelData.customer_type);
                        });
                        $('#tblbmccollection-collection_type').trigger('change');
                        $('#tblbmccollection-transporter_code').trigger('change');
                        
                        $('#tblbmccollection-vehicle_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                            $('#tblbmccollection-vehicle_code').val(data.modelData.vehicle_code);
                        });
                        
//                       $('#maincontent').html(data);
                         $('.create_fields').addClass('disabled');
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }

    };
    
    $('#tblbmccollection-snf').change(function(){
        calculateClr();
    });
    
     $('#tblbmccollection-fat').change(function(){
        calculateClr();
    });
    
    function calculateClr(){
        var union = $('#tblbmccollection-union_code').val();
        var fat = $('#tblbmccollection-fat').val();
        var snf = $('#tblbmccollection-snf').val();
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblbmccollection-clr').val(obj.data.toFixed(2));
                            $('#tblbmccollection-clr').trigger('change');
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