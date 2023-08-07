<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;

$this->title = Yii::$app->label->title('create', 'Indent Master');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_form_other', ['model' => $model, 'type' => 'create',])
            ?>
        </div>

        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <div class="QltyParamDivGrid">
                <?=
                $this->render('_list_grid_other', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
    gridChange();
    $(document).on('change', '#tblindentmaster-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblindentmaster-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblindentmaster-bmc_code', function() { 
         gridChange();
    });
    $(document).on('change', '#tblindentmaster-indent_date', function() {  
        gridChange();
    });
    $(document).on('change', '#tblindentmaster-indent_type', function() {  
        var indent_type = $('#tblindentmaster-indent_type').val();
        if(indent_type == 'mcc'){
            $('.warehouse_div').hide();
            $('#tblindentmaster-warehouse_code').val('');
            $('#tblindentmaster-warehouse_code').trigger('change');
            $('#tblindentmaster-warehouse_code').trigger('select2:select');
        }else if(indent_type == 'warehouse'){
            $('.warehouse_div').show();
        }
        
    });
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#indent-master-from .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tblindentmaster-plant_code').val();
        var bmc = $('#tblindentmaster-mcc_plant_code').val();
        var mcc = $('#tblindentmaster-bmc_code').val();
        var date = $('#tblindentmaster-indent_date').val();
        if(setData(plant) && setData(mcc) && setData(bmc) && setData(date)){
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
   
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
        $('.QltyParamDiv').show();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/product/tbl-indent-master/list-grid-other']) . "'+ '?' + $('#indent-master-from').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    $(document).on('change','#tblindentmaster-dcs_code',function(){
        setRate();
    });
    $(document).on('change','#tblindentmaster-product_code',function(){
        setRate();
    });
    $(document).on('change','#tblindentmaster-qty',function(){
        setAmount();
    });
    function setRate(){
        $('#tblindentmaster-rate').val('');
        var product_code=$('#tblindentmaster-product_code').val();
        var customer_type= 'DCS';
        var customer_code=$('#tblindentmaster-dcs_code').val();
        var bmc_code=$('#tblindentmaster-bmc_code').val();
        var union_code=$('#tblindentmaster-union_code').val();
        var invoice_date=$('#tblindentmaster-indent_date').val();
        var is_member_rate = 0;
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-product-sale/load-rate']) . "',
            data: {product_code: product_code, is_member_rate: is_member_rate, invoice_date: invoice_date, customer_type: customer_type, customer_code: customer_code, bmc_code: bmc_code, union_code: union_code},
            success: function(data) {
                var d=JSON.parse(data);
                $('#tblindentmaster-rate').val(d.sale_rate);
                setAmount();
            },
            error:function(data){
                    }
        });
    }
    
    function setAmount(){
        var quantity = $('#tblindentmaster-qty').val();
        if(quantity == '' || isNaN(quantity)) {
            quantity = 0;
        }
        var rate = $('#tblindentmaster-rate').val();
        if(rate == '' || isNaN(rate)) {
            rate = 0;
        }
        var amount = parseFloat(quantity) *  parseFloat(rate);
        if(amount == '' || isNaN(amount)) {
            amount = 0;
        }
        $('#tblindentmaster-amount').val(amount);
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>