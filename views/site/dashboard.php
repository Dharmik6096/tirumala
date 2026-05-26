<?php
$this->title = 'Dashboard';

use app\models\TblDashboardWidgets;
use Symfony\Component\Console\Input\Input;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$client_code = \Yii::$app->session->get('eiplCode');
$client_code = !empty($client_code) ? $client_code : '';
$client_code = strtolower($client_code);
$imageIconPathClient = $this->theme->getUrl('/assets/' . $client_code . '/images/dashboard/');
$imageIconPathEipl = $this->theme->getUrl('/assets/images/dashboard/');
$imageIconPath = is_dir(\Yii::$app->basePath . '/../' . $imageIconPathClient) ? $imageIconPathClient : $imageIconPathEipl;

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

$plant_selected_widgets = !empty($userPlantWidgets) ? $userPlantWidgets : [];
$plant_unselected_widgets = array_diff(!empty($plantWidgets) ? $plantWidgets : [], $plant_selected_widgets);
$allPlantWidgets = array_merge($plant_selected_widgets, $plant_unselected_widgets);

$farmer_selected_popup = !empty($userFarmerPopup) ? $userFarmerPopup : [];
$rmrd_selected_popup = !empty($userRmrdPopup) ? $userRmrdPopup: [];
$plant_selected_popup = !empty($userPlantPopup) ? $userPlantPopup: [];

if ($widget_type == 'farmer'){
    $farmerSelectedWidget = array_flip($farmer_selected_widgets);
    $farmerSelectedPopup = array_flip($farmer_selected_popup);
    $mergeWidgets = array_merge($farmerSelectedWidget, $farmerSelectedPopup);
    $lazy_loading_widgets = json_encode(array_flip($mergeWidgets));
}

if ($widget_type == 'rmrd'){
    $rmrdSelectedWidgets = array_flip($rmrd_selected_widgets);
    $rmrdSelectedPopup = array_flip($rmrd_selected_popup);
    $mergeWidgets = array_merge($rmrdSelectedWidgets, $rmrdSelectedPopup);
    $lazy_loading_widgets = json_encode(array_flip($mergeWidgets));
}

if ($widget_type == 'plant'){
    $plantSelectedWidgets = array_flip($plant_selected_widgets);
    $plantSelectedPopup = array_flip($plant_selected_popup);
    $mergeWidgets = array_merge($plantSelectedWidgets, $plantSelectedPopup);
    $lazy_loading_widgets = json_encode(array_flip($mergeWidgets));
}

// var_dump($widget_type);
// var_dump($lazy_loading_widgets);die;
$dashboard_widget = new TblDashboardWidgets();
$unionCode = !empty($model->union_code) ? $model->union_code : '';
$mccCode = !empty($model->mcc_code) ? $model->mcc_code : '';
$bmcCode = !empty($model->bmc_code) ? $model->bmc_code : '';
$dcsCode = !empty($model->dcs_code) ? $model->dcs_code : '';
$memberCode = !empty($model->member_code) ? $model->member_code : '';
$model->dup_search_date = !empty($model->date) ? $model->date : Yii::$app->controls->view_date(date('Y-m-d'));
$model->dpu_status = !empty($model->dpu_status) ? $model->dpu_status : -1;
$model->dpu_shift = !empty($model->dpu_shift) ? $model->dpu_shift : 1;
$enableDashboardPopup = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'enable_dashboard_popup');
$shift = Yii::$app->general->getShiftName($model->shift);
$user_type = Yii::$app->session->get('UserType');
?>

<div class="panel-group row panel-fixed dashboard_search_filter" id="filter">
    <div class="panel panel-default min_h_0">
        <div id="collapse1" >
            <div class="panel-body">
                <div class="col-sm-12 padding_left_right_0">
                    <?php
                    $form = ActiveForm::begin([
                                'action' => ['index'],
                                'method' => 'post',
                    ]);
                    ?>
                    <span class="searchFilterArea col-sm-12 dashboardWidgetHeader">
                        <!-- <span class="searchFilterHeader"><?php //Yii::t('app', 'Date')                                      ?>: </span> -->
                        <div class="col-sm-2 searchFilterHeader">
                            <?= Yii::$app->controls->date($model, $form, 'date', '', true, false, false, false); ?>
                        </div>
                        <div class="col-sm-1 searchFilterHeader">
                            <?= Yii::$app->dropdown->dropdown('shift', $model, $form, 'form-group padding-right-5 col-sm-12 shift', false, false, ''); ?>
                        </div>
                        <?= Html::activeHiddenInput($model, 'widget_type', ['id' => 'hidden_widget_type']) ?>
                        <div class="col-sm-2 searchFilterHeader">
                            <div class="switch-field">
                                <input type="radio" id="radio-farmer" class="radio_widgit_type" name="widget_type" value="farmer"/>
                                <label for="radio-farmer"><?= Yii::t('app', 'DCS') ?></label>
                                <input type="radio" id="radio-rmrd" class="radio_widgit_type" name="widget_type" value="rmrd" />
                                <label for="radio-rmrd"><?= Yii::t('app', 'RMRD') ?></label>
                                <?php if ($user_type <= 4) { ?>
                                    <input type="radio" id="radio-plant" class="radio_widgit_type" name="widget_type" value="plant" />
                                    <label for="radio-plant"><?= Yii::t('app', 'PLANT') ?></label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-2 searchFilterHeader">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', false); ?>
                        </div>
                        <div class="col-sm-2 searchFilterHeader">
                            <?php echo Html::hiddenInput('load_all', true, ['id' => 'load_all']); ?>
                            <?= Yii::$app->dropdown->union_mcc($model, $form, 'dashboard-union_code,load_all', 'mcc_code', false, false, false); ?>
                        </div>
                        <div class="col-sm-1 searchFilterHeader widget_filter_margin padding_left_right_0">
                            <button type="button" class="widget_table_setting_btn" data-toggle="collapse" data-target="#modal_widget_selection"><i class="fa fa-cog faa-spin animated faa-slow"></i></button>
                        </div>
                        <div class="col-sm-1 searchFilterHeader widget_filter_margin padding_left_right_0">
                            <?= Yii::$app->controls->search(); ?>
                        </div>
                        <div class="col-sm-1 searchFilterHeader widget_filter_margin padding_left_right_0">
                            <a class="member-mobile-info pull-Left pie_chart_icon"><i class="fa fa-mobile" title="Member Mobile Info."></i></a>
                        </div>
                        <div class="col-sm-1 searchFilterHeader dup_data_icon_margin padding_left_right_0">
                            <a type="button" class="pull-Left dpu_data_icon pie_chart_icon" data-toggle="collapse" data-target="#dpu_widget_filter"><img src="<?= $imageIconPath . 'dpu_data.png' ?>"></img></a>
                            <!-- <a class="dpu_data_popup"></a> -->
                        </div>
                    </span>

                    <div class="collapse" id="modal_widget_selection">   
                        <?php if (!empty($enableDashboardPopup)) { ?>
                            <div class='col-sm-6 padding_right_0'>
                                <div class='col-sm-9 widget_label_box padding_right_0'><div>Name</div> </div>
                                <div class='col-sm-3 widget_label_box'><div>Show PopUp</div></div>
                            </div>
                            <div class='col-sm-6 padding_left_0'>
                                <div class='col-sm-9 widget_label_box padding_right_0'><div>Name</div> </div>
                                <div class='col-sm-3 widget_label_box'><div>Show PopUp</div></div>
                            </div>
                        <?php } ?>      
                        <?php
                        if ($user_type <= 4) {
                            echo $form->field($model, 'plant_widgets[]')->checkboxList(
                                    $allPlantWidgets, [
                                'id' => 'plant_widgets_list',
                                'class' => 'row sortable',
                                'item' =>
                                function ($index, $label, $name, $checked, $value) use ($allPlantWidgets, $plant_selected_widgets, $model, $dashboard_widget, $plant_selected_popup, $enableDashboardPopup) {
                                    //                var_dump(count($map_model));exit;
                                    $checked = in_array($label, $plant_selected_widgets);
                                    $dispLabel = '';
                                    $dispLabel = $dashboard_widget->getWidgetLabel($label, 'plant');
                                    if (empty($dispLabel)) {
                                        return '';
                                    } else {
                                        $selectedPopup = in_array($label, $plant_selected_popup);
                                        $className = 'Dashboard';
                                        $plant_value = [];
                                        $output = "<div class='col-sm-6 dcs-checklist checklist'><div class='checkbox widgets_checkbox'>" . Html::checkbox($name, $checked, [
                                                    'value' => $label,
                                                    'id' => 'plant_' . $label,
                                                    'label' => '<label for="plant_' . $label . '">' . $dashboard_widget->getWidgetLabel($label, 'plant') . '</label>',
                                                    'labelOptions' => [
                                                        'class' => 'widgets-text' //. $disabled,
                                                    ],
                                                    'class' => 'widgets-checkbox',
                                        ]);
                                        if (!empty($enableDashboardPopup) && in_array($label, $plant_value)) {
                                            $output .= Html::checkbox($className . '[plant_widgets_after][]', $selectedPopup, [
                                                        'value' => $label,
                                                        'id' => 'plant_' . $label . '_after',
                                                        'label' => '<label for="plant_' . $label . '_after" class="widgets-text"></label>', // Label for the second checkbox
                                                        'class' => 'widgets-checkbox',
                                                        'labelOptions' => [
                                                            'class' => 'right_align_date mr-2',],
                                            ]);
                                        }
                                        $output .= "</div></div>";
                                        return $output;
                                    }
                                },
                                    ]
                            )->label(false);
                        }
                        ?>

                        <?php
                        echo $form->field($model, 'rmrd_widgets[]')->checkboxList(
                                $allRmrdWidgets, [
                            'id' => 'rmrd_widgets_list',
                            'class' => 'row sortable',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($allRmrdWidgets, $rmrd_selected_widgets, $model, $dashboard_widget, $rmrd_selected_popup, $enableDashboardPopup) {
                                //                var_dump(count($map_model));exit;
                                $checked = in_array($label, $rmrd_selected_widgets);
                                $dispLabel = '';
                                $dispLabel = $dashboard_widget->getWidgetLabel($label, 'rmrd');
                                if (empty($dispLabel)) {
                                    return '';
                                } else {
                                    $selectedPopup = in_array($label, $rmrd_selected_popup);
                                    $className = 'Dashboard';
                                    $rmrd_value = ['dashboard_farmer_rmrd_blocks'];
                                    // $check = $model->getDistrictUsed($allowWidgets, $label);
                                    // $disabled = ($checked == 1 && $check == 1) ? ' disabled' : '';
                                    $output = "<div class='col-sm-6 dcs-checklist checklist'><div class='checkbox widgets_checkbox'>" . Html::checkbox($name, $checked, [
                                                'value' => $label,
                                                'id' => 'rmrd_' . $label,
                                                'label' => '<label for="rmrd_' . $label . '">' . $dashboard_widget->getWidgetLabel($label, 'rmrd') . '</label>',
                                                'labelOptions' => [
                                                    'class' => 'widgets-text' //. $disabled,
                                                ],
                                                'class' => 'widgets-checkbox',
                                            ]);
                                    if (!empty($enableDashboardPopup) && in_array($label, $rmrd_value)) {
                                        $output .= Html::checkbox($className . '[rmrd_widgets_after][]', $selectedPopup, [
                                                    'value' => $label,
                                                    'id' => 'rmrd_' . $label . '_after',
                                                    'label' => '<label for="rmrd_' . $label . '_after" class="widgets-text"></label>', // Label for the second checkbox
                                                    'class' => 'widgets-checkbox',
                                                    'labelOptions' => [
                                                        'class' => 'right_align_date mr-2',                                                    ],
                                        ]);
                                    }
                                    $output .= "</div></div>";
                                    return $output;
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
                            function ($index, $label, $name, $checked, $value) use ($allFarmerWidgets, $farmer_selected_widgets, $model, $dashboard_widget, $farmer_selected_popup, $enableDashboardPopup) {
                                //                var_dump(count($map_model));exit;
                                $checked = in_array($label, $farmer_selected_widgets);
                                $dispLabel = '';
                                $dispLabel = $dashboard_widget->getWidgetLabel($label, 'farmer');
                                if (empty($dispLabel)) {
                                    return '';
                                } else {
                                    $selectedPopup = in_array($label, $farmer_selected_popup);
                                    $className = 'Dashboard';
                                    $farmer_value = ['dashboard_farmer_status', 'dashboard_farmer_rmrd_blocks'];
                                    // $check = $model->getDistrictUsed($allowWidgets, $label);
                                    // $disabled = ($checked == 1 && $check == 1) ? ' disabled' : '';
                                    $output = "<div class='col-sm-6 dcs-checklist checklist'><div class='checkbox widgets_checkbox'>" . Html::checkbox($name, $checked, [
                                                'value' => $label,
                                                'id' => 'farmer_' . $label,
                                                'label' => '<label for="farmer_' . $label . '">' . $dashboard_widget->getWidgetLabel($label, 'farmer') . '</label>',
                                                'labelOptions' => [
                                                    'class' => 'widgets-text' //. $disabled,
                                                ],
                                                'class' => 'widgets-checkbox',
                                    ]);
                                    if (!empty($enableDashboardPopup) && in_array($label, $farmer_value)) {
                                        $output .= Html::checkbox($className . '[farmer_widgets_after][]', $selectedPopup, [
                                                    'value' => $label,
                                                    'id' => 'farmer_' . $label . '_after',
                                                    'label' => '<label for="farmer_' . $label . '_after" class="widgets-text"></label>', // Label for the second checkbox
                                                    'class' => 'widgets-checkbox',
                                                    'labelOptions' => [
                                                        'class' => 'right_align_date mr-2',                                                    ],
                                        ]);
                                    }
                                    $output .= "</div></div>";
                                    return $output;
                                }
                            },
                                ]
                        )->label(false);
                        ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="collapse" id="dpu_widget_filter">
                        <?php
                        $form = ActiveForm::begin([
                                    'action' => ['index'],
                                    'id' => 'dpu_search_filter'
                        ]);
                        ?>
                        <div class="col-sm-8 padding_left_right_0">
                            <span class="col-sm-12 background_shadow float_right dashboardWidgetHeader">
                                                            <!-- <span class="searchFilterHeader"><?php //Yii::t('app', 'Date')                                      ?>: </span> -->
                                <div class="col-sm-6 searchFilterHeader">
                                    <?= Yii::$app->controls->date($model, $form, 'dup_search_date', '', true, false, false, false); ?>
                                </div>
                                <div class="col-sm-6 searchFilterHeader">
                                    <?= Yii::$app->dropdown->shift($model, $form, 'dpu_shift', false); ?>
                                </div>
                                <div class="col-sm-6 searchFilterHeader">
                                    <?= Yii::$app->dropdown->dropdownStatic('dpu_status', $model, $form, '', false, false, 'dpu_status', false); ?>
                                </div>
                                <div class="col-sm-2 searchFilterHeader">
                                    <?= Yii::$app->controls->custombutton('Search', 'javascript:void(0)', false, 'dashboardDPUSearch'); ?>
                                </div>
                            </span>
                        </div>
                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="clearfix"></div>

<div class="panel panel-default panel-main panel-dashboard mt34">
    <div class="panel-body dashboard_section">
        <div class="row">
            <?php //if (Yii::$app->session->get('organizations_type') !== 'UNION') {   ?>
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
            <?php //$this->render('_dashborad_filter', ['model' => $model, 'id' => 'fed_datewise', 'url' => $chart_url, 'container' => 'fed_datewise_container', 'date_range' => true, 'range2' => false, 'shift' => false, 'type' => 'column', 'title' => 'Datewise Milk Collection']);   ?>
                    <div id="fed_datewise_container" class="cont"></div>
                </div>
            </div> -->
            <?php //} else {   ?> 
            <div class="row">




            </div>
            <?php //}   ?>
        </div>

        <?php
        $selected_widgets = ($widget_type == 'farmer') ? $farmer_selected_widgets : (($widget_type == 'plant') ? $plant_selected_widgets : $rmrd_selected_widgets);
        $all_widgets = ($widget_type == 'farmer') ? $farmerWidgets : (($widget_type == 'plant') ? $plantWidgets : $rmrdWidgets);
        if (!empty($selected_widgets)) {
            foreach ($selected_widgets as $key => $value) {
                if (in_array($value, $all_widgets)) {
                    echo $this->render('widget_dashboard_' . $value, ['model' => $model, 'union' => $unionCode, 'date' => $date, 'table_url' => $table_url, 'container_url' => $container_url, 'class_cols' => $class_cols, 'display' => $display, 'display_rmrd' => $display_rmrd, 'chart_url' => $chart_url, 'dashboard_society_status_pie_chart' => $dashboard_society_status_pie_chart, 'bmc_code' => $bmcCode, 'dcs_code' => $dcsCode, 'member_code' => $memberCode]);
                }
            }
        }
        // echo $this->render('widget_dashboard_milk_analysis', ['model' => $model, 'date' => $date, 'table_url' => $table_url, 'container_url' => $container_url, 'class_cols' => $class_cols, 'display' => $display, 'display_rmrd' => $display_rmrd, 'chart_url' => $chart_url]);
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

<div id="DPU_data_modal" class="modal fade" role="dialog">
    <div class="modal-dialog custom_width_dup_modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <div onclick="exportThisWithParameter('dup_collection_table', '<?= $this->title ?>')" class="widget_table_search_btn_right_margin mis_custom_report"><i class="fa fa-file-excel-o"></i></div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'DPU Data'); ?></h4>
            </div>
            <div class="modal-body" id='DPU_data_container'>
                <?php
                echo $this->render('_dashboard_collection_widget', ['model' => $model]);
                ?>
            </div>
        </div>

    </div>
</div>
<div id="crossTabDetails"></div>
<div id="chartToTable"></div>

<?php
if (!empty($enableDashboardPopup) && (($widget_type == 'farmer' && !Yii::$app->session->get('dashboardFarmerPopup') && !empty($farmer_selected_popup)) 
        || ($widget_type == 'rmrd' && !Yii::$app->session->get('dashboardRmrdPopup') && !empty($rmrd_selected_popup))
        || ($widget_type == 'plant' && !Yii::$app->session->get('dashboardPlantPopup') && !empty($plant_selected_popup))
    )) :
    $title = ($widget_type == 'farmer') ? Yii::t('app', 'DCS') : (($widget_type == 'plant') ? Yii::t('app', 'PLANT') : Yii::t('app', 'RMRD'));
    ?>
    <div class="modal fade" id="dashboardFarmerPopup" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog custom_width_dup_modal">
            <div class="modal-content">
                <div class="modal-header font-large"> <?= strtoupper($title) . ' : ' . Yii::$app->controls->view_date($date) . '(' . $shift . ')' ?>
                    <?php echo Html::button(Yii::t('app', 'OK'), ['class' => 'btn btn-primary pop_button', 'id' => 'close']); ?>
                </div>
                <div class="modal-body widget_popup">
                    <?php
                    $selected_popup = ($widget_type == 'farmer') ? $farmer_selected_popup : (($widget_type == 'plant') ? $plant_selected_popup : $rmrd_selected_popup);
                    $all_widgets = ($widget_type == 'farmer') ? $farmerWidgets : (($widget_type == 'plant') ? $plantWidgets : $rmrdWidgets);
                    if (!empty($selected_popup)) {
                        foreach ($selected_popup as $key => $value) {
                            if (in_array($value, $all_widgets)) {
                                echo $this->render('widget_dashboard_' . $value, ['model' => $model, 'union' => $unionCode, 'date' => $date, 'table_url' => $table_url, 'container_url' => $container_url, 'class_cols' => $class_cols, 'display' => $display, 'display_rmrd' => $display_rmrd, 'chart_url' => $chart_url, 'dashboard_society_status_pie_chart' => $dashboard_society_status_pie_chart, 'bmc_code' => $bmcCode, 'dcs_code' => $dcsCode, 'member_code' => $memberCode, 'append_id' => '_popup']);
                            }
                        }
                    }
                    if ($widget_type == 'farmer') {
                        Yii::$app->session->set('dashboardFarmerPopup', true);
                    } elseif ($widget_type == 'rmrd') {
                        Yii::$app->session->set('dashboardRmrdPopup', true);
                    } elseif ($widget_type == 'plant') {
                        Yii::$app->session->set('dashboardPlantPopup', true);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#dashboardFarmerPopup').modal('show');

            $('#close').on('click', function() {
                $('#dashboardFarmerPopup').modal('hide');
            });
        });
    </script>
<?php endif; ?>

<?php
$script = "  
$(document).ready(function() {
    $('#dashboardFarmerPopup').modal({ 
        backdrop: 'static', 
        keyboard: false 
    });
    $('#dashboardFarmerPopup').modal('show');
});
    var clientCodeForData = '" . $client_code . "';
$( '.sortable' ).sortable();
$('.widget_table_setting_btn').click(function(){
    $('#dpu_widget_filter').removeClass('in');
});

$('.dpu_data_icon').click(function(){
    $('#modal_widget_selection').removeClass('in');
});

    $(document).ready(function() {
        if('" . $widget_type . "' == '' || '" . $widget_type . "' == 'farmer'){
            $('#hidden_widget_type').val('farmer');
            $('#radio-farmer').prop('checked', true);
            $('#rmrd_widgets_list').hide();
            $('#plant_widgets_list').hide();
            $('#farmer_widgets_list').show();
        }else if('" . $widget_type . "' == 'plant'){
            $('#hidden_widget_type').val('plant');
            $('#radio-plant').prop('checked', true);
            $('#plant_widgets_list').show();
            $('#rmrd_widgets_list').hide();
            $('#farmer_widgets_list').hide();
        }
        else{
            $('#hidden_widget_type').val('" . $widget_type . "');
            $('#radio-rmrd').prop('checked', true);
            $('#rmrd_widgets_list').show();
            $('#farmer_widgets_list').hide();
            $('#plant_widgets_list').hide();
        }
        var position = '';
        var widgets = '" . $lazy_loading_widgets . "';
        var widget = $.parseJSON(widgets);
        var timeOut = 500;
        $.each(widget, function(index, value) {
        
            setTimeout(() => {
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
                    'dashboard_society_status_pie_chart',
                    'dashboard_farmer_rmrd_blocks',
                    'mobile_analysis_dashboard_blocks',
                    'mobile_analysis_dashboard_pie_charts',
                    'complain_summary_dashboard',
                    'iot_temperature',
                    'mcc_wise_indent_summary',
                    'today_vs_yesterday_collection',
                    'dashboard_farmer_status',
                    'intransit_tanker_milk_detail',
                    'plant_wise_tanker_status',
                    'vehicle_wise_tanker_status',
                    'plant_wise_tanker_milk_detail',
                    'intransit_tanker_status_detail',
                    'plant_tanker_capacity_wise_tanker_status',
                    'feed_summary_dashboard',
                    'dashboard_farmer_rmrd_avg','BmcWiseCrossTab','tbl_hits_counts','tbl_collc_count_summary','month_calendar','milk_analysis_grid','milk_analysis_vertical','milk_collection_summary','shift_wise_status_detail','cc_plant_wise_tanker_qty_status_detail','trip_wise_tanker_time_details','intransit_hours','inside_plant_tankers'].indexOf(value) == -1) 
                    {
                        setChartWidgets(value);
                    }

                else if(['dashboard_blocks'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'dashboard_blocks';
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
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
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                      var shift= $('#dashboard-shift').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
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
                                var pourerMember = obj1.res.pourerMember;
                                var totalMember = obj1.res.totalMember;
                                var percentage = ((pourerMember * 100) / totalMember).toFixed(2);
                                $('#farmer_rmrd_block_union').text(obj1.res.pourerUnion+'/'+obj1.res.totalUnion);
                                $('#farmer_rmrd_block_mcc').text(obj1.res.pourerMcc+'/'+obj1.res.totalMcc);
                                $('#farmer_rmrd_block_bmc').text(obj1.res.pourerBmc+'/'+obj1.res.totalBmc);
                                $('#farmer_rmrd_block_dcs').text(obj1.res.pourerDcs+'/'+obj1.res.totalDcs);
                                $('#farmer_rmrd_block_farmer').text(obj1.res.pourerMember+'('+percentage+'%)/'+obj1.res.totalMember);
                                $('#farmer_rmrd_block_blk_vendor').text(obj1.res.pourerBulkVen+'/'+obj1.res.totalBulkVen);
                                $('#farmer_rmrd_block_vlcc_vendor').text(obj1.res.pourerVlccVen+'/'+obj1.res.totalVlccVen);
                                $('#farmer_rmrd_block_quantity').text(obj1.res.totalQty+' | '+obj1.res.PreviousDatetotalQty);
                                $('#farmer_rmrd_block_fatkg').text(obj1.res.fatKg+' | '+obj1.res.fatAvg);
                                $('#farmer_rmrd_block_snfkg').text(obj1.res.snfKg+' | '+obj1.res.snfAvg);
                                $('#farmer_rmrd_block_amount').text(obj1.res.amount+' | '+obj1.res.effrtpl+' | '+obj1.res.rtpl);
                                $('#farmer_rmrd_block_ts_kg_tab').text(obj1.res.ts_kg_tab);
                                $('#totle_app').text(obj1.res.app);
                                $('#totle_ws').text(obj1.res.ws);
                                $('#totle_ma').text(obj1.res.ma);
                                $('#farmer_rmrd_block_union_popup').text(obj1.res.pourerUnion+'/'+obj1.res.totalUnion);
                                $('#farmer_rmrd_block_mcc_popup').text(obj1.res.pourerMcc+'/'+obj1.res.totalMcc);
                                $('#farmer_rmrd_block_bmc_popup').text(obj1.res.pourerBmc+'/'+obj1.res.totalBmc);
                                $('#farmer_rmrd_block_dcs_popup').text(obj1.res.pourerDcs+'/'+obj1.res.totalDcs);
                                $('#farmer_rmrd_block_farmer_popup').text(obj1.res.pourerMember+'('+percentage+'%)/'+obj1.res.totalMember);
                                $('#farmer_rmrd_block_blk_vendor_popup').text(obj1.res.pourerBulkVen+'/'+obj1.res.totalBulkVen);
                                $('#farmer_rmrd_block_vlcc_vendor_popup').text(obj1.res.pourerVlccVen+'/'+obj1.res.totalVlccVen);
                                $('#farmer_rmrd_block_quantity_popup').text(obj1.res.totalQty+' | '+obj1.res.PreviousDatetotalQty);
                                $('#farmer_rmrd_block_fatkg_popup').text(obj1.res.fatKg+' | '+obj1.res.fatAvg);
                                $('#farmer_rmrd_block_snfkg_popup').text(obj1.res.snfKg+' | '+obj1.res.snfAvg);
                                $('#farmer_rmrd_block_amount_popup').text(obj1.res.amount+' | '+obj1.res.effrtpl+' | '+obj1.res.rtpl);
                                $('#farmer_rmrd_block_ts_kg_tab_popup').text(obj1.res.ts_kg_tab);
                                $('#totle_app_popup').text(obj1.res.app);
                                $('#totle_ws_popup').text(obj1.res.ws);
                                $('#totle_ma_popup').text(obj1.res.ma);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
                else if(['mobile_analysis_dashboard_blocks'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'proc_mobile_user_count_list';
                        
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-dashboard-mobile-data']) . "',
                        data: blockDataString+'&sp='+id,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success')
                            {
                                for (var key in obj1.res){
                                    if(obj1.res[key] == null){
                                        obj1.res[key] = 0;
                                    }
                                }
                                $('#mobile_block_mpp').text(obj1.res.total_app_installed_mpp+'/'+obj1.res.total_mpp);
                                $('#mobile_block_member').text(obj1.res.total_app_installed_member+'/'+obj1.res.total_member);
                                $('#mobile_block_employee').text(obj1.res.total_app_installed_employee+'/'+obj1.res.total_employee);
                                $('#mobile_block_supervisor').text(obj1.res.total_app_installed_supervisor+'/'+obj1.res.total_supervisor);
                                $('#mobile_block_manager').text(obj1.res.total_app_installed_az_manager+'/'+obj1.res.total_az_manager);
                                $('#mobile_block_other_staff').text(obj1.res.total_app_installed_other_Staff+'/'+obj1.res.total_other_Staff);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
                else if(['mobile_analysis_dashboard_pie_charts'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'proc_mobile_user_count_and_list';
                        
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-mobile-pie-chart']) . "',
                        data: blockDataString+'&sp='+id,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success')
                            {
                              $.each(obj1.series, function(index, value) {   
                                drawPieChart(index,value);
                              });
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
                else if(['complain_summary_dashboard'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'proc_complain_dashboard_list';
                        
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/complain-summary-dashboard']) . "',
                        data: blockDataString+'&sp='+id,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success'){
                                $('#total_complain').text(obj1.res.total_complain);
                                $('#inprogress_complain').text(obj1.res.inprogress_complain);
                                $('#close_complain').text(obj1.res.close_complain);
                                $('#resolved_complain').text(obj1.res.resolved_complain);
                                $('#today_date').text(obj1.res.today_date);
                                drowBarChart('complain_summary_bar_chart','Complain Summary',obj1.series);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
                else if(['today_vs_yesterday_collection'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'today_vs_yesterday_collection';
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
                    var widget_type= $('#hidden_widget_type').val();
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-dashboard-today-vs-yesterday-collection']) . "',
                        data: blockDataString+'&sp='+id+'&union='+union+'&widget_type='+widget_type,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success')
                            {
                                for (var key in obj1.res){
                                    if(obj1.res[key] == null){
                                        obj1.res[key] = 0;
                                    }
                                }
                                console.log(obj1.res);
                                $('#today_cc_qty').text(obj1.res.today_cc_qty);
                                $('#today_cc_count').text(obj1.res.today_cc_count);
                                $('#today_avg_rate').text(obj1.res.today_avg_rate);
                                
                                $('#yesterday_cc_qty').text(obj1.res.yesterday_cc_qty);
                                $('#yesterday_cc_count').text(obj1.res.yesterday_cc_count);
                                $('#yesterday_avg_rate').text(obj1.res.yesterday_avg_rate);
                               
                                $('#today_total_ltr').text(obj1.res.today_total_ltr);
                                $('#today_rmrd_avg_rate').text(obj1.res.today_rmrd_avg_rate);
                                $('#today_rmrd_cc_count').text(obj1.res.today_rmrd_cc_count);
                                $('#today_rmrd_cc_qty').text(obj1.res.today_rmrd_cc_qty);
                                $('#today_bulk_count').text(obj1.res.today_bulk_count);
                                $('#today_bulk_qty').text(obj1.res.today_bulk_qty);
                                $('#today_vlcc_count').text(obj1.res.today_vlcc_count);
                                $('#today_vlcc_qty').text(obj1.res.today_vlcc_qty);
                               
                               $('#yesterday_total_ltr').text(obj1.res.yesterday_total_ltr);
                                $('#yesterday_rmrd_avg_rate').text(obj1.res.yesterday_rmrd_avg_rate);
                                $('#yesterday_rmrd_cc_count').text(obj1.res.yesterday_rmrd_cc_count);
                                $('#yesterday_rmrd_cc_qty').text(obj1.res.yesterday_rmrd_cc_qty);
                                $('#yesterday_bulk_count').text(obj1.res.yesterday_bulk_count);
                                $('#yesterday_bulk_qty').text(obj1.res.yesterday_bulk_qty);
                                $('#yesterday_vlcc_count').text(obj1.res.yesterday_vlcc_count);
                                $('#yesterday_vlcc_qty').text(obj1.res.yesterday_vlcc_qty);
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
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
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
    //                            console.log(obj1.res);
                                var htmlData = '';
                                htmlData = htmlData + '<tbody>';
                                Object.keys(obj1.res).forEach(function (key){
                                    var dispLabel = obj1.res[key].colType;
                                    if(dispLabel.toLowerCase() == 'dcs') {
                                        dispLabel = '" . Yii::t('app', 'DCS') . "';
                                    } else if(dispLabel.toLowerCase() == 'farmer' || dispLabel.toLowerCase() == 'member') {
                                        dispLabel = '" . Yii::t('app', 'Member') . "';
                                    }
                                    htmlData = htmlData + '<tr>';
                                    htmlData = htmlData + '<td class=\'dashboardWidgetHeader color_fff\' rowspan=\'2\'>AVG/';
                                    htmlData = htmlData + dispLabel;
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
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
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
                                $('#dashboard_farmer_status_collection_not_done').text(obj1.res.collectionNotDone);
                                $('#dashboard_farmer_status_non_functuional_dcs_count').text(obj1.res.nonFunctionalDcsCount);
                                $('#dashboard_farmer_status_complaint_registered').text(obj1.res.complaintReceivedDcsCount);
                                $('#dashboard_farmer_status_non_complaint_registered').text(obj1.res.complaintNonRegisterDcsCount);
                                $('#dashboard_farmer_status_active_dcs_popup').text(obj1.res.activeDcs);
                                $('#dashboard_farmer_status_installed_dcs_popup').text(obj1.res.installedDcs);
                                $('#dashboard_farmer_status_online_dcs_popup').text(obj1.res.onlineDcs);
                                $('#dashboard_farmer_status_offline_dcs_popup').text(obj1.res.offlineDcs);
                                $('#dashboard_farmer_status_online_dcs_e_popup').text(obj1.res.onlineDcsE);
                                $('#dashboard_farmer_status_online_dcs_m_popup').text(obj1.res.onlineDcsM);
                                $('#dashboard_farmer_status_collection_not_done_popup').text(obj1.res.collectionNotDone);
                                $('#dashboard_farmer_status_non_functuional_dcs_count_popup').text(obj1.res.nonFunctionalDcsCount);
                                $('#dashboard_farmer_status_complaint_registered_popup').text(obj1.res.complaintReceivedDcsCount);
                                $('#dashboard_farmer_status_non_complaint_registered_popup').text(obj1.res.complaintNonRegisterDcsCount);
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
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
                        $('#calendar .fc-day-grid').html('<div class=\"text-center mt35\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
                        // $('.fc-view-container').addClass('disp_none');
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
                    // eventAfterAllRender: function(view){
                    //     console.log(cal_data);
                    // },
                    dayClick: function(date, jsEvent, view) {
                    var dt=date.format();
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                        var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
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
                else if(['month_calendar'].indexOf(value) == 0){
                    //calendar widget
                    $('#month_calendar').fullCalendar({
                    defaultView: 'year',
                    allDayDefault: false,
                    selectable: true,
                    selectHelper: true,
                    editable: true,
                    eventLimit: true,
                    defaultDate: moment('" . $date . "'),
                    viewRender: function (view, element) {
                    var b = $('#month_calendar').fullCalendar('getDate');
                    var y=b.format('Y');
                    var union= '" . $unionCode . "';
                    var mcc= '" . $mccCode . "';
                        $('#month_calendar .fc-today-button').html('Current Year');
                        // $('.fc-day-grid').html('<div class=\"text-center mt35\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
                        $('#month_calendar .fc-view-container').addClass('disp_none');
                        $.ajax({
                                    type: 'post',
                                    url: '" . Url::to(['/site/load-year-data']) . "',
                                    data: 'y='+y+'&union='+union+'&mcc='+mcc,
                                    success: function(data) {

                                        var obj1 = data;
                                        if (obj1.status == 'success')
                                        {
                                            cal_data=obj1.res;                                                   
                                        }
                                        let cview = $('#month_calendar').fullCalendar('getView');  
                                        if(cview.name == 'year'){
                                            setYearData(cal_data);
                                            $('#month_calendar .fc-scroller.fc-day-grid-container').addClass('disp_none');
                                            $('#month_calendar .fc-row .fc-widget-header').addClass('disp_none');
                                            $('#month_calendar .fc-view-container').removeClass('disp_none');
                                        }
                                        else{
                                            $('#month_calendar .fc-row .fc-widget-header').removeClass('disp_none');
                                            $('#month_calendar .fc-scroller.fc-day-grid-container').removeClass('disp_none');
                                            $('#month_calendar .fc-view-container').removeClass('disp_none');
                                        }
                                        cview.unrenderDates();
                                        cview.renderDates();
                                        $(window).trigger('resize'); 
                                    },
                                    error:function(data){
                                                //alert('Your data has not been submitted..Please try again');
                                            }
                        });
                        }
                    });
                }
                //pie chart 
                 else if(['dashboard_society_status_pie_chart'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'dashboard_society_status_pie_chart';
                    var union= '" . $unionCode . "';
                    var mcc= '" . $mccCode . "';
                       $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-pie-chart']) . "',
                        data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
                        success: function(data) {
                            var obj1 = data;
                           // console.log(obj1);
                            if (obj1.status == 'success')
                            {
                                drawPieChart(+obj1.res.onlineDcs,+obj1.res.offlineDcs);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted..Please try again');
                        }
                    });
                }
                 else if(['iot_temperature'].indexOf(value) == 0) {
                    var blockDataString = $('#collapse1 form').serialize();
                    var id = 'iot_temperature';
                    var union = '" . $unionCode . "';
                    var mcc = '" . $mccCode . "';
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-temperature-data']) . "',
                        data: blockDataString + '&union=' + union + '&mcc=' + mcc,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success') {
                                var result = obj1.results;
                                var hours = [];
                                var temperatures = [];
                                $.each(result, function(index, value) {
                                    hours.push(value.hour);
                                    temperatures.push(parseInt(value.temperature, 10));
                                });
                                drawLineChart(id, hours, temperatures);
                            }
                        },
                        error: function(data) {
                            console.error('Error fetching temperature data');
                        }
                    });
                }
                //milk collection summary code
                else if(['milk_collection_summary'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'milk_collection_summary';
                   
                    var widget_type= $('#hidden_widget_type').val();
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/load-dashboard-milk-collection-summary']) . "',
                        data: blockDataString+'&sp='+id+'&widget_type='+widget_type,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success')
                            {
                                for (var key in obj1.res){
                                    if(obj1.res[key] == null){
                                        obj1.res[key] = 0;
                                    }
                                }
                                console.log(obj1.res);
                                $('#today_milk_collection_llpd').text(obj1.res.today_llpd_milk_collection);
                                $('#cumulative_milk_collection_llpd').text(obj1.res.cumulative_llpd_milk_collection);
                                $('#today_fat_quality').text(obj1.res.today_fat_quality);
                                $('#cumulative_fat_quality').text(obj1.res.cumulative_fat_quality);
                                $('#today_snf_quality').text(obj1.res.today_snf_quality);
                                $('#cumulative_snf_quality').text(obj1.res.cumulative_snf_quality);
                                $('#today_tons_feed_supply').text(obj1.res.today_tons_feed_supply);
                                $('#cumulative_tons_feed_supply').text(obj1.res.cumulative_tons_feed_supply);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
                //milk collection summary code complete
                else if(['milk_analysis_vertical'].indexOf(value) == 0 || ['milk_analysis_grid'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var union= '" . $unionCode . "';
    //                var union= $('#dashboard-union_code').val();
                    var mcc= '" . $mccCode . "';
    //                var mcc= $('#dashboard-mcc_code').val();
                    var widget_type= $('#hidden_widget_type').val();
                    parseMilkAnalysis(blockDataString,union,mcc,value);

                }
                
    //             else if(['milk_analysis'].indexOf(value) == 0){
    //                 var blockDataString = $('#collapse1 form').serialize();
    //                 var id= 'dashboard_milk_analysis';
    //                 var union= '" . $unionCode . "';
    // //                var union= $('#dashboard-union_code').val();
    //                 var mcc= '" . $mccCode . "';
    // //                var mcc= $('#dashboard-mcc_code').val();
    //                 var widget_type= $('#hidden_widget_type').val();
    //                 $.ajax({
    //                     type: 'post',
    //                     url: '" . Url::to(['/site/load-dashboard-milk-analysis']) . "',
    //                     data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
    //                     success: function(data) {
    //                         var obj1 = data;
    //                         if (obj1.status == 'success')
    //                         {
                                

    //                         }
    //                     },
    //                     error:function(data){
    //                         //alert('Your data has not been submitted.Please try again');
    //                     }
    //                 });
    //             }
    
                    else if(['mcc_wise_indent_summary'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'mis_mcc_wise_indent_summary'; 
                    var union= '" . $unionCode . "';
                    var mcc= '" . $mccCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/mcc-wise-indent-summary']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success') {
                                  $('#mcc_wise_indent_summary').html(obj1.mcc_wise_indent_summary);
                                }
                            },
                            error:function(data){
                                //alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }
                    else if(['intransit_tanker_milk_detail'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_plant_intransit_tanker_milk_detail'; 
                    var union= '" . $unionCode . "';
//                    var mcc= '" . $mccCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/plant-intransit-tanker-milk-detail']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success'){
                                    $('#Empty_Tankers').text(obj1.res.Empty_Tankers ?? 0);
                                    $('#With_Milk').text(obj1.res.With_Milk ?? 0);
                                    $('#Total_Calculated').text(obj1.res.Total_Calculated ?? 0);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }
                    else if(['intransit_tanker_status_detail'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_plant_intransit_tanker_status_details'; 
                    var union= '" . $unionCode . "';
//                    var mcc= '" . $mccCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/intransit-tanker-status-detail']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success'){
                                    $('#Waiting_for_loading').text(obj1.res.Waiting_for_loading ?? 0);
                                    $('#Loading_Completed').text(obj1.res.Loading_Completed ?? 0);
                                    $('#Total').text(obj1.res.Total ?? 0);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }
                    else if(['plant_wise_tanker_status'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_plant_wise_tanker_status'; 
                    var union= '" . $unionCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/plant-wise-tanker-status']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success') {
                                  $('#plant_wise_tanker_status').html(obj1.plant_wise_tanker_status);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }

                    else if(['vehicle_wise_tanker_status'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_vehicle_wise_tanker_status'; 
                    var union= '" . $unionCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/vehicle-wise-tanker-status']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success') {
                                  $('#vehicle_wise_tanker_status').html(obj1.vehicle_wise_tanker_status);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    } 
                    else if(['plant_wise_tanker_milk_detail'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_plant_wise_tanker_status'; 
                    var union= '" . $unionCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/plant-wise-tanker-milk-detail']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success') {
                                  $('#plant_wise_tanker_milk_detail').html(obj1.plant_wise_tanker_milk_detail);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }
                    else if(['plant_tanker_capacity_wise_tanker_status'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_portal_dashboard_plant_tanker_capacity_wise_tanker_status'; 
                    var union= '" . $unionCode . "';
                        $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/site/plant-tanker-capacity-wise-tanker-status']) . "',
                            data: blockDataString+'&sp='+id+'&union='+union,
                            success: function(data) {
                                var obj1 = data;
                                if (obj1.status == 'success') {
                                  $('#plant_tanker_capacity_wise_tanker_status').html(obj1.plant_tanker_capacity_wise_tanker_status);
                                }
                            },
                            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
                            }
                        });
                    }
                    else if(['feed_summary_dashboard'].indexOf(value) == 0){
                    var blockDataString = $('#collapse1 form').serialize();
                    var id= 'sp_product_dashboard_block';
                    var union= '" . $unionCode . "';                        
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/site/feed-summary-dashboard']) . "',
                        data: blockDataString+'&sp='+id+'&union='+union,
                        success: function(data) {
                            var obj1 = data;
                            if (obj1.status == 'success')
                            {
                                for (var key in obj1.res){
                                    if(obj1.res[key] == null){
                                        obj1.res[key] = 0;
                                    }
                                }
                                
                                $('#opening_balance').text(obj1.res.opening_balance);
                                $('#received').text(obj1.res.received);
                                $('#inventory_transfer').text(obj1.res.inventory_transfer);
                                $('#sale').text(obj1.res.sale);
                                $('#sale_return').text(obj1.res.sale_return);
                                $('#balance_qty').text(obj1.res.balance_qty);
                            }
                        },
                        error:function(data){
                            //alert('Your data has not been submitted.Please try again');
                        }
                    });
                }
            }, timeOut);
            timeOut = timeOut + 3000;
//            console.log(timeOut);
        });
        

    });

    function setChartWidgets(set_widget_id){
        drawChart(set_widget_id,set_widget_id+'_container','{$chart_url}','column');   
       // console.log(set_widget_id);
        // console.log(set_widget_id+'_container');
    }
    //new code
    barChart('bmc_dispatch_widget_container', '" . Yii::$app->controls->view_date($date) . " BMC Dispatch', [], []);
    barChart('milk_coll_widget_container', '" . Yii::$app->controls->view_date($date) . " Milk Collection (Top 5)', [], []);
    barChart('bmc_coll_widget_container', '" . Yii::$app->controls->view_date($date) . " BMC Collection', [], []);
    barChart('reconciliation_chart_widget_container', '" . Yii::$app->controls->view_date($date) . " Reconciliation Chart', [], []);
    
    function barChart(cont, text, xdata, ydata) {
    var bar_chart = $('#' + cont);
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
                    color: '#00A3DE',
                    yAxis: 1,
                    data: ydata,
                    tooltip: {
                        valueSuffix: ' lt'
                    }

                }]
        });
    }
}

function drawChart(id, cntr, url, type)
{
    if (['bmc_union_comparison', 'union_datewise', 'bmc_union_datewise', 'union_comparison', 'member_datewise'].indexOf(id) == -1) {
        var datastring = $('#collapse1 form').serialize();
    } else {
        var datastring = $('#' + id).serialize();
    }
    var union = '" . $unionCode . "';
//            var union= $('#dashboard-union_code').val();
    var mcc = '" . $mccCode . "';
//            var mcc= $('#dashboard-mcc_code').val();
    $.ajax({
        type: 'post',
        url: url,
        data: datastring + '&sp=' + id + '&union=' + union + '&mcc=' + mcc,
        success: function (data) {

            var index = $('#' + cntr).data('highcharts-chart');
            var chart = Highcharts.charts[index];
            var vals = [];
            var color = '00A3DE';
            var suf = '';
            // console.log(chart +'--'+id);
            while (chart.series.length > 0) {
                chart.series[0].remove(false);
            }
            $.each(data.res, function (key, val) {
                vals = val.map(function (x) {
                    return parseFloat(x, 10);
                });
                if (key.toLowerCase() === 'qty')
                {
                    suf = '(ltr)';
                } else
                {
                    suf = '';
                }

                chart.addSeries({
                    type: type,
                    name: key.toUpperCase() + suf,
                    data: vals,
                    yAxis: 1,
                    color: '#' + color,
                }, false);
                color = parseInt(color) + 003333;

            });
            chart.xAxis[0].setCategories(data.lbl[0]);
            chart.redraw();
        },
        error: function (data) {
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
                    color: '#790000',
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
    var union= '" . $unionCode . "';
//    var union= $('#dashboard-union_code').val();
    var mcc= '" . $mccCode . "';
//    var mcc= $('#dashboard-mcc_code').val();
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
    var union= '" . $unionCode . "';
//    var union= $('#dashboard-union_code').val();
    var mcc= '" . $mccCode . "';
//    var mcc= $('#dashboard-mcc_code').val();
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

$(document).on('click','.dpu_data_popup',function(e){
    $('#DPU_data_modal').modal('toggle'); 
});

$('.radio_widgit_type').on('change',function() {
    $('#hidden_widget_type').val($('input[name=widget_type]:checked', '.switch-field').val());

    if($('input[name=widget_type]:checked', '.switch-field').val() == 'farmer'){
        $('#rmrd_widgets_list').hide();
        $('#plant_widgets_list').hide();
        $('#farmer_widgets_list').show();
    }

    if($('input[name=widget_type]:checked', '.switch-field').val() == 'rmrd'){
        $('#farmer_widgets_list').hide();
        $('#plant_widgets_list').hide();
        $('#rmrd_widgets_list').show();
    }
    
    if($('input[name=widget_type]:checked', '.switch-field').val() == 'plant'){
        $('#farmer_widgets_list').hide();
        $('#rmrd_widgets_list').hide();
        $('#plant_widgets_list').show();
    }
});

function setYearData(cal_data){
    for (var key in cal_data) {
        if (cal_data.hasOwnProperty(key)) {
            var k = key.replace(/-/g, '');
            $('#'+k).append('<div class=\"cal-data\"><span class=\"label text-success\" title=\"Avg FAT\">Avg FAT: '+cal_data[key][0]+'</span><span class=\"label text-danger\" title=\"Avg SNF\">Avg SNF: '+cal_data[key][1]+'</span><!--<span class=\"label text-success\" title=\"Kg FAT\">Kg FAT: '+cal_data[key][2]+'</span><span class=\"label text-danger\" title=\"kg SNF\">kg SNF: '+cal_data[key][3]+'</span>--><span class=\"label text-info\" title=\"Qty(ltr)\">Qty(ltr): '+cal_data[key][4]+'</span></div>');
        }
    }
}

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


// $(document).on('click','.fc-year-monthly-td',function(e){
//     // $('#pieChartModal').modal('toggle'); 
//     var month = $(this).children('.fc-year-monthly-name').attr('id');
//     var addition = '-';
//     month = [month.slice(0, 4), addition, month.slice(4)].join('');

//     console.log(month);
//     var html='<div class=\"milk-collection\">'+
//             '<div class=\"table-responsive dashboard_tbl\"><table class=\"table table-striped\">'+
//             '<thead><tr><th>Union</th><th>Villages</th><th>Avg FAT</th><th>Avg SNF</th><th>Milk Collection (ltr)</th></tr></thead>';
//     html=html+'</table></div></div>';
//     $('#calendar_details').html(html);
//     // var union= '" . $unionCode . "';
//     // var mcc= '" . $mccCode . "';

//     // $('#cal_modal-title').html('Data for '+date.format('DD-MM-YYYY'));
//     // $('#calendar_details').html('<div class=\"text-center\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></div>');
//     // $.ajax({
//     //             type: 'post',
//     //             url: '" . Url::to(['/site/load-dcs-data']) . "',
//     //             data: 'dt='+dt+'&union='+union+'&mcc='+mcc,
//     //             success: function(data) {

//     //                 var obj1 = data;
//     //                 if (obj1.status == 'success')
//     //                 {
                    
//     //                     var html='<div class=\"milk-collection\">'+
//     //                     '<div class=\"table-responsive dashboard_tbl\"><table class=\"table table-striped\">'+
//     //                     '<thead><tr><th>Union</th><th>Villages</th><th>Avg FAT</th><th>Avg SNF</th><th>Milk Collection (ltr)</th></tr></thead>';
//     //                 $.each(obj1.res, function(index, value) {
//     //                     html=html+'<tr>'+
//     //                         '<td>'+value.union_name+'</td>'+
//     //                         '<td>'+value.dcs_name+'</td>'+
//     //                         '<td>'+value.AvgFAT+'</td>'+
//     //                         '<td>'+value.AvgSNF+'</td>'+
//     //                         '<td>'+value.total_qty+'</td>'+
//     //                     '</tr>';
//     //                     });
                        
//     //                 html=html+'</table></div></div>';
//     //                 $('#calendar_details').html(html);
//     //                 }
//     //                 else{
//     //                     $('#calendar_details').html('Data not available.');
//     //                 }

//     //             },
//     //             error:function(data){
//     //                         //alert('Your data has not been submitted..Please try again');
//     //                     }
//     // });
//     chartModal.modal('show');
// });


$(document).on('click', '.dashboardDPUSearch', function(){
    $('#loadercontent').show();
    $('#pageloader').show();
    var blockDataString = $('#dpu_widget_filter form').serialize();
    var union= '" . $unionCode . "';
//                var union= $('#dashboard-union_code').val();
    var mcc= '" . $mccCode . "';
//                var mcc= $('#dashboard-mcc_code').val();
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/site/dpu-data-collection']) . "',
        // data: blockDataString+'&union='+union+'&mcc='+mcc,
        data: blockDataString,
        success: function(data) {
            var obj1 = data;
            htmlData = '';
            if (obj1.status == 'success')
            {
                var i = 1;
                obj1.res = JSON.parse(obj1.res);
                $.each(obj1.res, function(key,value) {
                    htmlData = htmlData + '<tr>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+ i++ +'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.ref_code+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.dcs_name+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.collection_date+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.shift+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.No_Of_Sample+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.QTY+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.Pending+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.Error+'</td>';
                    htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.Processed+'</td>';
                    htmlData = htmlData + '</tr>';
                });
            
                $('.dpu_data_collection_tbl').html(htmlData);

                $('#dup_collection_table thead .search_filter td').each( function (i) {
                    var title = $('#dup_collection_table thead .search_filter td').eq( $(this).index() ).text();
                    $(this).html( '<input type=\'text\' placeholder=\'Search '+title+'\' data-index='+i+' />' );
                });
              
                var table = $('#dup_collection_table').DataTable( {
                    paging: false,
                    info: false,
                    ordering: false,
                });
             
                $( table.table().container() ).on( 'keyup', 'thead .search_filter input', function () {
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                });

                $('#DPU_data_modal').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        },
        error:function(data){
            $('#loadercontent').hide();
            $('#pageloader').hide();
            //alert('Your data has not been submitted.Please try again');
        }
    })

    return false;
});

function parseMilkAnalysis(blockDataString,union,mcc,value){
    var id= 'dashboard_milk_analysis';
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/site/load-dashboard-milk-analysis']) . "',
        data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
        success: function(data) {
            var obj1 = data;
            if (obj1.status == 'success' && ['milk_analysis_grid'].indexOf(value) == 0)
            {
                $('tbody.dashboardMilkAnalysis_tbody').html('');
                var table = $('#farmer_rmrd_tbl_container table tbody');
                var i = 0;
                var htmlData = '';

                $.each(obj1.res, function(key,value) {
                    htmlData = htmlData + '<tr>';
                    htmlData = htmlData + '<td>'+value.bmc_name+' '+value.bmc_code+'</td>';
                    htmlData += '<td>' + (obj1.fromDate) + '</td>';
                    htmlData += '<td>' + (obj1.fromShift == 1 ? 'Morning' : (obj1.fromShift == 2 ? 'Evening' : '')) + '</td>';
                    htmlData += '<td>' + (obj1.toDate) + '</td>';
                    htmlData += '<td>' + (obj1.toShift == 1 ? 'Morning' : (obj1.toShift == 2 ? 'Evening' : '')) + '</td>';
                    htmlData = htmlData + '<td>'+value.cc_qty+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_avg_fat+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_avg_snf+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_avg_rate+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_amount+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_no_of_farmers+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_count+'</td>';
                    // htmlData = htmlData + '<td>'+value.cc_online+'</td>';
                    // htmlData = htmlData + '<td>'+value.cc_pendrive+'</td>';
                    // htmlData = htmlData + '<td>'+value.cc_manual+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_qty+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_avg_fat+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_avg_snf+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_avg_rate+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_amount+'</td>';
                    htmlData = htmlData + '<td>'+value.cc_receipt_count+'</td>';
                    if(clientCodeForData != 'gyan') {
                        htmlData = htmlData + '<td>'+value.vendor_qty+'</td>';
                        htmlData = htmlData + '<td>'+value.vendor_avg_fat+'</td>';
                        htmlData = htmlData + '<td>'+value.vendor_avg_snf+'</td>';
                        htmlData = htmlData + '<td>'+value.vendor_avg_rate+'</td>';
                        htmlData = htmlData + '<td>'+value.vendor_amount+'</td>';
                        htmlData = htmlData + '<td>'+value.vendor_count+'</td>';
                        
                        htmlData = htmlData + '<td>'+value.total_qty+'</td>';
                        htmlData = htmlData + '<td>'+value.total_avg_fat+'</td>';
                        htmlData = htmlData + '<td>'+value.total_avg_snf+'</td>';
                        htmlData = htmlData + '<td>'+value.total_avg_rate+'</td>';
                        htmlData = htmlData + '<td>'+value.total_amount+'</td>';
                        htmlData = htmlData + '<td>'+value.total_count+'</td>';
                    }
                    var var_class = 'negative_value';
                    if(value.diff_qty >= 0){
                        var_class = 'positive_vlaue';
                    }
                    htmlData = htmlData + '<td class='+var_class+'>'+value.diff_qty+'</td>';
                    
                    var var_class = 'negative_value';
                    if(value.diff_avg_fat >= 0){
                        var_class = 'positive_vlaue';
                    }
                    htmlData = htmlData + '<td class='+var_class+'>'+value.diff_avg_fat+'</td>';
                    
                    var var_class = 'negative_value';
                    if(value.diff_avg_snf >= 0){
                        var_class = 'positive_vlaue';
                    }
                    htmlData = htmlData + '<td class='+var_class+'>'+value.diff_avg_snf+'</td>';
                    
                    var var_class = 'negative_value';
                    if(value.diff_amount >= 0){
                        var_class = 'positive_vlaue';
                    }
                    htmlData = htmlData + '<td class='+var_class+'>'+value.diff_amount+'</td>';
                    
                    var var_class = 'negative_value';
                    if(parseFloat(value.cc_count) == parseFloat(value.cc_receipt_count)){
                        var_class = 'positive_vlaue';
                    }
                    htmlData = htmlData + '<td class='+var_class+'>'+value.diff_count+'</td>';
                    htmlData = htmlData + '</tr>';
                });
                // htmlData = htmlData + '</body>';
                $('.dashboardMilkAnalysis_tbody').html(htmlData);
            }
            if(obj1.status == 'success' && ['milk_analysis_vertical'].indexOf(value) == 0){
                    $('.dashboardMilkAnalysis_vertical').html('');
                    var table = $('#farmer_rmrd_tbl_container table tbody');
                    var i = 0;
                    var htmlData = '';

                    // $.each(obj1.res, function(key,value) {
                        htmlData = htmlData + '<table id=\'custom_report\' class=\'fht-table table table-striped\'>';
                        htmlData = htmlData + '<thead>';
                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<th class = \'custom_grid_header header_labels\'></td>';
                        htmlData = htmlData + '<th class = \'custom_grid_header header_labels\'></td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<th class = \'custom_grid_header header_labels\'>'+value.bmc_name+' '+value.bmc_code+'</th>';
                        });
                        htmlData = htmlData + '</tr>';
                        htmlData = htmlData + '</thead>';
                        htmlData = htmlData + '<tbody>';
                        
                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td rowspan=\'7\' class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'CC Collection') . "</td>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Qty') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_qty+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'FAT') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_avg_fat+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'SNF') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_avg_snf+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Rate') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_avg_rate+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Amount') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_amount+'</td>';
                        });
                        htmlData = htmlData + '</tr>';


                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'No Of Farmers') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_no_of_farmers+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'CC Count') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_count+'</td>';
                        });
                        htmlData = htmlData + '</tr>';
                       
                        // htmlData = htmlData + '<tr>';
                        // htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Online') . "</td>';
                        // $.each(obj1.res, function(key,value) {
                        //     htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_online+'</td>';
                        // });
                        // htmlData = htmlData + '</tr>';
                        
                        // htmlData = htmlData + '<tr>';
                        // htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Pendrive') . "</td>';
                        // $.each(obj1.res, function(key,value) {
                        //     htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_pendrive+'</td>';
                        // });
                        // htmlData = htmlData + '</tr>';

                        // htmlData = htmlData + '<tr>';
                        // htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Manual') . "</td>';
                        // $.each(obj1.res, function(key,value) {
                        //     htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_manual+'</td>';
                        // });
                        // htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\' rowspan=\'6\'>" . Yii::t('app', 'BMC Receipts') . "</td>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Qty') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_qty+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'FAT') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_avg_fat+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'SNF') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_avg_snf+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Rate') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_avg_rate+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Amount') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_amount+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Count') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.cc_receipt_count+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        if(clientCodeForData != 'gyan') {
                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\' rowspan=\'6\'>" . Yii::t('app', 'Bulk Vendor Receipts') . "</td>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Qty') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_qty+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'FAT') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_avg_fat+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'SNF') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_avg_snf+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Rate') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_avg_rate+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Amount') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_amount+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Count') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.vendor_count+'</td>';
                            });
                            htmlData = htmlData + '</tr>';
                        

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\' rowspan=\'6\'>" . Yii::t('app', 'Total') . "</td>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Qty') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_qty+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'FAT') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_avg_fat+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'SNF') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_avg_snf+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Rate') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_avg_rate+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Amount') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_amount+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                            htmlData = htmlData + '<tr>';
                            htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Count') . "</td>';
                            $.each(obj1.res, function(key,value) {
                                htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value\'>'+value.total_count+'</td>';
                            });
                            htmlData = htmlData + '</tr>';

                        }
                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\' rowspan=\'5\'>" . Yii::t('app', 'CC Differences') . "</td>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Qty') . "</td>';
                        $.each(obj1.res, function(key,value) {
                                var var_class = 'negative_value';
                                if(value.diff_qty >= 0){
                                    var_class = 'positive_vlaue';
                                }
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_qty+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'FAT') . "</td>';
                        $.each(obj1.res, function(key,value) {
                                var var_class = 'negative_value';
                                if(value.diff_avg_fat >= 0){
                                    var_class = 'positive_vlaue';
                                }
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_avg_fat+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'SNF') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            var var_class = 'negative_value';
                            if(value.diff_avg_snf >= 0){
                                var_class = 'positive_vlaue';
                            }
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_avg_snf+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        // htmlData = htmlData + '<tr>';
                        // htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Rate') . "</td>';
                        // $.each(obj1.res, function(key,value) {
                        //     var var_class = 'negative_value';
                        //     if(value.diff_avg_rate >= 0){
                        //         var_class = 'positive_vlaue';
                        //     }
                        //     htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_avg_rate+'</td>';
                        // });
                        // htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Amount') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            var var_class = 'negative_value';
                            if(value.diff_amount >= 0){
                                var_class = 'positive_vlaue';
                            }
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_amount+'</td>';
                        });
                        htmlData = htmlData + '</tr>';

                        htmlData = htmlData + '<tr>';
                        htmlData = htmlData + '<td class = \'custom_grid_header header_labels\'>" . Yii::t('app', 'Count') . "</td>';
                        $.each(obj1.res, function(key,value) {
                            var var_class = 'negative_value';
                            if(parseFloat(value.cc_count) == parseFloat(value.cc_receipt_count)){
                                var_class = 'positive_vlaue';
                            }
                            htmlData = htmlData + '<td class = \'custom_grid_normal dynamic_value '+var_class+'\'>'+value.diff_count+'</td>';
                        });
                        htmlData = htmlData + '</tr>';
                        htmlData = htmlData + '</tbody>';
                        htmlData = htmlData + '</table>';

                    $('.dashboardMilkAnalysis_vertical').html(htmlData);

                    setTimeout(function(){ $('#custom_report').CongelarFilaColumna({Columnas:2}); }, 200);
               }

        },
        error:function(data){
            //alert('Your data has not been submitted.Please try again');
        }
    });
}
";
$this->registerJs($script, View::POS_READY, 'village-code');
?>