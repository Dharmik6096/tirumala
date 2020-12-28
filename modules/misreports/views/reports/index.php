<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->from_date = empty($model->from_date) ? date('d-m-Y') : $model->from_date;
$model->to_date = empty($model->to_date) ? date('d-m-Y') : $model->to_date;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
$defaultToggle = true;
$model->p_date = empty($model->p_date) ? date('d-m-Y') : $model->p_date;
$model->date = empty($model->date) ? date('d-m-Y') : $model->date;
if (isset($data['url1'])) {
    $this->params['menu'][] = Yii::$app->controls->custombutton($data['url1'][0], $data['url1'][1], $data['url1'][2]);
}
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
    $this->title = !empty($data['export_file_name']) ? $data['export_file_name'] : $this->title;
    ?>
    <div class="panel-body padding-0">
        <div class="report-area not_ellipsis">
            <div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
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
                                    <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false);  ?>  
                                    <?php
                                    $param = isset($data['param']) ? explode(',', $data['param']) : [];
                                    foreach ($param as $key => $value) {
                                        $value_array = explode(':', $value);
                                        $value = $value_array[0];

                                        if (isset($value_array[1]) && $value_array[1] == 'string') {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-3 padding-left-5 padding-right-5', false);
                                                ?>
                                            </div>    
                                            <?php
                                            if (isset($value_array[2])) {
                                                ?>
                                                <div class="col-sm-3 shift">
                                                    <?php
                                                    echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                                    ?>
                                                </div>    
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('union_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                                            </div>   <?php
                                        }
                                        if (in_array($value, array('plant_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_plant_code">
                                                <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodel-union_code', 'plant_code', 'Plant'); ?>
                                            </div>
                                        <?php } if (in_array($value, array('mcc_code'))) { ?>
                                            <div class="col-sm-3 val_mcc_code">
                                                <?php
                                                if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                    Yii::$app->dropdown->union_mcc($model, $form, 'reportsmodel-union_code', $value, $model->getAttributeLabel('mcc_code'));
                                                } else {
                                                    echo Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodel-plant_code', $value, 'MCC');
                                                }
                                                ?>                
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('bmc_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_bmc_code">
                                                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodel-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('dcs_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'route_code') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?php
                                                    echo Yii::$app->dropdown->route_dcs($model, $form, 'reportsmodel-route_code', 'dcs_code', Yii::t('app', 'Society'));
                                                    ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodel-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (isset($value_array[1]) && $value_array[1] == 'txt') {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= $form->field($model, $value_array[0])->textInput(['maxlength' => true]) ?>
                                            </div>    
                                            <?php
                                        }
                                        if (in_array($value, array('customer_code'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->merge_dcs_customer($model, $form, 'reportsmodel-bmc_code', 'customer_code', Yii::t('app', 'Name')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('customer_type'))) {
                                            ?>
                                            <div class="col-sm-3 cust_type">
                                                <?= Yii::$app->dropdown->customer_type($model, $form, 'reportsmodel-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('vendor_code'))) {
                                            $depend = 'reportsmodel-bmc_code,reportsmodel-customer_type';
                                            if (isset($value_array[1])) {
                                                $depend = 'reportsmodel-bmc_code,reportsmodel-' . $value_array[1];
                                            }
                                            ?>
                                            <div class="col-sm-3 vendor">
                                                <?= Yii::$app->dropdown->customer_code($model, $form, $depend, 'vendor_code', $model->getAttributeLabel('vendor_code'), FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('main_customer_type'))) {
                                            ?>
                                            <div class="col-sm-3 ">
                                                <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('customer_type'), FALSE, 'main_customer_type'); ?>
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
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'reportsmodel-union_code,reportsmodel-bmc_code,reportsmodel-customer_type,applicable_for,data_lock_bmc,0,reportsmodel-type_check', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('member_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'reportsmodel-dcs_code', '', $model->getAttributeLabel('member')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_organization_type'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('p_organization_type'), false, 'p_organization_type') ?> 
                                                </div>
                                                <?php
                                            }
                                        }

                                        if (in_array($value, array('rate_type', 'bank_type', 'report_status', 'originating_type','type_wise_report'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                                ?>

                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_purchase_rate_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'rate_type') {
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?php // Yii::$app->dropdown->org_type_rate($model, $form, 'reportsmodel-p_organization_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code'));   ?>
                                                    <?= Yii::$app->dropdown->memberRateChart($model, $form, 'reportsmodel-union_code,reportsmodel-rate_type', 'p_purchase_rate_code', $model->getAttributeLabel('p_purchase_rate_code')); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('member_type'))) {
                                            ?>
                                            <div class="col-sm-3 val_dcs_code">
                                                <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel($value), false, 'member_type'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('route_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'all_routes') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->all_routes($model, $form, 'reportsmodel-plant_code,reportsmodel-mcc_code,reportsmodel-bmc_code', 'route_code', Yii::t('app', 'Route')); ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?= Yii::$app->dropdown->union_routes($model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $value); ?>                                           
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('store_location_type'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('store_location_type'), FALSE, 'store_location_type'); ?>

                                            </div>

                                            <?php
                                        }
                                        if (in_array($value, array('asset_code'))) {
                                            $depends = 'reportsmodel-' . $value_array[1];
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->depend_dropdown('union_asset', $model, $form, $depends, 'form-group col-sm-3 padding-right-5 padding-left-5', $model->getAttributeLabel('asset_code'), $value);
                                                ?>
                                            </div>

                                            <?php
                                        }
                                        if (in_array($value, array('sap_code'))) {
                                            $depends = 'reportsmodel-' . $value_array[1] . ',' . 'reportsmodel-' . $value_array[2] . ',' . 'reportsmodel-' . $value_array[3] . ',' . 'reportsmodel-' . $value_array[4];
                                            ?>
                                            <div class="col-sm-3">
                                                <?php echo Yii::$app->dropdown->org_sap_code($model, $form, $depends, 'sap_code', $model->getAttributeLabel('sap_code')); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('transporter_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('transporter_code')); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('transporter_code'), false, 'transporter_code'); ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('vehicle_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'transporter_code') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'reportsmodel-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code')); ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code', $model->getAttributeLabel('vehicle_code')); ?>
                                                </div>
                                                <?php
                                            }
                                        }

                                        if (in_array($value, array('report_collection_type'))) {
                                            ?>
                                                <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->dropdownStatic($value, $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                                </div>
                                            <?php
                                        }
                                    }

                                    if (isset($data['report_type'])) {
                                        echo $form->field($model, 'report_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type']);
                                    }
                                    if (isset($data['dynamic'])) {
                                        echo Html::hiddenInput('dynamic_report', $data['dynamic']);
                                    }                                    
                                    if (!isset($data['output_type'])) {
                                        echo $form->field($model, 'output_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList(['DOWNLOAD' => 'DOWNLOAD', 'VIEW' => 'VIEW']);
                                    }
                                    ?>

                                    <div class="modal-footer mt10 col-sm-12">
                                        <?php
                                        if ($param) {
                                            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
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

            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?> <?= $custom_report_class?>">
                <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>

            <?php if (!empty($result) && !(isset($data['download_only']))) { ?>

                <!--                                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                                                </div>-->
            <?php } ?>
            <?php
            if (!empty($result) && !is_array($result) && !isset($data['custom_report'])) {
                echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
            } else if(!empty($result) && isset($data['custom_report'])){
                echo $this->render('_dynamic_report', ['result' => $result, 'model' => $model]);
            }
            else if (!empty($result) && !(isset($data['download_only']))) {
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
                    'id' => 'mis-report-list',
                    'attributes' => $attr,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true, $removeExportType, $exportEvents);
            }
            ?>
        </div>
    </div>
</div>       
<?php
$script = "
$('.mis_report_modal_toggle').on('click', function(){
    $('#mis_report_search_filter').modal('toggle');
});
   
    $(document).ready(function(){  
        if('" . $report . "'=='LocationWiseAssetMovement'|| '" . $report . "'=='LocationWiseAssetSummary'|| '" . $report . "'=='LocationWiseAssetDetail'){
            hideFields();
            $(document).on('change','#reportsmodel-store_location_type', function() {
                hideFields();
            });
        }
    });
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
