<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'BMC Milk Dispatch - Edit Trip Detail');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'dispatch-trip-edit',
    ]);
    $output = $dataProvider->getModels();
    ?>
    <div id="fixed-table-container" class="static_header_grid dynamic_report_table overflow_auto sticky-footer-panel fixed-table-container">
        <table id="trip_data_grid" class="table table-striped table-input" id="table">
            <?php
            if (!empty($output)) {
                $show_column = ['bmc_milk_dispatch_code', 'bmc_code', 'challan_no', 'transaction_date', 'gross_weight', 'tare_weight', 'vehicle_code', 'current_trip_code', 'trip_code'];
                ?>
                <thead>
                    <tr>
                        <th class="custom_grid_header">#</th>
                        <?php
                        foreach ($output[0] as $att => $value) {
                            if (in_array($att, $show_column)) {
                                $class = '';
                                if ($att == 'bmc_milk_dispatch_code') {
                                    $class = ' disp_none';
                                }
                                ?>
                                <th class="custom_grid_header <?= $class ?>"><?= Yii::t('app', $model->getAttributeLabel($att)) ?></th>
                                <?php
                            }
                        }
                        ?>
                        <th class="custom_grid_header"><?= Yii::t('app', 'Edit') ?></th>
                        <th class="custom_grid_header"><?= Yii::t('app', 'Save') ?></th>

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
                                if (in_array($value_key, $show_column)) {
                                    $class = is_numeric($value) ? 'number_align custom_grid_normal' : 'custom_grid_normal';
                                    $class .= ' ' . $value_key . '-' . $i;
                                    if ($value_key == 'bmc_milk_dispatch_code') {
                                        $class .= ' disp_none';
                                    }
                                    if ($value_key == 'trip_code') {
                                        ?>
                                        <td class="<?= $class ?>"><input type="hidden" id = "dropdown_value" value="<?= $value ?>"><?= $value ?></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td class="<?= $class ?>"><?= $value ?></td>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <td class="custom_grid_normal active_div edit_record" id="edit_id_<?= $i ?>">
                                <i class="fa fa-edit"></i>
                            </td>
                            <td class="custom_grid_normal disable_div save_record" id="save_id_<?= $i ?>">
                                <i class="fa fa-check-square"></i>
                            </td>
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
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = '
    $(".edit_record").click( function(){
        var row_id = $(this).attr("id");
        $("#"+row_id).addClass("disable_div");
        var val = row_id.split("_");
        var row_number = val[2];
        $("#save_id_"+row_number).removeClass("disable_div");
        $("#save_id_"+row_number).addClass("active_div");

        var bmc_milk_dispatch_code_value = $(".bmc_milk_dispatch_code-"+row_number).html();
        var bmc_milk_dispatch_code_append = "<input type=\'hidden\' name=\'bmc_milk_dispatch_code[]\' value=\'"+bmc_milk_dispatch_code_value+"\'> <span>"+bmc_milk_dispatch_code_value+"</span>"
        $(".bmc_milk_dispatch_code-"+row_number).html(bmc_milk_dispatch_code_value);
                     
        var trip_code_value = $(".trip_code-"+row_number+" #dropdown_value").val();
        var data_append_trip_code = "<select class=\'dd_trip_code\' id=\'dd_trip_code-"+row_number+"\' name = \'trip_code["+bmc_milk_dispatch_code_value+"][]\'><option selected=\'true\' disabled=\'disabled\'>Select Trip Code</option>"
        $("#loadercontent").show();
        $("#pageloader").show();
        $.ajax({
            type: "post",
            url: "' . Url::to(['/tankermovement/tbl-bmc-milk-dispatch/get-trip-code']) . '",
            data: {bmc_milk_dispatch_code: bmc_milk_dispatch_code_value},
            success: function(data) {
                var obj1 = data;
                $.each(obj1.res, function(key,value) {
                    data_append_trip_code = data_append_trip_code +"<option value = \'"+key+"\'>"+value+"</option>"
                });
                data_append_trip_code = data_append_trip_code + "</select>";
                setTimeout(function(){
                    $(".trip_code-"+row_number).html(data_append_trip_code);
                    $("#dd_trip_code-"+row_number).val(trip_code_value);
                    $("#dd_trip_code-"+row_number).attr("data-val",trip_code_value);
                    $("#loadercontent").hide();
                    $("#pageloader").hide();
                }, 200);               
            }
        });      
    });
    
 $(".save_record").click( function(){
        var row_id = $(this).attr("id");
        var val = row_id.split("_");
        var row_number = val[2];
        var bmc_milk_dispatch_code_value = $(".bmc_milk_dispatch_code-"+row_number).html();                    
        var trip_code_value = $("#dd_trip_code-"+row_number).val();
        $("#loadercontent").show();
        $("#pageloader").show();
        $.ajax({
            type: "post",
            url: "' . Url::to(['/tankermovement/tbl-bmc-milk-dispatch/change-trip-code']) . '",
            data: {trip_code:trip_code_value,bmc_milk_dispatch_code: bmc_milk_dispatch_code_value},
            success: function(data) {
                var obj = data; 
                $("#loadercontent").hide();
                $("#pageloader").hide();
                if(obj.status=="success"){
                $(".trip_code-"+row_number).html(trip_code_value);
                $(".vehicle_code-"+row_number).html(obj.parsing_no);
                $(".current_trip_code-"+row_number).html(trip_code_value);
                $("#"+row_id).addClass("disable_div");
                $("#edit_id_"+row_number).removeClass("disable_div");
                $("#edit_id_"+row_number).addClass("active_div");
                } else {
                 bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+ obj.msg +"</span></div></div>");
                }                              
            }
        });      
    });  
';
$this->registerJs($script, View::POS_END, 'dispatch-trip-edit-script');
?>
