<?php

use app\modules\collection\models\TblCollectionPenaltyType;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'TS Loss And Shortage');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'transit-loss-shortage',
    ]);
    // va   r_dump($model);die;
    ?>
    <div id="fixed-table-container" class="dynamic_report_table overflow_auto sticky-footer-panel fixed-table-container">
        <?php echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>

        <table id="recovery_grid" class="table table-striped table-input" id="table">
            <?php
            if (!empty($output)) {
                $show_column = ['mcc_plant_code', 'bmc_code', 'bmc_ref_code', 'bmc_name', 'route_code'];
                ?>
                <thead>
                    <tr>
                        <th class="custom_grid_header">#</th>
                        <?php
                        foreach ($output[0] as $att => $value) {
                            $str = ucwords(str_replace('_', ' ', $att));
                            if (!in_array($att, $show_column)) {
                                $class = '';
                                if ($att == 'vsp_transit_recovery_code') {
                                    $class = ' disp_none';
                                }
                                ?>
                                <th class="custom_grid_header <?= $class ?>"><?= Yii::t('app', $str) ?></th>
                                <?php
                            }
                        }
                        if ($att != 'message') {
                            ?>
                            <th class="custom_grid_header"><?= Yii::t('app', 'Action') ?></th>
                            <?php
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($output as $att => $value_row) {
                        ?>
                        <tr>
                            <td class="custom_grid_normal"><?= ++$i; ?></td>
                            <?php
                            foreach ($value_row as $value_key => $value) {
                                if (!in_array($value_key, $show_column)) {
                                    $class = is_numeric($value) ? 'number_align custom_grid_normal' : 'custom_grid_normal';
                                    $class .= ' ' . $value_key . '-' . $i;
                                    if ($value_key == 'vsp_transit_recovery_code') {
                                        $class .= ' disp_none';
                                    }
                                    if ($value_key == 'ts_loss_responsibility' || $value_key == 'qty_diff_responsibility') {
                                        ?>
                                        <td class="<?= $class ?>"><input type="hidden" id = "dropdown_value" value="<?= $value ?>"><?= Yii::$app->dropdown->getRecords('loss_responsibility')['data'][$value] ?></td>
                                        <?php
                                    } else if ($value_key == 'from_shift') {
                                        ?>
                                        <td class="<?= $class ?>"><input type="hidden" id = "" value="<?= $value ?>">
                                            <?php
                                            echo $value == '1' ? 'M' : 'E';
                                            ?>
                                        </td>
                                        <?php
                                    } else if ($value_key == 'to_shift') {
                                        ?>
                                        <td class="<?= $class ?>"><input type="hidden" id = "" value="<?= $value ?>">
                                            <?php
                                            echo $value == '1' ? 'M' : 'E';
                                            ?>
                                        </td>
                                    <?php } else if ($value_key == 'qty_diff_type') {
                                        ?>
                                        <td class="<?= $class ?>"><input type="hidden" id = "dropdown_value" value="<?= $value ?>">
                                            <?php
                                            $c_penalty_model = new TblCollectionPenaltyType();
                                            $c_penalty_model->penalty_type_code = $value;
                                            echo Yii::$app->general->getforeignkey($c_penalty_model->penaltyType, 'penalty_type');
                                            ?>
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td class="<?= $class ?>"><?= $value ?></td>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php if ($value_key != 'message') { ?>
                                <td class="custom_grid_normal active_div edit_record" id="edit_id_<?= $i ?>"><i class="fa fa-edit"></i></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <?php
            }
            ?>          
        </table>
    </div>



    <div class="panel-footer">
        <?php
        if (!empty($output)) {
            if (isset($output[0]['message'])) {
                echo Html::submitButton(Yii::t('app', 'Unlock'), ['class' => 'btn btn-primary apply-shortcut', 'name' => 'submitBtn', 'value' => 'unlock']);
            } else {
                echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary apply-shortcut', 'name' => 'submitBtn', 'value' => 'save']);
                echo Html::submitButton(Yii::t('app', 'Save & Lock'), ['class' => 'btn btn-primary apply-shortcut', 'name' => 'submitBtn', 'value' => 'save_lock']);
            }
        }
        ?>
        <div onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')"class="btn btn-primary apply-shortcut widget_table_search_btnaa downloadDashboardExcel right_30aa mis_custom_report"><i class="fa fa-file-excel-o"></i></div>
        <?= Yii::$app->controls->custombutton('Cancel', 'transit-loss-shortage'); ?> 

    </div>
    <?php ActiveForm::end(); ?>
</div>
<div id="AppInformation"></div>

<?php
$script = '


    // $("#recovery_grid").find("tr").click( function(){
    $(".edit_record").click( function(){
        var row_id = $(this).attr("id");
        $("#"+row_id).addClass("disable_div");
        var val = row_id.split("_");
        var row_number = val[2];
        
        var vsp_transit_recovery_code_value = $(".vsp_transit_recovery_code-"+row_number).html();
        var vsp_code_append = "<input type=\'hidden\' name=\'vsp_transit_recovery_code[]\' value=\'"+vsp_transit_recovery_code_value+"\'> <span>"+vsp_transit_recovery_code_value+"</span>"
        $(".vsp_transit_recovery_code-"+row_number).html(vsp_code_append);
        
        var ts_deduction_amount_value = $(".ts_deduction_amount-"+row_number).html();
        var ts_deduction_amount_append = "<input type=\'text\' class=\'ts_deduction_amount\' id=\'ts_deduction_amount_input-"+row_number+"\' name=\'ts_deduction_amount["+vsp_transit_recovery_code_value+"][]\' value=\'"+ts_deduction_amount_value+"\'> <span>"+ts_deduction_amount_value+"</span>"
        $(".ts_deduction_amount-"+row_number).html(ts_deduction_amount_append);    
        $("#ts_deduction_amount_input-"+row_number).val(ts_deduction_amount_value);

        var ts_loss_responsibility_value = $(".ts_loss_responsibility-"+row_number+" #dropdown_value").val();
        var data_append = "<select class=\'dd_ts_loss_responsibility\' id=\'dd_ts_loss_responsibility-"+row_number+"\' name = \'ts_loss_responsibility["+vsp_transit_recovery_code_value+"][]\'><option selected=\'true\' disabled=\'disabled\'>Select TS Loss Responsibility</option><option value = \'1\'>Center Incharge</option><option value = \'2\'>Transporter</option><option value = \'3\'>MCC</option></select>"
        $(".ts_loss_responsibility-"+row_number).html(data_append);
        $("#dd_ts_loss_responsibility-"+row_number).val(ts_loss_responsibility_value);


        var qty_diff_type_value = $(".qty_diff_type-"+row_number+" #dropdown_value").val();
        var data_append_qty_diff = "<select class=\'dd_qty_diff_type\' id=\'dd_qty_diff_type-"+row_number+"\' name = \'qty_diff_type["+vsp_transit_recovery_code_value+"][]\'><option selected=\'true\' disabled=\'disabled\'>Select Qty Diff Type</option>"
        var union= "' . $model->union_code . '";
        $.ajax({
            type: "post",
            url: "' . Url::to(['/vsp/transit-recovery/get-penalty-type']) . '",
            data: { union: union},
            success: function(data) {
                var obj1 = data;
                $.each(obj1.res, function(key,value) {
                    data_append_qty_diff = data_append_qty_diff +"<option value = \'"+key+"\'>"+value+"</option>"
                });
                data_append_qty_diff = data_append_qty_diff + "</select>";
                setTimeout(function(){
                    $(".qty_diff_type-"+row_number).html(data_append_qty_diff);
                    $("#dd_qty_diff_type-"+row_number).val(qty_diff_type_value);
                    calculateShortageRecovery(qty_diff_type_value,row_number);
                }, 200);
            },
            error:function(data){
            }
        });

        var qty_diff_responsibility_value = $(".qty_diff_responsibility-"+row_number+" #dropdown_value").val();
        var data_append = "<select class=\'dd_qty_diff_responsibility\' id=\'dd_qty_diff_responsibility-"+row_number+"\' name = \'qty_diff_responsibility["+vsp_transit_recovery_code_value+"][]\'><option selected=\'true\' disabled=\'disabled\'>Select Qty Diff Responsibility</option><option value = \'1\'>Center Incharge</option><option value = \'2\'>Transporter</option><option value = \'2\'>MCC</option></select>"
        $(".qty_diff_responsibility-"+row_number).html(data_append);
        $("#dd_qty_diff_responsibility-"+row_number).val(qty_diff_responsibility_value);
    });

    $(document).on("change", ".dd_qty_diff_responsibility", function(){
        var row_id = $(this).attr("id");
        var val = row_id.split("-");
        var row_number = val[1];
        var dd_value = $("#"+row_id).val();
        calculateInchargeTransport(dd_value,row_number);
    });

    $(document).on("change", ".dd_qty_diff_type", function(){
        var row_id = $(this).attr("id");
        var val = row_id.split("-");
        var row_number = val[1];
        var dd_value = $("#"+row_id).val();
        calculateShortageRecovery(dd_value,row_number);
    });

    $(document).on("change", ".ts_deduction_amount", function(){
        var row_id = $(this).attr("id");
        var val = row_id.split("-");
        var row_number = val[1];
        var id = "dd_qty_diff_responsibility-"+row_number;
        var dd_value = $("#"+id).val();
        calculateInchargeTransport(dd_value,row_number);
    });

    function calculateShortageRecovery(dd_value,row_number){
        var vsp_transit_recovery_code_value = $(".vsp_transit_recovery_code-"+row_number+" input").val();
        var dcs_code_value = $(".dcs_code-"+row_number).html();
        var from_date_value = $(".from_date-"+row_number).html();
        var actual_qty_value = parseFloat($(".actual_qty-"+row_number).html());
        var composite_qty_value = parseFloat($(".composite_qty-"+row_number).html());
        var qty_diff_responsibility_value = $("#dd_qty_diff_responsibility-"+row_number).val();
        if(actual_qty_value - composite_qty_value < 0){
            $.ajax({
                type: "post",
                url: "' . Url::to(['/vsp/transit-recovery/get-penalty-rate']) . '",
                data: { dcs_code: dcs_code_value, from_date: from_date_value, qty_diff_type : dd_value},
                success: function(data) {
                    var shortage_recovery_value = ((actual_qty_value - composite_qty_value) * data.res).toFixed(2);
                    var shortage_recovery_append = "<input type=\'text\' class=\'shortage_recovery_input-"+row_number+"\' name=\'shortage_recovery["+vsp_transit_recovery_code_value+"][]\' value=\'"+shortage_recovery_value+"\'> <span>"+shortage_recovery_value+"</span>"
                    $(".shortage_recovery-"+row_number).html(shortage_recovery_append);
                    setTimeout(function(){
                        calculateInchargeTransport(qty_diff_responsibility_value,row_number);
                    },200);
                },
                error:function(data){
                }
            });
        }

    }

    function calculateInchargeTransport(dd_value,row_number){
        var vsp_transit_recovery_code_value = $(".vsp_transit_recovery_code-"+row_number+" input").val();
        // var ts_deduction_amount = parseFloat($(".ts_deduction_amount-"+row_number).html());
        var ts_deduction_amount = parseFloat($("#ts_deduction_amount_input-"+row_number).val());
        var shortage_recovery_value = 0;
        if(isNaN($(".shortage_recovery_input-"+row_number).val())){
            var shortage_recovery_value = parseFloat($(".shortage_recovery-"+row_number).html());
        }else{
            var shortage_recovery_value = parseFloat($(".shortage_recovery_input-"+row_number).val());
        }
        var diffrence_addition = (ts_deduction_amount + shortage_recovery_value).toFixed(2);
        if(dd_value == 1){
            var total_recovery_incharge_append = "<input type=\'hidden\' class=\'total_recovery_incharge_input-"+row_number+"\' name=\'total_recovery_incharge["+vsp_transit_recovery_code_value+"][]\' value=\'"+diffrence_addition+"\'> <span>"+diffrence_addition+"</span>"
            $(".total_recovery_incharge-"+row_number).html(total_recovery_incharge_append);

            var total_recovery_transporter_append = "<input type=\'hidden\' class=\'total_recovery_transporter_input-"+row_number+"\' name=\'total_recovery_transporter["+vsp_transit_recovery_code_value+"][]\' value=\'0\'> <span>0</span>"
            $(".total_recovery_transporter-"+row_number).html(total_recovery_transporter_append);
        }else{
            var total_recovery_transporter_append = "<input type=\'hidden\' class=\'total_recovery_transporter_input-"+row_number+"\' name=\'total_recovery_transporter["+vsp_transit_recovery_code_value+"][]\' value=\'"+diffrence_addition+"\'> <span>"+diffrence_addition+"</span>"
            $(".total_recovery_transporter-"+row_number).html(total_recovery_transporter_append);

            var total_recovery_incharge_append = "<input type=\'hidden\' class=\'total_recovery_incharge_input-"+row_number+"\' name=\'total_recovery_incharge["+vsp_transit_recovery_code_value+"][]\' value=\'0\'> <span>0</span>"
            $(".total_recovery_incharge-"+row_number).html(total_recovery_incharge_append);
        }
    }
        var fixedTable1 = fixTable(document.getElementById("fixed-table-container"));
';
$this->registerJs($script, View::POS_END, 'transit-loss-shortage');
?>
<?php
$script = "
   $(document).on('click', '.downloadDashboardExcel', function(){
    var idVal = $(this).attr('data-val');
    var titleVal = $(this).attr('data-title');
    fnExcelReport(idVal);
});

function fnExcelReport(idVal, titleVal = 'download')
{
    //bgcolor=\'#87AFC6\'
    var tab_text='<table border=\'2px\'><tr>';
    var textRange; var j=0;
    tab = document.getElementById(idVal); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+'</tr>';
        //tab_text=tab_text+'</tr>';
    }

    tab_text=tab_text+'</table>';
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, '');//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,''); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ''); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE '); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open('txt/html','replace');
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand('SaveAs',true,titleVal+'.xls');
    }  else {
        //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text), '_blank');  
    }
    return (sa);
}
 
";
$this->registerJs($script, View::POS_READY, 'recoverys-excel-download');
?>
