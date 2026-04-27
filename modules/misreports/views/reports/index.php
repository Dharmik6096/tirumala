<?php

use app\components\CustomDataTable;
use yii\helpers\Html;
use app\components\ActiveForm;
use app\modules\usermanagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;
use nullref\datatable\DataTable;
use yii\widgets\MaskedInput;
//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->from_date = empty($model->from_date) ? date('d-m-Y') : $model->from_date;
$model->to_date = empty($model->to_date) ? date('d-m-Y') : $model->to_date;
$model->from_shift = empty($model->from_shift) ? 1 : $model->from_shift;
$model->to_shift = empty($model->to_shift) ? 2 : $model->to_shift;
$model->language_code = empty($model->language_code) ? 0 : $model->language_code;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
$defaultToggle = true;
$model->p_date = empty($model->p_date) ? date('d-m-Y') : $model->p_date;
$model->date = empty($model->date) ? date('d-m-Y') : $model->date;
$model->f_spr_date = empty($model->f_spr_date) ? date('d-m-Y') : $model->f_spr_date;
$model->t_spr_date = empty($model->t_spr_date) ? date('d-m-Y') : $model->t_spr_date;
$model->f_cmpr_date = empty($model->f_cmpr_date) ? date('d-m-Y') : $model->f_cmpr_date;
$model->t_cmpr_date = empty($model->t_cmpr_date) ? date('d-m-Y') : $model->t_cmpr_date;
$model->from_time = empty($model->from_time) ? date('H:i') : $model->from_time;
$model->to_time = empty($model->to_time) ? date('H:i') : $model->to_time;
if (isset($data['url1'])) {
    $this->params['menu'][] = Yii::$app->controls->custombutton($data['url1'][0], $data['url1'][1], $data['url1'][2]);
}
$downloadSapFiles = json_encode($fileDownloadArr);
?>
<div class="panel panel-default panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <?php
    $removeExportType = [];
    $exportEvents = [];
    if (isset($data['export_title']) && $data['export_title'] && !empty($result)) {
        $report_type = ($model->report_type == 0) ? Yii::t('app', 'VM') : (($model->report_type == 1) ? Yii::t('app', 'WQ') : Yii::t('app', 'SD'));
        $this->title = $model->mcc_code . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($model->date)) . '_' . $model->shift;
        $removeExportType = ['CSV'];
        $exportEvents = ['onRenderSheet' => function($sheet, $widget) {
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword("password");
            },];
    } else if (!empty($data['removeExportType'])) {
        $removeExportType = $data['removeExportType'];
    }
    if (isset($data['dynamic_label']) && $data['dynamic_label'] && !empty($result)) {
        $codeToAppend = $model->getMccCode($model->mcc_code);
        $fromShift = $model->from_shift == 1 ? 'MORNING' : 'EVENING';
        $toShift = $model->to_shift == 1 ? 'MORNING' : 'EVENING';
        $this->title = $codeToAppend->name . '_' . $codeToAppend->ref_code . '_' . Yii::$app->controls->view_date($model->from_date, 'php:Y-m-d') . ' to ' . Yii::$app->controls->view_date($model->to_date, 'php:Y-m-d') . '_' . $fromShift . '_' . $toShift;
        $removeExportType = ['CSV'];
        $exportEvents = ['onRenderSheet' => function($sheet, $widget) {
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword("password");
            },];
    }
    $this->title = !empty($data['export_file_name']) ? $data['export_file_name'] : $this->title;

    $multiArray = !empty($data['multiArray']) ? $data['multiArray'] : [];
    $reportClass = 'report-area';
    if (!empty($result) && isset($data['kartik_grid_view'])) {
        $reportClass = '';
    }
    $disableCopyClass = '';
    if (!empty($data) && !empty($data['excel_readonly'])) {
        $disableCopyClass = 'disable-copy-class';
    }
    ?>
    <div class="panel-body padding-0">
        <div class="<?= $reportClass ?> not_ellipsis <?= $disableCopyClass ?>">
            <div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $title; ?></h4>
                        </div>
                        <!--<div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">-->
                        <div class="">
                            <?php
                            $form = ActiveForm::begin(['options' => [
                                            'id' => 'report-form',
                                            'field-class' => 'form-group col-sm-6'
                                        ],
                                        'method' => 'get',
                                        'validateOnBlur' => FALSE,
                                        'validateOnChange' => FALSE,
                                        'enableClientValidation' => true,
                                        'validateOnSubmit' => true,
                            ]);
                            ?>    
                            <div class="row margin_0">

                                <div class="modal-body">
                                    <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false);    ?>  
                                    <?php
                                    $param = isset($data['param']) ? explode(',', $data['param']) : [];
                                    foreach ($param as $key => $value) {
                                        $value_array = explode(':', $value);
                                        $value = $value_array[0];

                                        if (isset($value_array[1]) && $value_array[1] == 'string') {
                                            echo ($value == 'date' || $value == 'from_date' || ($value == 'to_date' && isset($value_array[2]))) ? '<div class="clearfix"></div>' : '';
                                            ?>
                                            <div class="col-sm-6 reportDate">
                                                <?php
                                                echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-6 padding-left-5 padding-right-5', false);
                                                ?>
                                            </div>    
                                            <?php
                                            if (isset($value_array[2])) {
                                                ?>
                                                <div class="col-sm-6 shift">
                                                    <?php
                                                    echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'form-group', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                                    ?>
                                                </div>    
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('shift_code'))) {
                                            ?>
                                            <div class="col-sm-6 ShiftHideShow">
                                                <?php
                                                echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'form-group', $model->getAttributeLabel('shift_code'), false, 'shift_code');
                                                ?>
                                            </div>    
                                            <?php
                                        }
                                        if (in_array($value, array('union_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', false, true, true); ?>
                                            </div>   <?php
                                        }
                                        if (in_array($value, array('plant_code'))) {
                                            ?>
                                            <div class="col-sm-6 val_plant_code">
                                                <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodel-union_code', 'plant_code', 'Plant'); ?>
                                            </div>
                                        <?php } if (in_array($value, array('mcc_code'))) { ?>
                                            <div class="col-sm-6 val_mcc_code">
                                                <?php
                                                $multiple = in_array($value, $multiArray) ? true : false;
                                                if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                    Yii::$app->dropdown->union_mcc($model, $form, 'reportsmodel-union_code', $value, $model->getAttributeLabel('mcc_code'), $multiple);
                                                } else {
                                                    echo Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodel-plant_code', $value, Yii::t('app', 'MCC'), $multiple);
                                                }
                                                ?>                
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('bmc_code'))) {
                                            $multiple = in_array($value, $multiArray) ? true : false;
                                            if (isset($value_array[1]) && $value_array[1] == 'channel_code') {
                                                $channelmultiple = isset($value_array[2]) ? FALSE : true;
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->channel_bmc($model, $form, 'reportsmodel-channel_code', 'bmc_code', Yii::t('app', 'BMC'), $channelmultiple); ?>
                                                </div>
                                                <?php
                                            } else if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->union_bmc($model, $form, 'reportsmodel-union_code', 'bmc_code', Yii::t('app', 'BMC'), false); ?>
                                                </div>
                                                <?php
                                            } else if (isset($value_array[1]) && $value_array[1] == 'area_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->area_bmc($model, $form, 'reportsmodel-area_code', 'bmc_code', Yii::t('app', 'BMC'), false); ?>                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-6 val_bmc_code">
                                                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodel-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), $multiple); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('dcs_code'))) {
                                            $multiple = in_array($value, $multiArray) ? true : false;
                                            if (isset($value_array[1]) && $value_array[1] == 'route_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?php
                                                    echo Yii::$app->dropdown->route_dcs($model, $form, 'reportsmodel-route_code', 'dcs_code', Yii::t('app', 'Society'));
                                                    ?>
                                                </div>
                                                <?php
                                            } else if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                ?>
                                                <div class="col-sm-6 val_dcs_code">
                                                    <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'reportsmodel-union_code', '', Yii::t('app', 'Society'), 'dcs_code', false, $multiple); ?>
                                                </div>  
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-6 val_dcs_code">
                                                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodel-bmc_code', 'dcs_code', Yii::t('app', 'Society'), $multiple, '', false, false); ?>                    
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (isset($value_array[1]) && $value_array[1] == 'txt') {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, $value_array[0])->textInput(['maxlength' => true]) ?>
                                            </div>    
                                            <?php
                                        }
                                        if (in_array($value, array('customer_code'))) {
                                            ?>
                                            <div class="col-sm-6 val_dcs_code">
                                                <?= Yii::$app->dropdown->merge_dcs_customer($model, $form, 'reportsmodel-bmc_code', 'customer_code', Yii::t('app', 'Name')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('customer_type'))) {
                                            ?>
                                            <div class="col-sm-6 cust_type">
                                                <?= Yii::$app->dropdown->customer_type($model, $form, 'reportsmodel-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('vendor_code'))) {
                                            $depend = 'reportsmodel-bmc_code,reportsmodel-customer_type';
                                            if (isset($value_array[1])) {
                                                if ($value_array[1] == 'BULKVEN') {
                                                    echo Html::hiddenInput('customer_type', 'BULKVEN', ['id' => 'reportsmodel-customer_type']);
                                                    $depend = 'reportsmodel-bmc_code,reportsmodel-customer_type';
                                                } else {
                                                    $depend = 'reportsmodel-bmc_code,reportsmodel-' . $value_array[1];
                                                }
                                            }
                                            ?>
                                            <div class="col-sm-6 vendor">
                                                <?= Yii::$app->dropdown->customer_code($model, $form, $depend, 'vendor_code', $model->getAttributeLabel('vendor_code'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('main_customer_type'))) {
                                            ?>
                                            <div class="col-sm-6 ">
                                                <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-6', $model->getAttributeLabel('customer_type'), FALSE, 'main_customer_type'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('date_payment_cycle'))) {
                                            echo Html::hiddenInput('customer_type', 'dcs', ['id' => 'reportsmodel-customer_type']);
                                            $where = json_encode(['data_lock_member' => 1]);
                                            echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                                            echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->paymentCycleWithDate($model, $form, 'reportsmodel-union_code,reportsmodel-bmc_code,reportsmodel-customer_type,applicable_for,data_lock_bmc,0,reportsmodel-type_check', 'date_payment_cycle', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('payment_cycle_code'))) {

                                            if (isset($value_array[1]) && isset($value_array[2]) && $value_array[1] == 'default') {
                                                echo Html::hiddenInput('customer_type', $value_array[2], ['id' => 'reportsmodel-customer_type']);
                                                $where = json_encode(['data_lock_member' => 1]);
                                            } else {
                                                echo Html::hiddenInput('customer_type', 'DCS', ['id' => 'reportsmodel-customer_type']);
                                                $where = json_encode(['data_lock_bmc' => 1]);
                                            }
                                            echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                                            echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                                            if (isset($value_array[1]) && $value_array[1] == 'type_check') {
                                                echo Html::hiddenInput('type_check', TRUE, ['id' => 'reportsmodel-type_check']);
                                            }
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'reportsmodel-union_code,reportsmodel-bmc_code,reportsmodel-customer_type,applicable_for,data_lock_bmc,0,reportsmodel-type_check', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('member_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'reportsmodel-dcs_code', '', $model->getAttributeLabel('member')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('basis_on'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('milk_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'milk_type', ['options' => ['class' => 'form-group']])->dropDownList(['1' => 'Cow', '2' => 'Buffalo']);
                                                ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_organization_type'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('p_organization_type'), false, 'p_organization_type') ?> 
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('action_perform'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value, false, TRUE) ?> 
                                            </div>
                                            <?php
                                        }

                                        if (in_array($value, array('rate_type', 'bank_type', 'report_status', 'originating_type', 'type_wise_report', 'route_type_trans', 'sap_file', 'top_collection_on', 'param_type', 'login_type', 'current_status', 'milk_sale_on', 'billing_on', 'dispatch_type', 'is_groupbyserial', 'p_product_type', 'master_type', 'sort_type', 'member_types', 'payment_method', 'report_rate_type', 'amount_variation', 'sort_by', 'edit_type', 'search_by', 'search_type', 'report_sort_by', 'sort_direction', 'farmer_type', 'report_member_type', 'filter_type', 'milk_sort_by', 'society_type', 'status_type', 'farmer_sort_type', 'registered_type', 'soc_type', 'report_status_type', 'sms_type', 'top', 'search_by_soc', 'report_gender', 'manual_type', 'report_app_type'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_purchase_rate_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'rate_type') {
                                                ?>
                                                <div class="col-sm-6 val_dcs_code">
                                                    <?php // Yii::$app->dropdown->org_type_rate($model, $form, 'reportsmodel-p_organization_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code'));       ?>
                                                    <?= Yii::$app->dropdown->memberRateChart($model, $form, 'reportsmodel-union_code,reportsmodel-rate_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code')); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('member_type'))) {
                                            ?>
                                            <div class="col-sm-6 val_dcs_code">
                                                <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel($value), false, 'member_type'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('route_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'all_routes') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->all_routes($model, $form, 'reportsmodel-plant_code,reportsmodel-mcc_code,reportsmodel-bmc_code', 'route_code', Yii::t('app', 'Route')); ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-6 val_dcs_code">
                                                    <?= Yii::$app->dropdown->union_routes($model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $value); ?>                                           
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('store_location_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('store_location_type'), FALSE, 'store_location_type'); ?>

                                            </div>

                                            <?php
                                        }
                                        if (in_array($value, array('asset_code'))) {
                                            $depends = 'reportsmodel-' . $value_array[1];
                                            ?>
                                            <div class="col-sm-6">
                                                <?php
                                                echo Yii::$app->dropdown->depend_dropdown('union_asset', $model, $form, $depends, 'form-group col-sm-6 padding-right-5 padding-left-5', $model->getAttributeLabel('asset_code'), $value);
                                                ?>
                                            </div>

                                            <?php
                                        }
                                        if (in_array($value, array('sap_code'))) {
                                            $depends = 'reportsmodel-' . $value_array[1] . ',' . 'reportsmodel-' . $value_array[2] . ',' . 'reportsmodel-' . $value_array[3] . ',' . 'reportsmodel-' . $value_array[4];
                                            ?>
                                            <div class="col-sm-6">
                                                <?php echo Yii::$app->dropdown->org_sap_code($model, $form, $depends, 'sap_code', $model->getAttributeLabel('sap_code')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('transporter_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('transporter_code')); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('transporter_code'), false, 'transporter_code'); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('vehicle_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'transporter_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'reportsmodel-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code')); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code', $model->getAttributeLabel('vehicle_code')); ?>
                                                </div>
                                                <?php
                                            }
                                        }

                                        if (in_array($value, array('report_collection_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('channel_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('channel', $model, $form, 'form-group col-sm-6', $model->getAttributeLabel($value), false, $value); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('product_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'product_type') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->product($model, $form, 'reportsmodel-union_code,reportsmodel-product_type', $value, 'Product', TRUE); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('sap_batch_no'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->productBatchNo($model, $form, 'reportsmodel-plant_code,reportsmodel-product_code', 'sap_batch_no', 'Batch', false) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('org_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('product_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('module_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->moduleType($model, $form, 'module_type', 'Module Type'); ?>                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('trip_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'vehicle_code') {
                                                ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->depend_dropdown('vehicle_trip', $model, $form, 'reportsmodel-vehicle_code', 'form-group col-sm-4', $model->getAttributeLabel('trip_code')); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->dropdown('trip_code', $model, $form, '', $model->getAttributeLabel($value), false, 'trip_code'); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('grn_no'))) {
                                            ?>
                                            <div class="col-sm-6 val_dcs_code">
                                                <?= Yii::$app->dropdown->dropdown('grn_no', $model, $form, '', $model->getAttributeLabel($value), false, 'grn_no'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('plant_register_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('month', 'report_req_status', 'payment_type', 'rate_cal_for', 'trip_status', 'milk_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('year'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->year($model, $form, 'year', 'Year', '', false, true, 5, 5) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('state_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('state_code', $model, $form, '', 'State Name'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('region_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                $addAll = (in_array('all', $value_array)) ? true : false;
                                                ?>
                                                <div class="col-sm-6 val_region_code">
                                                    <?= Yii::$app->dropdown->depend_dropdown('region', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-12', 'Region', 'region_code', false, 0, [], false, '', false, true, false, true, false, $addAll); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <?= Yii::$app->dropdown->depend_dropdown('region_code', $model, $form, 'reportsmodel-state_code', 'form-group col-sm-12', 'Region Name', 'region_code'); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('area_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->depend_dropdown('area_code', $model, $form, 'reportsmodel-region_code', 'form-group col-sm-12', 'Area Name', 'area_code'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('login_user_code', 'user_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown($value, $model, $form, '', 'User Name', false, '', TRUE); ?>
                                            </div>   <?php
                                        }
                                        if (in_array($value, array('user_login_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic('login_type', $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('Login Type'), false, $value, TRUE, TRUE) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('bill_head_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->bill_head($model, $form, 'reportsmodel-union_code', 'bill_head_code', 'Bill Head', 'U'); ?>       
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('insurance_master_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('insurance_master_list', $model, $form, 'form-group col-sm-2 padding-right-5', $model->getAttributeLabel('insurance_master'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('operation_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdownStatic('action_perform', $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('dispatch_center_type'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('dispatch_center_type', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('dispatch_center_type'), false, 'dispatch_center_type'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('dispatch_center'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dispatchCenterType($model, $form, 'reportsmodel-dispatch_center_type', 'dispatch_center', $model->getAttributeLabel('dispatch_center'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('store_location_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'reportsmodel-store_location_type_all', '', $model->getAttributeLabel('store_location_code')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('store_location_type_all'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('store_location_type'), FALSE, 'store_location_type_all', TRUE); ?>

                                            </div>

                                            <?php
                                        }
                                        if (in_array($value, array('product_group_code'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->depend_dropdown('product_group', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product Group'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('department'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('department', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('department'), false, 'department'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('milk_type_code'))) {
                                            ?>                
                                            <div class = "col-sm-6">
                                                <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('milk_type_code'), false, 'milk_type_code', true); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('language_code'))) {
                                            ?>
                                            <div class="col-sm-12 radio-section">
                                                <?=
                                                $form->field($model, 'language_code')->radioList([0 => 'English', 1 => 'Gujarati'], ['class' => 'radio-container', 'item' => function ($index, $label, $name, $checked, $value) {
                                                        return '<label class="radio-inline">' . Html::radio($name, $checked, ['value' => $value]) . ' ' . $label . '</label>';
                                                    }]);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('from_code'))) {
                                            ?>
                                            <div class="clearfix"></div>
                                            <div class="col-sm-6 val_from_code">
                                                <?= $form->field($model, 'from_code')->textInput(['type' => 'number', 'min' => 1, 'value' => (isset($model->from_code) && $model->from_code != 0) ? $model->from_code : 1]) ?>                             
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('to_code'))) {
                                            ?>
                                            <div class="col-sm-6 val_to_code">
                                                <?= $form->field($model, 'to_code')->textInput(['type' => 'number', 'min' => 1, 'max' => 9999, 'value' => (isset($model->to_code) && $model->to_code !== '') ? $model->to_code : 9999]) ?>                                          
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, ['is_show_zero_val', 'is_group_by_society', 'last_rate', 'group_by_region', 'show_only_received_data', 'show_val'])) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, $value, ['checkboxTemplate' => "<div class='checkbox mt-25'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox() ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('from_soc'))) {
                                            ?>
                                            <div class="clearfix"></div>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'from_soc')->textInput(['type' => 'number', 'min' => 1, 'value' => (isset($model->from_soc) && $model->from_soc != 0) ? $model->from_soc : 1]) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('to_soc'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'to_soc')->textInput(['type' => 'number', 'min' => 1, 'max' => 100, 'value' => (isset($model->to_soc) && $model->to_soc !== '') ? $model->to_soc : 100]) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('region_type'))) {
                                            ?>
                                            <div class="col-sm-6 val_region_code">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('mobile_no'))) {
                                            ?>
                                            <div class="col-sm-6 number-validate MobileHideShow">
                                                <?= $form->field($model, 'mobile_no')->textInput() ?>                                          
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('no_of_farmer_edit'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'no_of_farmer_edit')->textInput(['type' => 'number', 'min' => 0, 'value' => (isset($model->no_of_farmer_edit)) ? $model->no_of_farmer_edit : 0]) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('no_of_individual_farmer_edit'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'no_of_individual_farmer_edit')->textInput(['type' => 'number', 'min' => 0, 'value' => (isset($model->no_of_individual_farmer_edit) && $model->no_of_individual_farmer_edit !== '') ? $model->no_of_individual_farmer_edit : 0]) ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('from_time'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'from_time')->widget(MaskedInput::className(), ['mask' => '99:99', 'value' => (isset($model->from_time) && $model->from_time !== '') ? $model->from_time : date('H:i')]); ?> 
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('to_time'))) {
                                            ?>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'to_time')->widget(MaskedInput::className(), ['mask' => '99:99', 'value' => (isset($model->to_time) && $model->to_time !== '') ? $model->to_time : date('H:i')]); ?> 
                                            </div>
                                            <?php
                                        }
                                    }
                                    if (isset($data['report_type'])) {
                                        echo $form->field($model, 'report_type', ['options' => ['class' => 'form-group col-sm-6']])->dropDownList($data['report_type']);
                                    }
                                    if (isset($data['dynamic'])) {
                                        echo Html::hiddenInput('dynamic_report', $data['dynamic']);
                                    }
                                    ?>   <div class = "clearfix"></div>
                                    <?php
                                    if (!isset($data['output_type'])) {
                                        echo $form->field($model, 'output_type', ['options' => ['class' => 'form-group col-sm-6']])->dropDownList(['DOWNLOAD' => 'DOWNLOAD', 'VIEW' => 'VIEW']);
                                    }
                                    ?>

                                    <div class="modal-footer mt10 col-sm-12">
                                        <?php
                                        if ($param) {
                                            if (!empty($fileDownloadArr)) {
                                                echo Html::hiddenInput('upload_ftp_file', '0', ['id' => 'reportsmodel-upload_ftp_file']);
                                            }
                                            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $class = 'beforeGridLoad';
            if (!empty($result)) {
                $defaultToggle = false;
                if (!(isset($data['download_only'])) && is_array($result)) {
                    $class = '';
                }
            }
            if (!empty($model->getErrors())) {
                $defaultToggle = true;
            }
            $custom_report_class = isset($data['custom_report']) ? 'custom_report_search' : '';
            ?>

            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?> <?= $custom_report_class ?>">
                <div class="btn-login btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
                <?php if (!empty($result) && $model->output_type != 'BACKGROUND' && empty($data['excel_readonly'])) {
                    ?>
                    <div onclick="exportThisWithParameter('custom_report', '<?= $this->title ?>', true)" class="btn-group btn btn-default mis_custom_report"><i class="far fa-file-excel"></i></div>
                    <?php }
                    ?>
            </div>
            <?php if (!empty($result) && !(isset($data['download_only']))) { ?>

                <!--                                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                                                </div>-->
            <?php } ?>
            <?php
            if (!empty($result) && !is_array($result) && !isset($data['custom_report'])) {
                echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
            } else if (!empty($result) && isset($data['custom_report'])) {
                echo $this->render('_dynamic_report', ['result' => $result, 'model' => $model]);
            } else if (!empty($result) && isset($data['kartik_grid_view'])) {
                $attr = [];
                foreach ($result[0] as $att => $value) {
                    $checkAttr = explode('##', $att);
                    $attr_arr = [];
                    $format = 'raw';
                    if (in_array($att, ['Quantity', 'FAT', 'CLR', 'SNF'])) {
                        $format = ['decimal', 2];
                    }
//                    $attr_arr['attribute'] = $att;
                    if (empty($checkAttr[1]) || $checkAttr[0] != $checkAttr[1]) {
                        $attr_arr = [];
                        if (!empty($data['to_decrypt']) && in_array($checkAttr[0], $data['to_decrypt'])) {
                            $attr_arr['value'] = function($model) use ($att) {
                                return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
                            };
                        }

                        $str = ucwords(str_replace('_', ' ', $att));
                        $attr_arr['attribute'] = $att;
                        $attr_arr['label'] = Yii::t('app', $str);
                        $attr_arr['format'] = $format;
                        $attr_arr['filter'] = false;
                        $attr[] = $attr_arr;
//                    $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
                    }
                }
                $grid_option = [
                    'id' => $data['kartik_grid_view'],
                    'attributes' => $attr,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index']);

//                echo $this->render('_dynamic_report', ['result' => $result, 'model' => $model]);
            } else if (!empty($result) && !(isset($data['download_only']))) {
                $attr = [];
                foreach ($result[0] as $att => $value) {
                    $checkAttr = explode('##', $att);
                    $attr_arr = [];
                    $format = 'raw';
                    if (in_array($att, ['Quantity', 'FAT', 'CLR', 'SNF'])) {
                        $format = ['decimal', 2];
                    }
//                    $attr_arr['attribute'] = $att;
                    if (empty($checkAttr[1]) || $checkAttr[0] != $checkAttr[1]) {
                        $attr_arr = [];
                        if (!empty($data['to_decrypt']) && in_array($checkAttr[0], $data['to_decrypt'])) {
                            $attr_arr['value'] = function($model) use ($att) {
                                return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
                            };
                        }

                        $str = ucwords(str_replace('_', ' ', $att));
                        $attr_arr['attribute'] = $att;
                        $attr_arr['label'] = Yii::t('app', $str);
                        $attr_arr['format'] = $format;
                        $attr_arr['filter'] = true;

                        $datatabel = [];
                        $datatabel['data'] = $att;
                        $datatabel['title'] = Yii::t('app', $str);
                        $datatabel['filter'] = true;
                        $attr[] = $datatabel;
//                    $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
                    }
                }
                $grid_option = [
                    'id' => 'mis-report-list',
                    'attributes' => $attr,
                    'active_column' => false,
                ];
                $c = 0;
                echo '<div id="grid_show_hide_list" class="dropdown-check-list" tabindex="100">';
                echo '<span class="anchor"><i class="fa fa-chevron-down"></i></span>';
                echo '<ul class="items">';
                foreach ($attr as $key => $value) {
                    echo '<li><input class="toggle-vis" data-column="' . $c++ . '" type="checkbox" checked/>' . $value['title'] . '</li>';
                }
                echo '</ul>';
                echo '</div>';
                // echo '<a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>';
                // var_dump($dataProvider->getModels());
                echo CustomDataTable::widget([
                    'id' => 'custom_report',
                    'autoWidth' => true,
//                    'searching' => true,
                    'data' => $dataProvider->getModels(),
                    'scrollX' => true,
                    'scrollY' => '100px',
                    'scrollCollapse' => false,
                    'paging' => false,
                    'columns' => $attr,
                    'info' => false,
                    'withColumnFilter' => true,
                    'order' => []
                ]);
//                echo \nullref\datatable\DataTable::widget([
//                    'id' => 'custom_report',
//                    'data' => $dataProvider->getModels(),
//                    // 'scrollY' => '200px',
//                    'scrollCollapse' => true,
//                    'paging' => false,
//                    'columns' => $attr,
//                    'info' => false,
//                    'withColumnFilter' => true
//                ]);
                // Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true, $removeExportType, $exportEvents);
            }
            ?>
        </div>
        <?php
        if (!empty($fileDownloadArr)) {
            echo GhostHtml::submitButton('<i class="text-white fas fa-file"></i>', ['class' => 'btn-login btn btn-default submit_btn downloadSapFiles apply-shortcut', 'name' => 'download', 'value' => 'download', 'id' => 'download', 'title' => Yii::t('app', 'download')]);
            echo GhostHtml::submitButton('FTP Upload', ['class' => 'btn-login btn btn-default apply-shortcut uploadSapFiles ms-2', 'name' => 'ftp-upload', 'value' => 'ftp-upload', 'id' => 'ftp-upload', 'title' => Yii::t('app', 'Ftp Upload')]);
        }
        ?>
    </div>
</div>       
<?php
$script = "
$('.mis_report_modal_toggle').on('click', function(){
    $('#mis_report_search_filter').modal('toggle');
});

    $(document).ready(function(){  
        $('.dataTables_scrollBody').resize();
        if('" . $report . "'=='LocationWiseAssetMovement'|| '" . $report . "'=='LocationWiseAssetSummary'|| '" . $report . "'=='LocationWiseAssetDetail'){
            hideFields();
            $(document).on('change','#reportsmodel-store_location_type', function() {
                hideFields();
            });
        }
        if('" . $report . "'=='StockDetail' || '" . $report . "'=='StockDetailSummary'){
            hideOrgFields();
            $(document).on('change','#reportsmodel-org_type', function() {
                hideOrgFields();
            });
        }
        if('" . $report . "'=='MemberMilkCollection' || '" . $report . "'=='MemberMilkCollectionDcsWise'){
            hideShift();
            $(document).on('change','#reportsmodel-basis_on', function() {
                 hideShift();
            });
        }
        
        if('" . $report . "'=='LocalMilkSale'){
            hideShiftCode();
            $(document).on('change','#reportsmodel-milk_sale_on', function() {
                 hideShiftCode();
            });
        }
        
	if('" . $report . "'=='MilkPurchaseRegisterReport' || '" . $report . "'=='FarmerLedgerReport' || '" . $report . "'=='MilkPurchaseAnalysis' || '" . $report . "'=='FarmerListReport'|| '" . $report . "'=='SmsDetailReport'){
            hideMemberTypes();
            $(document).on('change','#reportsmodel-member_types', function() {
                 hideMemberTypes();
            });
            $(document).on('keyup change','#reportsmodel-from_code', function() {
                if('" . $report . "'=='MilkPurchaseRegisterReport' || '" . $report . "'=='FarmerLedgerReport' || '" . $report . "'=='MilkPurchaseAnalysis' || '" . $report . "'=='FarmerListReport'|| '" . $report . "'=='SmsDetailReport'){
                    var member_types =  $('#reportsmodel-member_types').val();
                    if(member_types == '1'){
                        $('#reportsmodel-to_code').val($(this).val());
                    }
                }
            });
        }
		
        if('" . $report . "'=='DateWiseMilkPurchaseSummary'){
            hideSearchType();
            $(document).on('change','#reportsmodel-search_type', function() {
                 hideSearchType();
            });
            $(document).on('keyup change','#reportsmodel-from_code', function() {
                if('" . $report . "'=='DateWiseMilkPurchaseSummary'){
                    var search_type =  $('#reportsmodel-search_type').val();
                    if(search_type == '1'){
                        $('#reportsmodel-to_code').val($(this).val());
                    }
                }
            });
        }
        
        if('" . $report . "'=='MilkEditReport' || '" . $report . "'=='MilkEditSummary' || '" . $report . "'=='SocietyList'){
            hideSearchBy();
            $(document).on('change','#reportsmodel-search_by', function() {
                 hideSearchBy();
            });
        }
        
        if('" . $report . "'=='SmsDetailReport'){
            hideContactShift();
            $(document).on('change','#reportsmodel-sms_type', function() {
                 hideContactShift();
            });
        }
        
        if('" . $report . "'=='MuAppVdcsAppUserReport'){
            hideReportAppType();
            $(document).on('change','#reportsmodel-report_app_type', function() {
                 hideReportAppType();
            });
        }
        
        if('" . $report . "'=='SocietySampleReport'){
              hideSocietySampleFields();
              $(document).on('change','#reportsmodel-region_code', function() {
                  hideSocietySampleFields();
              });

              $(document).on('change','#reportsmodel-is_show_zero_val', function() {
                  hideSocietySampleFields();
              });
        }
		
        $('.toggle-vis').on('click', function (e) {
            // e.preventDefault();
            // Get the column API object
            var table = $('.dataTable').DataTable();
            var column = table.column( $(this).attr('data-column') );
            // Toggle the visibility
            var column_no = column.selector.cols
            if(column.visible()){
                $('#custom_report #w'+column_no).parent().hide();
            }else{
                $('#custom_report #w'+column_no).parent().show();
            }
            column.visible( ! column.visible() );
    });

        var checkList = document.getElementById('grid_show_hide_list');
        if(checkList != null){
            checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList.classList.contains('visible'))
                checkList.classList.remove('visible');
            else
                checkList.classList.add('visible');
            }
        }
    });
    
    function hideShift(){
        if('" . $report . "'=='MemberMilkCollection' || '" . $report . "'=='MemberMilkCollectionDcsWise'){
            var basis_on =  $('#reportsmodel-basis_on option:selected').val();
             if(basis_on == '1' || basis_on == '2'){
                $('.val_shift').show();
            }else {
                $('.val_shift').hide();
                $('.val_shift select').val('');
                $('.val_shift select').trigger('change');
            }
        }
       
    }
    function hideFields(){
        var locat_type =  $('#reportsmodel-store_location_type option:selected').text();
        $('.val_plant_code').hide();
        $('.val_mcc_code').hide();
        $('.val_bmc_code').hide();
        $('.val_dcs_code').hide();
        $('.val_plant_code select').val('');
        $('.val_plant_code select').trigger('change');
        $('.val_plant_code select').trigger('select2:select');
  
        if(locat_type == 'PLANT'){
            $('.val_plant_code').show();
        }else if(locat_type =='BMC'){
            $('.val_plant_code').show();
            $('.val_mcc_code').show();
            $('.val_bmc_code').show();
        }
        else if(locat_type =='DCS' || locat_type == 'Warehouse'){
            $('.val_plant_code').show();
            $('.val_mcc_code').show();
            $('.val_bmc_code').show();
            $('.val_dcs_code').show();
        }
       
    }
    
    $('#reportsmodel-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) { 
        if('" . $report . "'=='RateAcknowledgement'){
            hideCustomer();
        }
    });
    $(document).on('change','#reportsmodel-rate_type', function() {
        hideCustomer();
    });
    
    function hideCustomer(){
        var rate_type =  $('#reportsmodel-rate_type option:selected').text();
        if(rate_type == 'MEMBER'){
            $('.cust_type').hide();
            $('.cust_type select').val('DCS');
            $('.cust_type select').trigger('change');
        }else {
            $('.cust_type').show();
        }
    }
    function hideOrgFields(){
        var org_type =  $('#reportsmodel-org_type option:selected').val();
        $('.val_mcc_code').hide();
        $('.val_bmc_code').hide();
        $('.val_dcs_code').hide();
        $('.val_mcc_code select').val('');
        $('.val_mcc_code select').trigger('change');
        $('.val_mcc_code select').trigger('select2:select');
  
        if(org_type == 'MCC'){
            $('.val_mcc_code').show();
        }else if(org_type =='BMC'){
            $('.val_mcc_code').show();
            $('.val_bmc_code').show();
        }
        else if(org_type =='DCS'){
            $('.val_mcc_code').show();
            $('.val_bmc_code').show();
            $('.val_dcs_code').show();
        }
       
    }
     
    function hideShiftCode(){
        if('" . $report . "'=='LocalMilkSale'){
            var basis_on =  $('#reportsmodel-milk_sale_on option:selected').val();
             if(basis_on == '1'){
                $('.val_shift').show();
            }else {
                $('.val_shift').hide();
                $('.val_shift select').val('');
                $('.val_shift select').trigger('change');
            }
        }
       
    }
    
   function hideMemberTypes() {
    var member_types = $('#reportsmodel-member_types').val();
    var fromCode = $('#reportsmodel-from_code');
    var toCode = $('#reportsmodel-to_code');
    if (member_types == '1') { 
        $('.val_from_code').show();
        $('.val_to_code').show();
        fromCode.prop('readonly', false);
        toCode.prop('readonly', true);
        fromCode.val('1');
        toCode.val(fromCode.val());
    } else if (member_types == '2') { 
        $('.val_from_code').show();
        $('.val_to_code').show();
        fromCode.prop('readonly', false);
        toCode.prop('readonly', false);
        fromCode.val('1');
        toCode.val('9999');
    } 
    else { 
        $('.val_from_code').hide();
        $('.val_to_code').hide();
        fromCode.prop('readonly', true).val(0);
        toCode.prop('readonly', true).val(0);
    }
}

    function hideSearchBy(){
        if('" . $report . "'=='MilkEditReport' || '" . $report . "'=='MilkEditSummary' || '" . $report . "'=='SocietyList'){
            var search_by =  $('#reportsmodel-search_by').val();
            if(search_by == '1'){ 
                $('.val_dcs_code').show();
                $('.val_region_code').hide();
                resetField('.val_region_code select')
            }else if(search_by == '2'){ 
                $('.val_dcs_code').hide();
                $('.val_region_code').show();
                resetField('.val_dcs_code select')
            }else {
                $('.val_dcs_code').hide();
                $('.val_region_code').hide();
                resetField('.val_dcs_code select')
                resetField('.val_region_code select')
            }
        }
    }
	
    function hideSearchType() {
        var search_type = $('#reportsmodel-search_type').val();
        var fromCode = $('#reportsmodel-from_code');
        var toCode = $('#reportsmodel-to_code');
        if (search_type == '1') { 
            $('.val_from_code').show();
            $('.val_to_code').show();
            fromCode.prop('readonly', false);
            toCode.prop('readonly', true);
            fromCode.val('1');
            toCode.val(fromCode.val());
        } else if (search_type == '2') { 
            $('.val_from_code').show();
            $('.val_to_code').show();
            fromCode.prop('readonly', false);
            toCode.prop('readonly', false);
            fromCode.val('1');
            toCode.val('9999');
        } else { 
            $('.val_from_code').hide();
            $('.val_to_code').hide();
            fromCode.prop('readonly', true).val(0);
            toCode.prop('readonly', true).val(0);
        }
    }

    function resetField(selector) {
        $(selector).val(0).trigger('change').trigger('select2:select');
    }
    
     function hideContactShift(){
        if('" . $report . "'=='SmsDetailReport'){
            var sms_type =  $('#reportsmodel-sms_type').val();
            if(sms_type == '1'){ 
                $('.MobileHideShow').show();
                $('.ShiftHideShow').hide();
                resetField('.ShiftHideShow select')
            }else if(sms_type == '2'){ 
                $('.MobileHideShow').hide();
                $('.ShiftHideShow').show();
                $('.MobileHideShow input').val('').trigger('change').trigger('select2:select');
            }else {
                $('.MobileHideShow').hide();
                $('.ShiftHideShow').hide();
                $('.MobileHideShow input').val('').trigger('change').trigger('select2:select');
                resetField('.ShiftHideShow select')
            }
        }
    }
    
    
    
    function hideReportAppType(){
        if('" . $report . "'=='MuAppVdcsAppUserReport'){
            var report_app_type =  $('#reportsmodel-report_app_type').val();
            if(report_app_type == '1'){ 
                $('.val_dcs_code').hide();
                resetField('.val_dcs_code select')
            }else if(report_app_type == '2'){ 
                $('.val_dcs_code').show();
            }else {
                $('.val_dcs_code').hide();
                resetField('.val_dcs_code select')
            }
        }
    }
    
    function hideSocietySampleFields(){
        if('" . $report . "' == 'SocietySampleReport'){
            var is_show_zero_val = $('#reportsmodel-is_show_zero_val').is(':checked');
            if(is_show_zero_val){
                $('#reportsmodel-from_time').prop('readonly', false);
                $('#reportsmodel-to_time').prop('readonly', false);
            }else{
                $('#reportsmodel-from_time').prop('readonly', true);
                $('#reportsmodel-to_time').prop('readonly', true);
            }

            var region_code = $('#reportsmodel-region_code').val();
            if(region_code != '0' && region_code != '' && region_code != null){
                $('.val_dcs_code').hide();
                resetField('.val_dcs_code select');
            }else{
                $('.val_dcs_code').show();
            }
        }
    }
    
";

if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#mis_report_search_filter').modal('toggle');
        });
    ";
}
$this->registerJs($script, View::POS_READY, 'mis-report-script');
?>


<?php
$baseUrl = Yii::$app->request->baseUrl;
$count = count($fileDownloadArr);
$timeOutForLoader = ($count * 1000) + 2000;
$scriptDownload = "

var timeOut = 500;
$(document).on('click', '.downloadSapFiles', function(e){
    e.preventDefault();
    $('#loadercontent').show();
    $('#pageloader').show();
    timeOut = 500;
    var baseUrl = '" . $baseUrl . "/sap_data_files/';
    var downloadFilesJson = '" . $downloadSapFiles . "';
    var timeOutForLoader = " . $timeOutForLoader . ";
    var downloadFilesJsonAr = JSON.parse(downloadFilesJson);
    $.each(downloadFilesJsonAr, function(ind, vl) {
        setTimeout(() => {
            window.location.href = baseUrl + vl;
        }, timeOut);
        timeOut = timeOut + 1000;
    });
    setTimeout(() => {
        $('#loadercontent').hide();
        $('#pageloader').hide();
    }, timeOutForLoader);
    return false;
});

$(document).on('click', '.uploadSapFiles', function(e){
    $('#reportsmodel-upload_ftp_file').val('1');
    $('#report-form').submit();
});

";

$this->registerJs($scriptDownload, View::POS_READY, 'mis-report-script-other-download');
?>
