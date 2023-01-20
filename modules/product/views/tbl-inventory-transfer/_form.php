

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
    $this->render('inventory_transfer_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel, 'batchNoWiseInventory' => $batchNoWiseInventory,])
    ?>
</div>
<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel, 'batchNoWiseInventory' => $batchNoWiseInventory,])
        ?>
    </div>
</div>
<?php
$userType = Yii::$app->session->get('UserType');

$script = "
var userType ='$userType';
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
    $(document).ready(function(){
        $('#from_mcc').hide(); 
        $('#from_bmc').hide(); 
        $('#from_dcs').hide(); 
        $('#to_mcc').hide(); 
        $('#to_bmc').hide(); 
        $('#to_dcs').hide();
        if(userType == 5){
            $('#tblinventorytransfer-from_type').val('MCC');
            $('#tblinventorytransfer-from_type').trigger('select2:select');
            $('#tblinventorytransfer-from_type').trigger('change');
            $('.field-tblinventorytransfer-from_type').addClass('disabledDiv');
            
            $('#tblinventorytransfer-to_type').val('DCS');
            $('#tblinventorytransfer-to_type').trigger('select2:select');
            $('#tblinventorytransfer-to_type').trigger('change');
            $('.field-tblinventorytransfer-to_type').addClass('disabledDiv');
        }
    });
    
    $('#tblinventorytransfer-from_mcc_plant_code').on('change', function(){
    if(userType == 5){
        var f_mcc =$('#tblinventorytransfer-from_mcc_plant_code').val();
        if(setData(f_mcc)){
            $('#tblinventorytransfer-to_mcc_plant_code').val(f_mcc);
            $('#tblinventorytransfer-to_mcc_plant_code').trigger('select2:select');
            $('#tblinventorytransfer-to_mcc_plant_code').trigger('change');
            
            $('#tblinventorytransfer-to_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    let varValBMC = $('#tblinventorytransfer-to_bmc_code option:nth-child(2)').val();
                    let notSingleSelection = $('#tblinventorytransfer-to_bmc_code option:nth-child(3)').val();
                   
                    if(varValBMC == undefined) {
                        varValBMC = '';
                    }
                    if(notSingleSelection == undefined) {
                        notSingleSelection = '';
                    }
                    if(notSingleSelection ==''){
                        $('#tblinventorytransfer-to_bmc_code').val(varValBMC);
                        $('#tblinventorytransfer-to_bmc_code').trigger('change');
                        $('#tblinventorytransfer-to_bmc_code').trigger('select2:select');
                    }
                });
        }
    }    
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
            var f_mcc =$('#tblinventorytransfer-from_mcc_plant_code').val();
            var f_bmc =$('#tblinventorytransfer-from_bmc_code').val();
            var f_dcs =$('#tblinventorytransfer-from_dcs_code').val();

        if($(this).val()=='MCC'){
            $('#to_mcc').show();
            $('#to_bmc').hide(); 
            $('#to_dcs').hide();
                if(setData(f_mcc)){
                    $('#tblinventorytransfer-to_mcc_plant_code').val(f_mcc);
                    $('#tblinventorytransfer-to_mcc_plant_code').trigger('select2:select');
                    $('#tblinventorytransfer-to_mcc_plant_code').trigger('change');
                }
        }else if($(this).val()=='BMC'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').hide();
            
            if(setData(f_mcc)){
                $('#tblinventorytransfer-to_mcc_plant_code').val(f_mcc);
                $('#tblinventorytransfer-to_mcc_plant_code').trigger('select2:select');
                $('#tblinventorytransfer-to_mcc_plant_code').trigger('change');
            }
            if(setData(f_bmc)){
                $('#tblinventorytransfer-to_bmc_code').val(f_bmc);
                $('#tblinventorytransfer-to_bmc_code').trigger('select2:select');
                $('#tblinventorytransfer-to_bmc_code').trigger('change');
            }else{
                $('#tblinventorytransfer-to_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    let varValBMC = $('#tblinventorytransfer-to_bmc_code option:nth-child(2)').val();
                    let notSingleSelection = $('#tblinventorytransfer-to_bmc_code option:nth-child(3)').val();
                   
                    if(varValBMC == undefined) {
                        varValBMC = '';
                    }
                    if(notSingleSelection == undefined) {
                        notSingleSelection = '';
                    }
                    if(notSingleSelection ==''){
                        $('#tblinventorytransfer-to_bmc_code').val(varValBMC);
                        $('#tblinventorytransfer-to_bmc_code').trigger('change');
                        $('#tblinventorytransfer-to_bmc_code').trigger('select2:select');
                    }
                });
            }
        }else if($(this).val()=='DCS'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').show();
            
            if(setData(f_mcc)){
                $('#tblinventorytransfer-to_mcc_plant_code').val(f_mcc);
                $('#tblinventorytransfer-to_mcc_plant_code').trigger('select2:select');
                $('#tblinventorytransfer-to_mcc_plant_code').trigger('change');
            }
            if(setData(f_bmc)){
                $('#tblinventorytransfer-to_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    $('#tblinventorytransfer-to_bmc_code').val(f_bmc);
                    $('#tblinventorytransfer-to_bmc_code').trigger('select2:select');
                    $('#tblinventorytransfer-to_bmc_code').trigger('change');
                });
            }else{
                $('#tblinventorytransfer-to_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    let varValBMC = $('#tblinventorytransfer-to_bmc_code option:nth-child(2)').val();
                    let notSingleSelection = $('#tblinventorytransfer-to_bmc_code option:nth-child(3)').val();
                   
                    if(varValBMC == undefined) {
                        varValBMC = '';
                    }
                    if(notSingleSelection == undefined) {
                        notSingleSelection = '';
                    }
                    if(notSingleSelection ==''){
                        $('#tblinventorytransfer-to_bmc_code').val(varValBMC);
                        $('#tblinventorytransfer-to_bmc_code').trigger('change');
                        $('#tblinventorytransfer-to_bmc_code').trigger('select2:select');
                    }    
                });
            }
            if(setData(f_dcs)){
                $('#tblinventorytransfer-to_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    $('#tblinventorytransfer-to_dcs_code').val(f_dcs);
                    $('#tblinventorytransfer-to_dcs_code').trigger('select2:select');
                    $('#tblinventorytransfer-to_dcs_code').trigger('change');
                });
            }
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
    
    $('#tblinventorytransfertxn-sap_batch_no').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        let varVal = $('#tblinventorytransfertxn-sap_batch_no option:nth-child(2)').val();
        if(varVal == undefined) {
            varVal = '';
        }
        $('#tblinventorytransfertxn-sap_batch_no').val(varVal);
        $('#tblinventorytransfertxn-sap_batch_no').trigger('change');
        $('#tblinventorytransfertxn-sap_batch_no').trigger('select2:select');
    });
    $('#tblinventorytransfertxn-sap_batch_no').on('change', function(){
        getAvailableStock();
    });
    function getAvailableStock(){
        var from_type = $('#tblinventorytransfer-from_type').val();
        var from_code = $('#f_code').val();
        var product = $('#tblinventorytransfertxn-product_code').val();
        var union = $('#tblinventorytransfer-union_code').val();
        var batch_no = $('#tblinventorytransfertxn-sap_batch_no').val();
       
         if(setData(from_type) && setData(from_code) && setData(product)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-available-stock']) . "',
                    data: {'product':product,'from_type':from_type,'from_code':from_code,'union_code':union,'batch_no':batch_no},
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
