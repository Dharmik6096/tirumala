

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
        if($(this).val()=='MCC'){
            $('#from_mcc').show();
            $('#from_bmc').hide(); 
            $('#from_dcs').hide(); 
            $('#tblinventorytransfer-from_mcc_plant_code').on('change', function(){
                $('#f_code').val($(this).val());
            }) 
        }else if($(this).val()=='BMC'){
            $('#from_mcc').show(); 
            $('#from_bmc').show();
            $('#from_dcs').hide();
            $('#tblinventorytransfer-from_bmc_code').on('change', function(){
                $('#f_code').val($(this).val());
            })
         }else if($(this).val()=='DCS'){
            $('#from_mcc').show(); 
            $('#from_bmc').show();
            $('#from_dcs').show();
            $('#tblinventorytransfer-from_dcs_code').on('change', function(){
                $('#f_code').val($(this).val());
            })
        }
    });
    $('#tblinventorytransfer-to_type').on('change', function(){
        if($(this).val()=='MCC'){
            $('#to_mcc').show();
            $('#to_bmc').hide(); 
            $('#to_dcs').hide();
            $('#tblinventorytransfer-to_mcc_plant_code').on('change', function(){
                $('#t_code').val($(this).val());
            }) 
        }else if($(this).val()=='BMC'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').hide();
            $('#tblinventorytransfer-to_bmc_code').on('change', function(){
                $('#t_code').val($(this).val());
            })
        }else if($(this).val()=='DCS'){
            $('#to_mcc').show(); 
            $('#to_bmc').show();
            $('#to_dcs').show();
            $('#tblinventorytransfer-to_dcs_code').on('change', function(){
                $('#t_code').val($(this).val());
            })
        }
    
    function reloadGrid(){
            var url = '" . Url::to(['/product/tbl-inventory-transfer/list-grid']) . "'+ '?' + $('#inventory-transfer-form').serialize();
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
});

 
";
$this->registerJs($script, View::POS_END, 'serial-no-hide');
?>
