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
    $this->render('_main_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel])
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
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }
    function reloadGrid(id){
                $.ajax({
                    type: 'get',
                    url: '" . Url::to(['/product/tbl-grn/list-grid']) . "',
                    data: {grn_code: id},
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
    
    $('#tblgrntxn-product_code').on('change', function(){
        setUnit();
    });
    function setUnit(){
        var product = $('#tblgrntxn-product_code').val();
         if(setData(product)){
             $.ajax({
                    type: 'post',
                    url:'" . Url::to(['get-unit']) . "',
                    data: {'product':product},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblgrntxn-unit_code').val(obj.unit);
                            $('#tblgrntxn-unit_code').trigger('select2:select');
                            $('#tblgrntxn-unit_code').trigger('change');
                        }
                    },
                    error:function(data){

                    }
                });
        } 
    
    }
    $('#tblgrntxn-received_qty').on('change', function(){
        setBasicAmount();
    });
    $('#tblgrntxn-rejected_qty').on('change', function(){
        setBasicAmount();
    });
    $('#tblgrntxn-rate').on('change', function(){
        setBasicAmount();
    });
    function setBasicAmount(){
        var amount = 0;
        var rc_qty = parseFloat($('#tblgrntxn-received_qty').val());
        var rj_qty = parseFloat($('#tblgrntxn-rejected_qty').val());
        var rate = parseFloat($('#tblgrntxn-rate').val());
        if(rc_qty == '' || isNaN(rc_qty)){
            rc_qty = 0;
        }
        if(rj_qty == '' || isNaN(rj_qty)){
            rj_qty = 0;
        }
        if(rate == '' || isNaN(rate)){
            rate = 0;
        }
        amount = (rc_qty - rj_qty) * rate;
        $('#tblgrntxn-basic_amount').val(amount.toFixed(2));
        $('#tblgrntxn-basic_amount').trigger('change');
    }
    
    $('#tblgrntxn-basic_amount').on('change', function(){
        setGrossAmount();
    });
    $('#tblgrntxn-tax').on('change', function(){
        setGrossAmount();
    });
    function setGrossAmount(){
        var amount = 0;
        var b_amount = parseFloat($('#tblgrntxn-basic_amount').val());
        var tax = parseFloat($('#tblgrntxn-tax').val());
        if(b_amount == '' || isNaN(b_amount)){
            b_amount = 0;
        }
        if(tax == '' || isNaN(tax)){
            tax = 0;
        }
       
        amount = b_amount + tax;
        $('#tblgrntxn-gross_amount').val(amount.toFixed(2));
    }
    


";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

