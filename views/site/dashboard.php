<?php
$this->title = 'Dashboard';

use app\models\TblDashboardWidgets;
use Symfony\Component\Console\Input\Input;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$chart_url = Url::to(['load-chart']);
$table_url = Url::to(['load-table']);
$container_url = Url::to(['load-table']);
$results3 = !empty($results3) ? $results3 : [];
if (!empty($results4)) {
    foreach ($results4 as $res) {
        $cal_data[$res['dt']] = [$res['AvgFAT'], $res['AvgSNF'], $res['Qty']];
    }
} else {
    $cal_data = [];
}
$villages = array_column(array_values($results3), 'dcs_name');
$unions = array_column(array_values($results3), 'union_name');
$combined = array_map(function($a, $b) {
    return $a . '<br/>' . $b;
}, $villages, $unions);

$collection = array_column(array_values($results3), 'Qty');

$bmc_dispatch = array_column(array_values($results7), 'quantity');
$d_bmc = array_column(array_values($results7), 'bmc_name');
$fat = array_column(array_values($results3), 'AvgFAT');
$snf = array_column(array_values($results3), 'AvgSNF');
$cal_data = json_encode($cal_data);

$dcs = array_column(array_values($results8), 'VillageName');
$unions = array_column(array_values($results8), 'union_name');
$graph_chart_dcs = array_map(function($a, $b) {
    return $a;
}, $dcs, $unions);
$DPUCount = array_column(array_values($results8), 'DPUCount');
$DPUCount = json_encode($DPUCount);
$CFCount = array_column(array_values($results8), 'CFCount');
$CFCount = json_encode($CFCount);


$milk_collection = !empty($milk_collection) ? $milk_collection : [];
$dcs = array_column(array_values($milk_collection), 'dcs_name');
$unions = array_column(array_values($milk_collection), 'union_name');
$bar_chart_dcs = array_map(function($a, $b) {
    return $a;
}, $dcs, $unions);
$dcs_mcollection = array_column(array_values($milk_collection), 'm_quantity');
$dcs_mcollection = json_encode($dcs_mcollection);
$dcs_ecollection = array_column(array_values($milk_collection), 'e_quantity');
$dcs_ecollection = json_encode($dcs_ecollection);


$bmc_collection = !empty($results6) ? $results6 : [];
$bmc_dcs = array_column(array_values($bmc_collection), 'dcs_name');
$bmc_unions = array_column(array_values($bmc_collection), 'union_name');
$bar_chart_bmc = array_map(function($a, $b) {
    return $a;
}, $bmc_dcs, $bmc_unions);
$bmc_mcollection = array_column(array_values($bmc_collection), 'm_quantity');
$bmc_mcollection = json_encode($bmc_mcollection);
$bmc_ecollection = array_column(array_values($bmc_collection), 'e_quantity');
$bmc_ecollection = json_encode($bmc_ecollection);

$active_member = array_sum(array_map(function($item) {
            return $item['active_member'];
        }, $member_mobile_detail));
$mobile_app_active = array_sum(array_map(function($item) {
            return $item['mobile_app_active'];
        }, $member_mobile_detail));
$mobile_app_block = array_sum(array_map(function($item) {
            return $item['mobile_app_block'];
        }, $member_mobile_detail));

// $refreshWidgets = [
// 'fed_union',
// 'fed_comparison',
// 'fed_datewise',
// 'union_comparison',
// 'union_datewise',
// 'bmc_union_comparison',
// 'bmc_union_datewise',
// 'milk_coll_widget',
// 'BmcWiseCrossTab',
// 'bmc_coll_widget',
// 'bmc_dispatch_widget',
// 'reconciliation_chart_widget',
// 'table_milk_collection',
// 'manual_vs_auto_collection',
// 'dipatch_vs_receipt',
// 'bmc_collection_summary',
// 'monthly_milk_collection',
// 'dashboard_blocks',
// 'piechart_member_app',
// 'calender',
// 'dashboard_farmer_rmrd_blocks',
// 'dashboard_farmer_rmrd_avg',
// 'dashboard_farmer_status'];

$class_cols = "col-sm-3";
$display = "";
$display_rmrd = "disp_none";
$widget_type = !empty($model->widget_type) ? $model->widget_type : '';
if ($widget_type == 'farmer') {
    $class_cols = "col-sm-3";
}
if ($widget_type == 'rmrd') {
    $class_cols = "col-sm-4";
    $display = "disp_none";
    $display_rmrd = "";
}

$farmer_selected_widgets = !empty($userFarmerWidgets) ? $userFarmerWidgets : [];
$farmer_unselected_widgets = array_diff(!empty($farmerWidgets) ? $farmerWidgets : [], $farmer_selected_widgets);
$allFarmerWidgets = array_merge($farmer_selected_widgets, $farmer_unselected_widgets);

$rmrd_selected_widgets = !empty($userRmrdWidgets) ? $userRmrdWidgets : [];
$rmrd_unselected_widgets = array_diff(!empty($rmrdWidgets) ? $rmrdWidgets : [], $rmrd_selected_widgets);
$allRmrdWidgets = array_merge($rmrd_selected_widgets, $rmrd_unselected_widgets);

if ($widget_type == 'farmer')
    $lazy_loading_widgets = json_encode($farmer_selected_widgets);

if ($widget_type == 'rmrd')
    $lazy_loading_widgets = json_encode($rmrd_selected_widgets);

// var_dump($widget_type);
// var_dump($lazy_loading_widgets);die;
$dashboard_widget = new TblDashboardWidgets();
?>
<div class="panel-group row panel-fixed dashboard_set_filter" id="filter">
    <div class="panel panel-default min_h_0">
        <!-- <div class="panel-heading text-center">
            <h4 class="panel-title">
        <?php // Yii::t('app', 'Data for PCDF') . ' '  ?> (<?php // Yii::$app->controls->view_date($date)  ?>)
                <a data-toggle="collapse" href="#collapse1" class="setting"><i class="fa fa-gear"></i></a>
                <a class="member-mobile-info pull-right"><i class="fa fa-mobile"></i></a>
            </h4>
        </div> -->
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'post',
                ]);
                ?>
                <div class="dashboard_filter_form">
                    <!-- <h4 class="panel-title">
                    <?php // Yii::t('app', 'Data for PCDF') . ' '  ?> (<?php //Yii::$app->controls->view_date($date)  ?>)
                    </h4> -->
                    <div class="row">
                        <div class="col-sm-6">
                            <?= Yii::$app->controls->date($model, $form, 'date', '', true, false, false, false); ?>
                        </div>
                        <?= Html::activeHiddenInput($model, 'widget_type', ['id' => 'hidden_widget_type']) ?>
                        <div class="col-sm-6">
                            <div class="switch-field">
                                <input type="radio" id="radio-farmer" class="radio_widgit_type" name="widget_type" value="farmer"/>
                                <label for="radio-farmer">Farmer</label>
                                <input type="radio" id="radio-rmrd" class="radio_widgit_type" name="widget_type" value="rmrd" />
                                <label for="radio-rmrd">RMRD</label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', false); ?>
                        </div>
                        <div class="col-sm-6">
                            <?php echo Html::hiddenInput('load_all', true, ['id' => 'load_all']); ?>
                            <?= Yii::$app->dropdown->union_mcc($model, $form, 'dashboard-union_code,load_all', 'mcc_code', false, false, false); ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php
                        echo $form->field($model, 'rmrd_widgets[]')->checkboxList(
                                $allRmrdWidgets, [
                            'id' => 'rmrd_widgets_list',
                            'class' => 'row sortable',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($allRmrdWidgets, $rmrd_selected_widgets, $model, $dashboard_widget) {
                                //                var_dump(count($map_model));exit;
                                $checked = in_array($label, $rmrd_selected_widgets);
                                $dispLabel = '';
                                $dispLabel = $dashboard_widget->getWidgetLabel($label, 'rmrd');
                                if (empty($dispLabel)) {
                                    return '';
                                } else {
                                    // $check = $model->getDistrictUsed($allowWidgets, $label);
                                    // $disabled = ($checked == 1 && $check == 1) ? ' disabled' : '';
                                    return "<div class='col-sm-6 dcs-checklist checklist'><div class='checkbox widgets_checkbox'>" . Html::checkbox($name, $checked, [
                                                'value' => $label,
                                                'id' => 'rmrd_' . $label,
                                                'label' => '<label for="rmrd_' . $label . '">' . $dashboard_widget->getWidgetLabel($label, 'rmrd') . '</label>',
                                                'labelOptions' => [
                                                    'class' => 'widgets-text' //. $disabled,
                                                ],
                                                'class' => 'widgets-checkbox',
                                            ]) . "</div></div>";
                                }
                            },
                                ]
                        )->label(false);
                        ?>

                        <?php
                        echo $form->field($model, 'farmer_widgets[]')->checkboxList(
                                $allFarmerWidgets, [
                            'id' => 'farmer_widgets_list',
                            'class' => 'row sortable',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($allFarmerWidgets, $farmer_selected_widgets, $model, $dashboard_widget) {
                                //                var_dump(count($map_model));exit;
                                $checked = in_array($label, $farmer_selected_widgets);
                                $dispLabel = '';
                                $dispLabel = $dashboard_widget->getWidgetLabel($label, 'farmer');
                                if (empty($dispLabel)) {
                                    return '';
                                } else {
                                    // $check = $model->getDistrictUsed($allowWidgets, $label);
                                    // $disabled = ($checked == 1 && $check == 1) ? ' disabled' : '';
                                    return "<div class='col-sm-6 dcs-checklist checklist'><div class='checkbox widgets_checkbox'>" . Html::checkbox($name, $checked, [
                                                'value' => $label,
                                                'id' => 'farmer_' . $label,
                                                'label' => '<label for="farmer_' . $label . '">' . $dashboard_widget->getWidgetLabel($label, 'farmer') . '</label>',
                                                'labelOptions' => [
                                                    'class' => 'widgets-text' //. $disabled,
                                                ],
                                                'class' => 'widgets-checkbox',
                                            ]) . "</div></div>";
                                }
                            },
                                ]
                        )->label(false);
                        ?>
                        <div class="col-sm-12 pt5">
                            <div class="col-sm-2">
                                <a class="member-mobile-info pull-Left"><i class="fa fa-mobile fa-2x"></i></a>
                            </div>
                            <div class="col-sm-10 filt_btn">
                                <?= Yii::$app->controls->search(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-main panel-dashboard">
    <div class="panel-body dashboard_section">
        <div class="row">
            <?php //if (Yii::$app->session->get('organizations_type') !== 'UNION') {  ?>
            <!-- <div class="col-sm-6">
                <div class="flt">
            <?php
            // $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_union',
            //     'url' => $chart_url, 'container' => 'fed_union_container',
            //     'date_range' => false, 'range2' => false,
            //     'range_id1' => 'dt1',
            //     'shift' => true, 'type' => 'column', 'title' => Yii::t('app', 'Unionwise Milk Collection')]);
            ?>
                    <div id="fed_union_container" class="cont"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="flt">
            <?php
            // $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_comparison',
            //     'url' => $chart_url, 'container' => 'fed_comparison_container',
            //     'date_range' => true, 'range2' => true,
            //     'range_id1' => 'comp1', 'range_id2' => 'comp2',
            //     'shift' => false, 'type' => 'column',
            //     'title' => 'Compare Milk Collection']);
            ?>
                    <div id="fed_comparison_container" class="cont"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12">
                <div class="flt">
            <?php //$this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_datewise', 'url' => $chart_url, 'container' => 'fed_datewise_container', 'date_range' => true, 'range2' => false, 'shift' => false, 'type' => 'column', 'title' => 'Datewise Milk Collection']);  ?>
                    <div id="fed_datewise_container" class="cont"></div>
                </div>
            </div> -->
            <?php //} else {  ?> 
            <div class="row">




            </div>
            <?php //}  ?>
        </div>
        <?php
        $selected_widgets = $widget_type == 'farmer' ? $farmer_selected_widgets : $rmrd_selected_widgets;
        $all_widgets = $widget_type == 'farmer' ? $farmerWidgets : $rmrdWidgets;
        if (!empty($selected_widgets)) {
            foreach ($selected_widgets as $key => $value) {
                if (in_array($value, $all_widgets)) {
                    echo $this->render('widget_dashboard_' . $value, ['model' => $model, 'date' => $date, 'table_url' => $table_url, 'container_url' => $container_url, 'class_cols' => $class_cols, 'display' => $display, 'display_rmrd' => $display_rmrd, 'chart_url' => $chart_url]);
                }
            }
        }
        ?>


    </div>
</div>
<div id="chartModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content dashboardWidhetModalPopup">
            <div class="modal-header dashboardWidgetHeader">
                <button type="button" class="close  color_fff opacity_one" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id='cal_modal-title'></h4>
            </div>
            <div class="modal-body" id='calendar_details'>
            </div>
        </div>

    </div>
</div>

<div id="pieChartModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Member V/S Mobile APP'); ?></h4>
            </div>
            <div class="modal-body" id='piecontainer'>
            </div>
        </div>

    </div>
</div>
<div id="crossTabDetails"></div>
<div id="chartToTable"></div>

<?php
$script = "  
$( '.sortable' ).sortable();
    $(window).load(function(){
        if('" . $widget_type . "' == '' || '" . $widget_type . "' == 'farmer'){
            $('#hidden_widget_type').val('farmer');
            $('#radio-farmer').prop('checked', true);
            $('#rmrd_widgets_list').hide();
            $('#farmer_widgets_list').show();
        }
        else{
            $('#hidden_widget_type').val('" . $widget_type . "');
            $('#radio-rmrd').prop('checked', true);
            $('#rmrd_widgets_list').show();
            $('#farmer_widgets_list').hide();
        }
        var position = '';
        var widgets = '" . $lazy_loading_widgets . "';
        var widget = $.parseJSON(widgets);
        $.each(widget, function(index, value) {
            var datastring = $('form#'+value).serialize();
            $('#dataStringVal').val(datastring);
            if(['fed_union',
                'fed_comparison',
                'fed_datewise',
                'table_milk_collection',
                'manual_vs_auto_collection',
                'dipatch_vs_receipt',
                'bmc_collection_summary',
                'monthly_milk_collection',
                'dashboard_blocks',
                'piechart_member_app',
                'calender',
                'dashboard_farmer_rmrd_blocks',
                'dashboard_farmer_status',
                'dashboard_farmer_rmrd_avg','BmcWiseCrossTab','tbl_hits_counts','tbl_collc_count_summary'].indexOf(value) == -1) 
                {
                    setChartWidgets(value);
                }
            
            else if(['dashboard_blocks'].indexOf(value) == 0){
                var blockDataString = $('#collapse1 form').serialize();
                var id= 'dashboard_blocks';
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/site/load-dashboard-block-data']) . "',
                    data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
                    success: function(data) {
                        var obj1 = data;
                        if (obj1.status == 'success')
                        {
                            for (var key in obj1.res){
                                if(obj1.res[key] == null){
                                    obj1.res[key] = 0;
                                }
                            }
                            $('#no_of_societies').text(obj1.res.Dcs_Count);
                            $('#no_of_societies_M').text(obj1.res.Dcs_Count_M);
                            $('#no_of_societies_E').text(obj1.res.Dcs_Count_E);
                            $('#no_of_pourers').text(obj1.res.Total_Member); 
                            $('#collection_vs_installed').text(obj1.res.Dcs_Count+'/'+obj1.res.Install_Count);
                            $('#collection_vs_dispatch').text(obj1.res.Dcs_Count+'/'+obj1.res.Dcs_DisQty_total);
                            $('#dispatch_vs_receipt_block').text(obj1.res.Dcs_DisQty_total+'/'+obj1.res.bmc_dcs_Count);
                            $('#total_milk_collection_ltr').text(obj1.res.UnionQty+'/'+obj1.res.union_avg_fat+'/'+obj1.res.union_avg_snf);
                            $('#total_milk_dispatch_ltr').text(obj1.res.UnionDisQty+'/'+obj1.res.union_dis_avg_fat+'/'+obj1.res.union_dis_avg_snf);
                            $('#total_milk_dispatch_M').text(obj1.res.Dcs_DisQty_M);
                            $('#total_milk_dispatch_E').text(obj1.res.Dcs_DisQty_E);
                            $('#total_bmc_collection_ltr').text(obj1.res.BmcQty+'/'+obj1.res.bmc_avg_fat+'/'+obj1.res.bmc_avg_snf);
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted..Please try again');
                    }
                });
            }
            else if(['dashboard_farmer_rmrd_blocks'].indexOf(value) == 0){
                var blockDataString = $('#collapse1 form').serialize();
                var id= 'dashboard_farmer_rmrd_blocks';
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                var widget_type= $('#hidden_widget_type').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/site/load-dashboard-farmer-rmrd-data']) . "',
                    data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc+'&widget_type='+widget_type,
                    success: function(data) {
                        var obj1 = data;
                        if (obj1.status == 'success')
                        {
                            for (var key in obj1.res){
                                if(obj1.res[key] == null){
                                    obj1.res[key] = 0;
                                }
                            }
                            $('#farmer_rmrd_block_union').text(obj1.res.pourerUnion+'/'+obj1.res.totalUnion);
                            $('#farmer_rmrd_block_mcc').text(obj1.res.pourerMcc+'/'+obj1.res.totalMcc);
                            $('#farmer_rmrd_block_dcs').text(obj1.res.pourerDcs+'/'+obj1.res.totalDcs);
                            $('#farmer_rmrd_block_farmer').text(obj1.res.pourerMember+'/'+obj1.res.totalMember);
                            $('#farmer_rmrd_block_blk_vendor').text(obj1.res.pourerBulkVen+'/'+obj1.res.totalBulkVen);
                            $('#farmer_rmrd_block_vlcc_vendor').text(obj1.res.pourerVlccVen+'/'+obj1.res.totalVlccVen);
                            $('#farmer_rmrd_block_quantity').text(obj1.res.totalQty);
                            $('#farmer_rmrd_block_fatkg').text(obj1.res.fatKg);
                            $('#farmer_rmrd_block_snfkg').text(obj1.res.snfKg);
                            $('#farmer_rmrd_block_amount').text(obj1.res.amount);
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted.Please try again');
                    }
                });
            }
            else if(['dashboard_farmer_rmrd_avg'].indexOf(value) == 0){
                var blockDataString = $('#collapse1 form').serialize();
                var id= 'dashboard_farmer_rmrd_avg';
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                var widget_type= $('#hidden_widget_type').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/site/load-dashboard-farmer-rmrd-data']) . "',
                    data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc+'&widget_type='+widget_type,
                    success: function(data) {
                        var obj1 = data;
                        if (obj1.status == 'success')
                        {
                            for (var key in obj1.res){
                                if(obj1.res[key] == null){
                                    obj1.res[key] = 0;
                                }
                            }
                            var table = $('#farmer_rmrd_tbl_container table tbody');
                            var i = 0;
                            console.log(obj1.res);
                            var htmlData = '';
                            htmlData = htmlData + '<tbody>';
                            Object.keys(obj1.res).forEach(function (key){
                                htmlData = htmlData + '<tr>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetHeader color_fff\' rowspan=\'2\'>AVG/';
                                htmlData = htmlData + obj1.res[key].colType;
                                htmlData = htmlData + '</td>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetDetailPortion color_fff\'>" . Yii::t('app', 'FAT') . "</td>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetDetailPortion color_fff\'>" . Yii::t('app', 'SNF') . "</td>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetDetailPortion color_fff\'>" . Yii::t('app', 'QTY') . "</td>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetDetailPortion color_fff\'>" . Yii::t('app', 'Rate') . "</td>';
                                htmlData = htmlData + '<td class=\'dashboardWidgetDetailPortion color_fff\'>" . Yii::t('app', 'Amount') . "</td>';
                                htmlData = htmlData + '</tr>';
                                htmlData = htmlData + '<tr>';
                                htmlData = htmlData + '<td>'+obj1.res[key].avgFat+'</td>';
                                htmlData = htmlData + '<td>'+obj1.res[key].avgSnf+'</td>';
                                htmlData = htmlData + '<td>'+obj1.res[key].avgQty+'</td>';
                                htmlData = htmlData + '<td>'+obj1.res[key].avgRate+'</td>';
                                htmlData = htmlData + '<td>'+obj1.res[key].avgAmount+'</td>';
                                htmlData = htmlData + '</tr>';
                            });
                            htmlData = htmlData + '</tbody>';
                            
                            $('#farmer_rmrd_tbl_container').removeClass('disp_none');
                            $('.farmerRmrdAvgData').html(htmlData);
//                            Object.keys(obj1.res).forEach(function (key){
//                                var j = 0;
//                                $('#farmer_rmrd_tbl_container table thead tr:last').append('<th>'+obj1.res[key].colType.replace(/(^|_)./g, s => s.toUpperCase()).replace('_',' ')+'</th>')
//                                table.find('tr:eq('+ j++ +')').append('<td>'+obj1.res[key].avgQty+'</td>');
//                                table.find('tr:eq('+ j++ +')').append('<td>'+obj1.res[key].avgFat+'</td>');
//                                table.find('tr:eq('+ j++ +')').append('<td>'+obj1.res[key].avgSnf+'</td>');
//                                table.find('tr:eq('+ j++ +')').append('<td>'+obj1.res[key].avgRate+'</td>');
//                                table.find('tr:eq('+ j++ +')').append('<td>'+obj1.res[key].avgAmount+'</td>');
//                                $('#farmer_rmrd_tbl_container').removeClass('disp_none');
//                            });
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted.Please try again');
                    }
                });
            }
            else if(['dashboard_farmer_status'].indexOf(value) == 0){
                var blockDataString = $('#collapse1 form').serialize();
                var id= 'dashboard_farmer_status';
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/site/load-dashboard-farmer-rmrd-data']) . "',
                    data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
                    success: function(data) {
                        var obj1 = data;
                        if (obj1.status == 'success')
                        {
                            for (var key in obj1.res){
                                if(obj1.res[key] == null){
                                    obj1.res[key] = 0;
                                }
                            }
                            $('#dashboard_farmer_status_active_dcs').text(obj1.res.activeDcs);
                            $('#dashboard_farmer_status_installed_dcs').text(obj1.res.installedDcs);
                            $('#dashboard_farmer_status_online_dcs').text(obj1.res.onlineDcs);
                            $('#dashboard_farmer_status_offline_dcs').text(obj1.res.offlineDcs);
                            $('#dashboard_farmer_status_online_dcs_e').text(obj1.res.onlineDcsE);
                            $('#dashboard_farmer_status_online_dcs_m').text(obj1.res.onlineDcsM);
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted.Please try again');
                    }
                });
            }
            else if(['calender'].indexOf(value) == 0){
                //calendar widget
                $('#calendar').fullCalendar({
                dayRender: function(date, cell) {
                    var d=date.format('YYYY-MM-DD');
                    if(d in cal_data)
                    {
                        cell.append('<div class=\"cal-data\"><span class=\"label text-success\" title=\"Avg FAT\">'+cal_data[d][0]+'</span><span class=\"label text-danger\" title=\"Avg SNF\">'+cal_data[d][1]+'</span><span class=\"label text-info\" title=\"Qty(ltr)\">'+cal_data[d][2]+'</span></div>');
                    }
                },
                defaultDate: moment('" . $date . "'),
                viewRender: function (view, element) {
                var b = $('#calendar').fullCalendar('getDate');
                var m=b.format('Y-MM');
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                        $('.fc-day-grid').html('<div class=\"text-center mt35\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
                    $.ajax({
                                type: 'post',
                                url: '" . Url::to(['/site/load-month-data']) . "',
                                data: 'm='+m+'&union='+union+'&mcc='+mcc,
                                success: function(data) {

                                    var obj1 = data;
                                    if (obj1.status == 'success')
                                    {
                                    cal_data=obj1.res;                                                   
                                    }
                                    let cview = $('#calendar').fullCalendar('getView');  
                                    cview.unrenderDates();
                                    cview.renderDates();
                                    $(window).trigger('resize'); 
                                    

                                },
                                error:function(data){
                                            //alert('Your data has not been submitted..Please try again');
                                        }
                    });
                },
                dayClick: function(date, jsEvent, view) {
                var dt=date.format();
                var union= $('#dashboard-union_code').val();
                var mcc= $('#dashboard-mcc_code').val();
                    $('#cal_modal-title').html('Data for '+date.format('DD-MM-YYYY'));
                    $('#calendar_details').html('<div class=\"text-center\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
                    $.ajax({
                                type: 'post',
                                url: '" . Url::to(['/site/load-dcs-data']) . "',
                                data: 'dt='+dt+'&union='+union+'&mcc='+mcc,
                                success: function(data) {

                                    var obj1 = data;
                                    if (obj1.status == 'success')
                                    {
                                    
                                        var html='<div class=\"milk-collection\">'+
                                        '<div class=\"table-responsive dashboard_tbl\"><table class=\"table table-striped\">'+
                                        '<thead><tr><th>Union</th><th>Villages</th><th>Avg FAT</th><th>Avg SNF</th><th>Milk Collection (ltr)</th></tr></thead>';
                                    $.each(obj1.res, function(index, value) {
                                        html=html+'<tr>'+
                                            '<td>'+value.union_name+'</td>'+
                                            '<td>'+value.dcs_name+'</td>'+
                                            '<td>'+value.AvgFAT+'</td>'+
                                            '<td>'+value.AvgSNF+'</td>'+
                                            '<td>'+value.total_qty+'</td>'+
                                        '</tr>';
                                        });
                                        
                                    html=html+'</table></div></div>';
                                $('#calendar_details').html(html);
                                    }
                                    else{
                                        $('#calendar_details').html('Data not available.');
                                    }

                                },
                                error:function(data){
                                            //alert('Your data has not been submitted..Please try again');
                                        }
                    });
                    chartModal.modal('show');
                }
            });
            }
        });
    });

    function setChartWidgets(set_widget_id){
        drawChart(set_widget_id,set_widget_id+'_container','{$chart_url}','column');   
        // console.log(set_widget_id);
        // console.log(set_widget_id+'_container');
    }
//new code
    barChart('bmc_dispatch_widget_container','" . Yii::$app->controls->view_date($date) . " BMC Dispatch',[],[]);
    barChart('milk_coll_widget_container','" . Yii::$app->controls->view_date($date) . " Milk Collection',[],[]);
    barChart('bmc_coll_widget_container','" . Yii::$app->controls->view_date($date) . " BMC Collection',[],[]);
    barChart('reconciliation_chart_widget_container','" . Yii::$app->controls->view_date($date) . " Reconciliation Chart',[],[]);
    // barChart('collection_farmer_container','',[],[]);
    function barChart(cont,text,xdata,ydata){
        var bar_chart = $('#'+cont);
            if (bar_chart.length) {
                Highcharts.chart(cont, {
                    chart: {
                        zoomType: 'xy'
                    },
                    title: {
                        text:text
                    },
                    xAxis: [{
                            categories: xdata,
                            crosshair: true
                        }],
                    yAxis: [{// Primary yAxis
                            labels: {
                                format: '{value}',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            },
                            title: {
                                text: '',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            }
                        }, {// Secondary yAxis
                            title: {
                                text: '',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            opposite: false,
                        }
                    ],
                    tooltip: {
                        shared: true
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'left',
                        x: 120,
                        verticalAlign: 'top',
                        y: 100,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                    },
                    series: [{
                                name: 'QTY(ltr)',
                            type: 'column',
                            color: '#3a7bd5',
                            yAxis: 1,
                                data: ydata,
                            tooltip: {
                                valueSuffix: ' lt'
                            }
    
                        }]
                });
            }
         }
         
          function drawChart(id,cntr,url,type)
          {
            if(['bmc_union_comparison','union_datewise','bmc_union_datewise','union_comparison'].indexOf(id) == -1){
                var datastring = $('#collapse1 form').serialize();
            }else{
                var datastring = $('#'+id).serialize();
            }
            var union= $('#dashboard-union_code').val();
            var mcc= $('#dashboard-mcc_code').val();
            $.ajax({
                         type: 'post',
                         url: url,
                         data: datastring+'&sp='+id+'&union='+union+'&mcc='+mcc,
                         success: function(data) {
                        
                            var index=$('#'+cntr).data('highcharts-chart');
                            var chart=Highcharts.charts[index];
                            var vals=[];
                            var color='3a7bd5';
                            var suf='';
                            // console.log(chart +'--'+id);
                            while( chart.series.length > 0 ) {
                                chart.series[0].remove( false );
                            }
                            $.each(data.res, function (key, val) {
                            vals = val.map(function (x) { 
                                return parseFloat(x, 10); 
                            });
                            if(key.toLowerCase()==='qty')
                            {
                                suf='(ltr)';
                            }
                            else
                            {
                                suf='';
                            }
                            
                            chart.addSeries({  
                                type: type,
                                name: key.toUpperCase()+suf,
                                data: vals,
                                yAxis:1,
                                color:'#'+color,
                            }, false);
                            color=parseInt(color)+003333;
                           
                            });
                            chart.xAxis[0].setCategories(data.lbl[0]);
                             chart.redraw();
                         },
                         error:function(data){
                                     //alert('Your data has not been submitted..Please try again');
                                }
            });
          }
       //completed new code
//calender functions
var cal_data=" . $cal_data . ";
var chartModal = $('#chartModal').modal({
        show: false
    });


    

    /* Bar and Line dual chart */
    var line_chart = $('#reconciliation');
    if (line_chart.length) {
        Highcharts.chart('reconciliation', {
            chart: {
                zoomType: 'xy',
                height: '530px'
            },
            title: {
                text: '" . Yii::$app->controls->view_date($date) . ' ' . Yii::t('app', 'Reconciliation Chart') . "',
                y: 9,

            },

            xAxis: [{
                    categories: " . json_encode($graph_chart_dcs) . ",
                    crosshair: true
                }],
            yAxis: [{// Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }, {// Secondary yAxis
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} mm',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 400,
                verticalAlign: 'top',
                y: 10,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            series: [
                {
                    name: '" . Yii::t('app', 'DPU Farmer') . "',                     
                    type: 'spline',
                    color: '#3a7bd5',
                    data: dc,
                },{
                    name: '" . Yii::t('app', 'Collection Farmer') . "',                     
                    type: 'spline',
                    color: '#117856',
                    data: cfc,
                }
            ]
        });
    }
// $('#bmc-compare').hide();
// $('.society-compare').on('click',function() {
//     $('#society-compare').show();
//     $(this).addClass('active');
//     $('#bmc-compare').hide();
//     $('.bmc-compare').removeClass('active');
// });
// $('.bmc-compare').on('click',function() {
//     $('#bmc-compare').show();
//     $(this).addClass('active');
//     $('#society-compare').hide();
//     $('.society-compare').removeClass('active');
// });


        
// $('#bmc-datewise').hide();
// $('.society-datewise').on('click',function() {
//     $('#society-datewise').show();
//     $(this).addClass('active');
//     $('#bmc-datewise').hide();
//     $('.bmc-datewise').removeClass('active');
// });
// $('.bmc-datewise').on('click',function() {
//     $('#bmc-datewise').show();
//     $(this).addClass('active');
//     $('#society-datewise').hide();
//     $('.society-datewise').removeClass('active');
// });
$(document).ready(function(){
    $(document).on('click','.cross-tab-modal',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var p_date= $(this).attr('data-p_date');
        var shift= $(this).attr('data-shift');
        var union_Code= $(this).attr('data-union_Code');
        var p_bmc_code= $(this).attr('data-p_bmc_code');
        var p_type= $(this).attr('data-p_type');
        var bmc_name= $(this).attr('data-bmc_name');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/site/bmc-cross-tab-details']) . "',
            data:{'p_date':p_date, 'shift':shift, 'bmc_name' : bmc_name, 'union_Code':union_Code, 'p_bmc_code':p_bmc_code, 'p_type':p_type},
            success: function(data) {     
                $('#crossTabDetails').html(data);
                $('#crossTabDetailsModal').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });  
});
function setPopupTable(id,cntr,url,type,diff_sp_name = '', title = ''){
    $('#pageloader').show();
    $('#loadercontent').show();
    var datastring = $('#'+id).serialize();
    var sp_name = id;
    if(diff_sp_name != ''){
        sp_name = diff_sp_name;
    }
    var union= $('#dashboard-union_code').val();
    var mcc= $('#dashboard-mcc_code').val();
    $.ajax({
        type: 'post',
        url: url,
        data: datastring+'&sp='+sp_name+'&union='+union+'&title='+title+'&mcc='+mcc,
        success: function(data) {
            $('#chartToTable').html(data);
            $('#chartToTableModal').modal('toggle'); 
            $('#loadercontent').hide();
            $('#pageloader').hide();
        },
        error:function(data){
            $('#loadercontent').hide();
            $('#pageloader').hide();
        }
    });
}

function setHtmlData(id,cntr,url){
    // $('#pageloader').show();
    // $('#loadercontent').show();
    var datastring = $('#'+id).serialize();
    var sp_name = id;
    var union= $('#dashboard-union_code').val();
    var mcc= $('#dashboard-mcc_code').val();
    var popup = 'allow_popup';
    $.ajax({
        type: 'post',
        url: url,
        data: datastring+'&sp='+sp_name+'&union='+union+'&popup='+popup+'&mcc='+mcc,
        success: function(data) {
//            console.log(id+'_container');
            $('#'+id+'_container').html(data);
            // $('#loadercontent').hide();
            // $('#pageloader').hide();
        },
        error:function(data){
            // $('#loadercontent').hide();
            // $('#pageloader').hide();
        }
    });
}

var active_member = " . $active_member . ";
var mobile_app_active = " . $mobile_app_active . ";
var mobile_app_block = " . $mobile_app_block . ";
var pie_chart = $('#piecontainer');
if (pie_chart.length) {
    Highcharts.chart('piecontainer', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: false,
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name} ({point.y})</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    },
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: '" . Yii::t('app', 'Active Member') . "',
                y: active_member,
                color: '#3a7cd6'
            }, {
                name: '" . Yii::t('app', 'Mobile App Active') . "',
                y: mobile_app_active,
                color: '#1758'
            }, {
                name: '" . Yii::t('app', 'Mobile App Blocked') . "',
                y: mobile_app_block,
                color: '#FFB319'
            }]
        }]
    });
}

$(document).on('click','.member-mobile-info',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        $('#pieChartModal').modal('toggle'); 
        $('#loadercontent').hide();
        $('#pageloader').hide();
});

$('.radio_widgit_type').on('change',function() {
    $('#hidden_widget_type').val($('input[name=widget_type]:checked', '.switch-field').val());

    if($('input[name=widget_type]:checked', '.switch-field').val() == 'farmer'){
        $('#rmrd_widgets_list').hide();
        $('#farmer_widgets_list').show();
    }

    if($('input[name=widget_type]:checked', '.switch-field').val() == 'rmrd'){
        $('#farmer_widgets_list').hide();
        $('#rmrd_widgets_list').show();
    }
});

";
$this->registerJs($script, View::POS_READY, 'village-code');
?>