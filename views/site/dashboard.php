<?php
$this->title = 'Dashboard';

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
?>
<div class="panel-group row panel-fixed" id="filter">
    <div class="panel panel-default min_h_0">
        <div class="panel-heading text-center">
            <h4 class="panel-title">
                <?= Yii::t('app', 'Data for PCDF') . ' ' ?> (<?= Yii::$app->controls->view_date($date) ?>)
                <a data-toggle="collapse" href="#collapse1" class="setting"><i class="fa fa-gear"></i></a>
                <a class="member-mobile-info pull-right"><i class="fa fa-mobile"></i></a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'post',
                ]);
                ?>
                <div class="filt">
                    <div class="">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                    </div>
                    <div class="">
                        <?= Yii::$app->controls->date($model, $form, 'date'); ?>
                    </div>
                    <div class="filt-btn">
                        <?= Yii::$app->controls->search(); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-main panel-dashboard">
    <div class="panel-body">
        <div class="row">
            <?php if (Yii::$app->session->get('organizations_type') !== 'UNION') { ?>
                <div class="col-sm-6">
                    <div class="flt">
                        <?=
                        $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_union',
                            'url' => $chart_url, 'container' => 'container1',
                            'date_range' => false, 'range2' => false,
                            'range_id1' => 'dt1',
                            'shift' => true, 'type' => 'column', 'title' => Yii::t('app', 'Unionwise Milk Collection')]);
                        ?>
                        <div id="container1" class="cont"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="flt">
                        <?=
                        $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_comparison',
                            'url' => $chart_url, 'container' => 'container2',
                            'date_range' => true, 'range2' => true,
                            'range_id1' => 'comp1', 'range_id2' => 'comp2',
                            'shift' => false, 'type' => 'column',
                            'title' => 'Compare Milk Collection']);
                        ?>
                        <div id="container2" class="cont"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="flt">
                        <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_datewise', 'url' => $chart_url, 'container' => 'container3', 'date_range' => true, 'range2' => false, 'shift' => false, 'type' => 'column', 'title' => 'Datewise Milk Collection']); ?>
                        <div id="container3" class="cont"></div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-6 widget-tabbing">
                    <div class="col-sm-6 text-center widget-tab society-compare active"><?= Yii::t('app', 'Society Milk Collection'); ?></div>
                    <div class="col-sm-6 text-center widget-tab bmc-compare"><?= Yii::t('app', 'BMC Milk Collection'); ?></div>
                    <div class="flt">
                        <div id="society-compare">
                            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_comparison', 'url' => $chart_url, 'container' => 'container1', 'date_range' => true, 'range2' => true, 'shift' => false, 'type' => 'column', 'title' => '', 'table_popup' => true, 'table_class' => 'union_comparison_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Society Milk Collection')]); ?>
                            <div id="container1" class="cont"></div>
                        </div>
                        <div id="bmc-compare">
                            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'bmc_union_comparison', 'url' => $chart_url, 'container' => 'container3', 'date_range' => true, 'range2' => true, 'shift' => false, 'type' => 'column', 'title' => '', 'range_id1' => 'bmc_from_Date', 'range_id2' => 'bmc_to_Date', 'range_id3' => 'bmc_from_Date_2', 'range_id4' => 'bmc_to_Date_2', 'table_popup' => true, 'table_class' => 'bmc_union_comparison_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Milk Collection')]); ?>
                            <div id="container3" class="cont"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 widget-tabbing">
                    <div class="col-sm-6 text-center widget-tab society-datewise active"><?= Yii::t('app', 'Society Milk Collection - Date Wise'); ?></div>
                    <div class="col-sm-6 text-center widget-tab bmc-datewise"><?= Yii::t('app', 'BMC Milk Collection - Date Wise'); ?></div>
                    <div class="flt">
                        <div id="society-datewise">
                            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_datewise', 'url' => $chart_url, 'container' => 'container2', 'date_range' => true, 'range2' => false, 'range_id1' => 'comp1', 'range_id2' => 'comp2', 'shift' => false, 'type' => 'column', 'title' => '', 'table_popup' => true, 'table_class' => 'union_datewise_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Society Milk Collection - Date Wise')]); ?>
                            <div id="container2" class="cont"></div>
                        </div>
                        <div id="bmc-datewise">
                            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'bmc_union_datewise', 'url' => $chart_url, 'container' => 'container4', 'date_range' => true, 'range2' => false, 'range_id1' => 'comp1', 'range_id2' => 'comp2', 'shift' => false, 'type' => 'column', 'title' => '', 'range_id1' => 'bmc_date_wise_from_Date', 'range_id2' => 'bmc_date_wise_to_Date', 'table_popup' => true, 'table_class' => 'bmc_union_datewise_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Milk Collection - Date Wise')]); ?>
                            <div id="container4" class="cont"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clearfix mt25"></div>      
        <div class="row">
            <div class="col-sm-6">
                <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'container5', 'diff_sp_name' => 'milk_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'milk_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Milk Collection')]); ?>
                <div id="container5" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'container6', 'diff_sp_name' => 'bmc_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'bmc_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Collection')]); ?>
                <div id="container6" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'container7', 'diff_sp_name' => 'bmc_dispatch_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'bmc_dispatch_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Dispatch')]); ?>
                <div id="container7" class="cont"></div>
            </div>
            <div class="col-sm-6">
                <div class="milk-collection">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th colspan="4">Monthly Milk Collection</th> 
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Union') ?></th>
                                    <th>Villages</th>
                                    <th>No of Pourers</th>
                                    <th>Monthly Milk Collection(ltr)</th>
                                </tr>
                            </thead>
                            <?php
                            if (!empty($monthly_milk_collection)) {
                                foreach ($monthly_milk_collection as $milk_collection) {
                                    ?>
                                    <tr>
                                        <td><?= $milk_collection['union_name'] ?></td>
                                        <td><?= $milk_collection['dcs_name'] ?></td>
                                        <td><?= $milk_collection['Member_Count'] ?></td>
                                        <td><?= $milk_collection['Qty'] ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr><td colspan="4">Data not available.</td></tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'No. of Societies') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0 ?></h4>
                        <p><b>M:</b> <?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count_M'] : 0 ?> | <b>E:</b> <?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count_E'] : 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">                                
                    <div class="tbl-cell">
                        <p>No. of Pourers</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4><?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Total_Member'] : 0 ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Collection vs Installed') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h4><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Install_Count'] : 0) ?></h4></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Collection vs Dispatch') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h4><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_Count'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_total'] : 0) ?></h4></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p><?= Yii::t('app', 'Dispatch vs Receipt') ?></p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <p><h3><?= (!empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_total'] : 0) . '/' . (!empty($dashboard_blocks) ? $dashboard_blocks[0]['bmc_dcs_Count'] : 0) ?></h3></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['UnionQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['UnionQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['union_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['union_avg_snf'] . '</span>' : 0 ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total Milk Dispatch(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h3><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['UnionDisQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['UnionDisQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['union_dis_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['union_dis_avg_snf'] . '</span>' : 0 ?></h3>
                        <p><b>M:</b> <?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['Dcs_DisQty_M']) ? $dashboard_blocks[0]['Dcs_DisQty_M'] : 0 ?> | <b>E:</b> <?= !empty($dashboard_blocks) ? $dashboard_blocks[0]['Dcs_DisQty_E'] : 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="collection">
                    <div class="tbl-cell">
                        <p>Total BMC Collection(ltr)</p>
                        <p><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                        <h4><?= !empty($dashboard_blocks) && !empty($dashboard_blocks[0]['BmcQty']) ? '<span title=\'Quantity\'>' . $dashboard_blocks[0]['BmcQty'] . '</span>/<span title=\'Avg. FAT\'>' . $dashboard_blocks[0]['bmc_avg_fat'] . '</span>/<span title=\'Avg. SNF\'>' . $dashboard_blocks[0]['bmc_avg_snf'] . '</span>' : 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">            
            <div class="col-sm-12">
                <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'reconciliation', 'diff_sp_name' => 'reconciliation_chart_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'reconciliation_chart_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Reconciliation Chart')]); ?>
                <div id="reconciliation" class="cont"></div>
            </div>            
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="cal-header">Avg. FAT, Avg. SNF and Qty for</div>
                <div id="calendar"></div>
            </div>
            <div class="col-sm-6">
                <div class="cal-header"><?= Yii::t('app', 'BMC Wise Data Receipt Status'); ?></div>
                <div class="flt">
                    <div id="society-compare">
                        <?= $this->render('_dashborad_filter_cross_tab', ['model' => $model, 'id' => 'BmcWiseCrossTab', 'container' => 'BmcWiseCrossTab', 'date_range' => true, 'date_range_class' => 'col-sm-6', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'range_id1' => 'cross_tab_dt1', 'range_id2' => 'cross_tab_dt2']); ?>
                        <div id="BmcWiseCrossTab_container" class="cont milk-collection"></div>
                    </div>
                </div>
            </div>
            <!--            <div class="col-sm-6">
                            <div id="map_div"></div>
                        </div>-->
        </div>
        <div class="row">            
            <div class="col-sm-12">
                <div class="cal-header"><?= Yii::t('app', 'Milk Collection'); ?></div>
                <div class="flt">
                    <div id="society-compare">
                        <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'table_milk_collection', 'url' => $container_url, 'container' => 'table_milk_collection', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'union_code' => true, 'plant_code' => true, 'mcc_code' => 'milk_coll_mcc', 'bmc_code' => 'milk_coll_bmc', 'dcs_code' => true, 'from_date_id' => 'milk_coll_from_date', 'to_date_id' => 'milk_coll_to_date']); ?>
                        <div id="table_milk_collection_container"  class="milk-collection mt0 cont"></div>
                    </div>         
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-sm-6">
                <div class="cal-header"><?= Yii::t('app', 'Collection Status Manual vs Auto'); ?></div>
                <div class="flt">
                    <div id="society-compare">
                        <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'manual_vs_auto_collection', 'url' => $container_url, 'container' => 'manual_vs_auto_collection', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'union_code' => true, 'plant_code' => true, 'mcc_code' => 'coll_status_mcc', 'bmc_code' => 'coll_status_bmc', 'from_date_id' => 'coll_status_from_date', 'to_date_id' => 'coll_status_to_date', 'date_picker_class' => 'col-sm-3']); ?>
                        <div id="manual_vs_auto_collection_container"  class="milk-collection mt0 cont"></div>
                    </div>         
                </div>
            </div>         
            <div class="col-sm-6">
                <div class="cal-header">
                    <?php
                    $search_date = Yii::$app->controls->view_date($date);
                    echo $search_date . ' ' . Yii::t('app', 'Dispatch vs Receipt');
                    ?>
                </div>
                <div class="flt">
                    <div id="society-compare">
                        <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'dipatch_vs_receipt', 'url' => $container_url, 'container' => 'dipatch_vs_receipt', 'hidden_from_date' => $search_date, 'hidden_to_date' => $search_date, 'union_code' => true, 'mcc_code' => 'dispatch_vs_receipt', 'from_date_id' => 'coll_status_from_date', 'to_date_id' => 'coll_status_to_date', 'mcc_class' => 'col-sm-5']); ?>
                        <div id="dipatch_vs_receipt_container"  class="milk-collection mt0 cont div_height495"></div>
                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>
<div id="chartModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
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
    
    function barChart(cont,text,xdata,ydata){
    var bar_chart = $('#'+cont);
        if (bar_chart.length) {
            Highcharts.chart(cont, {
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: text
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
        var datastring = $('#'+id).serialize();
        var union= $('#dashboard-union_code').val();
        $.ajax({
                     type: 'post',
                     url: url,
                     data: datastring+'&sp='+id+'&union='+union,
                     success: function(data) {
                    
                        var index=$('#'+cntr).data('highcharts-chart');
                        var chart=Highcharts.charts[index];
                        var vals=[];
                        var color='3a7bd5';
                        var suf='';
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
          
//calender functions
var cal_data=" . $cal_data . ";
var chartModal = $('#chartModal').modal({
        show: false
    });
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
            $('.fc-day-grid').html('<div class=\"text-center mt35\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
        $.ajax({
                     type: 'post',
                     url: '" . Url::to(['/site/load-month-data']) . "',
                     data: 'm='+m+'&union='+union,
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
        $('#cal_modal-title').html('Data for '+date.format('DD-MM-YYYY'));
        $('#calendar_details').html('<div class=\"text-center\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
        $.ajax({
                     type: 'post',
                     url: '" . Url::to(['/site/load-dcs-data']) . "',
                     data: 'dt='+dt+'&union='+union,
                     success: function(data) {

                         var obj1 = data;
                          if (obj1.status == 'success')
                         {
                          
                            var html='<div class=\"milk-collection\">'+
                            '<div class=\"table-responsive\"><table class=\"table table-striped\">'+
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

function barChart(cont,text,xdata,ydata){
var bar_chart = $('#'+cont);
    if (bar_chart.length) {
        Highcharts.chart(cont, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: text
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
                        name: 'Quantity(ltr)',
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
var bmc_d = " . json_encode($bmc_dispatch) . ";
var bmc_dispatch = bmc_d.map(function (x) { 
    return parseFloat(x, 10); 
});
barChart('container7','" . Yii::$app->controls->view_date($date) . " BMC Dispatch '," . json_encode($d_bmc) . ",bmc_dispatch);
function barChart(cont,text,xdata,ydata){
var bar_chart = $('#'+cont);
    if (bar_chart.length) {
        Highcharts.chart(cont, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: text
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
                        name: 'Quantity(ltr)',
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

    var dcount = " . $DPUCount . ";
    var dc = dcount.map(function (x) { 
        return parseFloat(x, 10); 
    });
    
    var cfcount = " . $CFCount . ";
    var cfc = cfcount.map(function (x) { 
        return parseFloat(x, 10); 
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
$('#bmc-compare').hide();
$('.society-compare').on('click',function() {
    $('#society-compare').show();
    $(this).addClass('active');
    $('#bmc-compare').hide();
    $('.bmc-compare').removeClass('active');
});
$('.bmc-compare').on('click',function() {
    $('#bmc-compare').show();
    $(this).addClass('active');
    $('#society-compare').hide();
    $('.society-compare').removeClass('active');
});

var a = " . $dcs_mcollection . ";
var mcollection = a.map(function (x) { 
    return parseFloat(x, 10); 
});

var b = " . $dcs_ecollection . ";
var ecollection = b.map(function (x) { 
    return parseFloat(x, 10); 
});

var bar_chart = $('#container5');
if (bar_chart.length) {      
    Highcharts.chart('container5', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: '" . Yii::$app->controls->view_date($date) . ' ' . Yii::t('app', 'Milk Collection') . "'
        },
        xAxis: [{
                categories: " . json_encode($bar_chart_dcs) . ",
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
                    format: '{value} ltr'
                },
                opposite: false,
                tickInterval: 200
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
                name: '" . Yii::t('app', 'Morning') . "',
                type: 'column',
                color: '#3a7bd5',
                yAxis: 1,
                data: mcollection,
                tooltip: {
                    valueSuffix: ' lt'
                }

            },{
                name: '" . Yii::t('app', 'Evening') . "',
                type: 'column',
                color: '#1758',
                yAxis: 1,
                data: ecollection,
                tooltip: {
                    valueSuffix: ' lt'
                }

            }]
    });
}
        
var a_bmc = " . $bmc_mcollection . ";
var bmc_mcollection = a_bmc.map(function (x) { 
    return parseFloat(x, 10); 
});

var b_bmc = " . $bmc_ecollection . ";
var bmc_ecollection = b_bmc.map(function (x) { 
    return parseFloat(x, 10); 
});

var bar_chart = $('#container6');
if (bar_chart.length) {      
    Highcharts.chart('container6', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: '" . Yii::$app->controls->view_date($date) . ' ' . Yii::t('app', 'BMC Collection') . "'
        },
        xAxis: [{
                categories: " . json_encode($bar_chart_bmc) . ",
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
                    format: '{value} ltr'
                },
                opposite: false,
                tickInterval: 200
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
                name: '" . Yii::t('app', 'Morning') . "',
                type: 'column',
                color: '#3a7bd5',
                yAxis: 1,
                data: bmc_mcollection,
                tooltip: {
                    valueSuffix: ' lt'
                }

            },{
                name: '" . Yii::t('app', 'Evening') . "',
                type: 'column',
                color: '#1758',
                yAxis: 1,
                data: bmc_ecollection,
                tooltip: {
                    valueSuffix: ' lt'
                }

            }]
    });
}
        
$('#bmc-datewise').hide();
$('.society-datewise').on('click',function() {
    $('#society-datewise').show();
    $(this).addClass('active');
    $('#bmc-datewise').hide();
    $('.bmc-datewise').removeClass('active');
});
$('.bmc-datewise').on('click',function() {
    $('#bmc-datewise').show();
    $(this).addClass('active');
    $('#society-datewise').hide();
    $('.society-datewise').removeClass('active');
});
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
    $.ajax({
        type: 'post',
        url: url,
        data: datastring+'&sp='+sp_name+'&union='+union+'&title='+title,
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
    $('#pageloader').show();
    $('#loadercontent').show();
    var datastring = $('#'+id).serialize();
    var sp_name = id;
    var union= $('#dashboard-union_code').val();
    var popup = 'allow_popup';
    $.ajax({
        type: 'post',
        url: url,
        data: datastring+'&sp='+sp_name+'&union='+union+'&popup='+popup,
        success: function(data) {
//            console.log(id+'_container');
            $('#'+id+'_container').html(data);
            $('#loadercontent').hide();
            $('#pageloader').hide();
        },
        error:function(data){
            $('#loadercontent').hide();
            $('#pageloader').hide();
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
";
$this->registerJs($script, View::POS_READY, 'village-code');
