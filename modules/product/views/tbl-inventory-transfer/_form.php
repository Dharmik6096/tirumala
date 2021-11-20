

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
    $this->render('inventory_transfer_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel])
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
$(document).ready(function(){
    $('#from_mcc').hide(); 
    $('#from_bmc').hide(); 
    $('#from_dcs').hide(); 
    $('#to_mcc').hide(); 
    $('#to_bmc').hide(); 
    $('#to_dcs').hide();
    });
    $('#tblinventorytransfer-from_type').on('change', function(){
            $('#tblinventorytransfer-from_mcc_plant_code').val('');
            $('#tblinventorytransfer-from_mcc_plant_code').trigger('select2:select');
            $('#tblinventorytransfer-from_mcc_plant_code').trigger('change');
            setFromCode();
        if($(this).val()=='MCC'){
            $('#from_mcc').show();
            $('#from_bmc').hide(); 
            $('#from_dcs').hide(); 
        }else if($(this).val()=='BMC'){
            $('#from_mcc').show(); 
            $('#from_bmc').show();
            $('#from_dcs').hide();
        }else if($(this).val()=='DCS'){
            $('#from_mcc').show(); 
            $('#from_bmc').show();
            $('#from_dcs').show();
        }else{
            $('#from_mcc').hide(); 
            $('#from_bmc').hide(); 
            $('#from_dcs').hide(); 
        }
    });
    $('#tblinventorytransfer-from_mcc_plant_code').on('change', function(){
        setFromCode();
    });
    $('#tblinventorytransfer-from_bmc_code').on('change', function(){
        setFromCode();
    });
    $('#tblinventorytransfer-from_dcs_code').on('change', function(){
        setFromCode();
    });
    function setFromCode(){
       var type =$('#tblinventorytransfer-from_type').val();
       var f_mcc =$('#tblinventorytransfer-from_mcc_plant_code').val();
       var f_bmc =$('#tblinventorytransfer-from_bmc_code').val();
       var f_dcs =$('#tblinventorytransfer-from_dcs_code').val();
        if(type =='MCC'){
            $('#f_code').val(f_mcc);
             $('#f_code').trigger('change');
        }else if(type =='BMC'){
            $('#f_code').val(f_bmc);
             $('#f_code').trigger('change');
        }else if(type =='DCS'){
            $('#f_code').val(f_dcs);
             $('#f_code').trigger('change');
        }
    }

    $('#tblinventorytransfer-to_type').on('change', function(){
            $('#tblinventorytransfer-to_mcc_plant_code').val('');
            $('#tblinventorytransfer-to_mcc_plant_code').trigger('select2:select');
            $('#tblinventorytransfer-to_mcc_plant_code').trigger('change');
        if($(this).val()=='MCC'){
            $('#to_mcc').show();
            $('#to_bmc').hide(); 
            $('#to_dcs').hide();
        }else if($(this).val()=='BMC'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').hide();
        }else if($(this).val()=='DCS'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').show();
        }else{
            $('#to_mcc').hide(); 
            $('#to_bmc').hide(); 
            $('#to_dcs').hide();
        }
    });
    
 $('#tblinventorytransfer-to_mcc_plant_code').on('change', function(){
        setToCode();
    });
    $('#tblinventorytransfer-to_bmc_code').on('change', function(){
        setToCode();
    });
    $('#tblinventorytransfer-to_dcs_code').on('change', function(){
        setToCode();
    });
    function setToCode(){
       var t_type =$('#tblinventorytransfer-to_type').val();
       var t_mcc =$('#tblinventorytransfer-to_mcc_plant_code').val();
       var t_bmc =$('#tblinventorytransfer-to_bmc_code').val();
       var t_dcs =$('#tblinventorytransfer-to_dcs_code').val();
      
        if(t_type =='MCC'){
            $('#t_code').val(t_mcc);
        }else if(t_type =='BMC'){
            $('#t_code').val(t_bmc);
        }else if(t_type =='DCS'){
            $('#t_code').val(t_dcs);
        }
    }
    function reloadGrid(id){
             $.ajax({
                    type: 'get',
                    url: '" . Url::to(['/product/tbl-inventory-transfer/list-grid']) . "',
                    data: {inventory_transfer_code: id},
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
    $('#tblinventorytransfertxn-product_code').on('change', function(){
        setUnit();
        getAvailableStock();
    });
    function setUnit(){
        var product = $('#tblinventorytransfertxn-product_code').val();
         if(setData(product)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-unit']) . "',
                    data: {'product':product},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblinventorytransfertxn-unit_code').val(obj.unit);
                            $('#tblinventorytransfertxn-unit_code').trigger('select2:select');
                            $('#tblinventorytransfertxn-unit_code').trigger('change');
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
    $('#tblinventorytransfer-from_type').on('change', function(){
        getAvailableStock();
    });
    $('#f_code').on('change', function(){
        getAvailableStock();
    });
    function getAvailableStock(){
        var from_type = $('#tblinventorytransfer-from_type').val();
        var from_code = $('#f_code').val();
        var product = $('#tblinventorytransfertxn-product_code').val();
        var union = $('#tblinventorytransfer-union_code').val();
       
         if(setData(from_type) && setData(from_code) && setData(product)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-available-stock']) . "',
                    data: {'product':product,'from_type':from_type,'from_code':from_code,'union_code':union},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblinventorytransfertxn-available_stock').val(obj.stock);
                        }
                    },
                    error:function(data){

                    }
                });
        } 
    
    }
";
$this->registerJs($script, View::POS_END, 'serial-no-hide');
?>
