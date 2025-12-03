<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->p_from_date = empty($model->p_from_date) ? date('d-m-Y') : $model->p_from_date;
$model->p_to_date = empty($model->p_to_date) ? date('d-m-Y') : $model->p_to_date;
$model->from_shift = empty($model->from_shift) ? 1 : $model->from_shift;
$model->to_shift = empty($model->to_shift) ? 2 : $model->to_shift;
$model->p_collection_date = empty($model->p_collection_date) ? date('d-m-Y') : $model->p_collection_date;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
$defaultToggle = true;
$multiArray = !empty($data['multiArray']) ? $data['multiArray'] : [];
?>
<div class="panel panel-default panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body padding-0">
        <div class="report-area">
            <!--<div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">-->
            <div class="">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'report-form',
                                'field-class' => 'form-group col-sm-6'
                            ], 'validateOnBlur' => FALSE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>    

                <div class="modal modal-default fade" id="jasper_report_search_filter" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo $title; ?></h4>
                            </div>
                            <div class="row margin_0">

                                <div class="modal-body">
                                    <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false);  ?>
                                    <div class="col-sm-3">
                                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                                    </div>  
                                    <?php
                                    $param = isset($data['param']) ? explode(',', $data['param']) : [];
                                    foreach ($param as $key => $value) {
                                        $value_array = explode(':', $value);
                                        $value = $value_array[0];
                                        $multiple = in_array($value, $multiArray) ? true : false;
                                        $multiple_class = in_array($value, $multiArray) ? 'is_matched' : '';
                                        if (in_array($value, array('dcs_code', 'p_dcs_code', 'pm_dcs_code'))) {
                                            ?>
                                            <?php
                                            if (isset($value_array[1]) && ($value_array[1] == 'p_route_code' || $value_array[1] == 'route_code')) {
                                                $allowmulti = false;
                                                if (isset($value_array[2]) && $value_array[2] == 'multiselect') {
                                                    $allowmulti = true;
                                                }
                                                $id = 'reportsmodel-' . $value;
                                                $depends = 'reportsmodel-' . $value_array[1];
                                                ?>

                                                <div class="col-sm-3">
                                                    <?php
                                                    echo Yii::$app->dropdown->route_dcs($model, $form, $depends, 'p_dcs_code', Yii::t('app', 'Society'), false, $allowmulti, $id);
                                                    ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3 <?= $multiple_class ?>">
                                                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodel-p_bmc_code', 'p_dcs_code', Yii::t('app', 'Society'), $multiple); ?>         
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_route_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'all_routes') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->all_routes($model, $form, 'reportsmodel-p_plant_code,reportsmodel-p_mcc_code,reportsmodel-p_bmc_code', 'route_code', Yii::t('app', 'Route')); ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3 dd_route">
                                                    <?php
                                                    echo Yii::$app->dropdown->union_routes($model, $form, 'reportsmodel-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $value);
                                                    ?>
                                                </div>    
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_district_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->uniondistrict($model, $form, 'reportsmodel-union_code,reportsmodel-state_code', 'p_district_code', 'District', FALSE); ?>
                                            </div>    
                                            <?php
                                        }

                                        if (in_array($value, array('p_sub_district_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'reportsmodel-p_district_code', 'form-group col-sm-3 padding-right-5 padding-left-0', 'Sub District', 'p_sub_district_code'); ?>
                                            </div>    
                                            <?php
                                        }

                                        if (in_array($value, array('p_block_name'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php Yii::$app->dropdown->depend_dropdown('block_code', $model, $form, 'reportsmodel-p_sub_district_code', 'form-group col-sm-3 padding-right-5 padding-left-0', 'Block', 'p_block_name'); ?>
                                            </div>    
                                            <?php
                                        }

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
                                                <div class="col-sm-3">
                                                    <?php
                                                    echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                                    ?>
                                                </div>    
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('p_animal_type', 'p_milk_type', 'milk_type'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value), false, $value, true);
                                                ?>
                                            </div>    
                                            <?php
                                        }
                                        if (in_array($value, array('p_member_code', 'member_code'))) {
                                            $depends = 'reportsmodel-' . $value_array[1];
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->depend_dropdown('member', $model, $form, $depends, 'form-group col-sm-3 padding-right-5 padding-left-5', $model->getAttributeLabel($value), 'p_member_code');
                                                ?>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if (in_array($value, array('with_and_without_milktype'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->dropdownStatic('with_and_without_milktype', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_type'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->dropdownStatic('p_type', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_dcs_payment'))) {
                                            ?>

                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->unionpaymentcyclewithdate($model, $form, 'reportsmodel-union_code', 'p_dcs_payment', 'Payment Cycle'); ?>
                                            </div>    
                                            <?php
                                        }

                                        if (in_array($value, array('p_is_bank'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->dropdownStatic('bank_status', $model, $form, 'form-group', $model->getAttributeLabel($value), false, $value, false);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_member_type'))) {
                                            ?>

                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel($value), false, 'p_member_type'); ?>
                                            </div>   
                                            <?php
                                        }
                                        if (in_array($value, array('p_pouring_qty'))) {
                                            ?>

                                            <div class="col-sm-3">
                                                <?= $form->field($model, 'p_pouring_qty')->textInput(['maxlength' => true]) ?>
                                            </div>  
                                            <?php
                                        }
                                        if (in_array($value, array('p_no_of_pouring_day'))) {
                                            ?>

                                            <div class="col-sm-3">
                                                <?= $form->field($model, 'p_no_of_pouring_day')->textInput(['maxlength' => true]) ?>
                                            </div>  
                                            <?php
                                        }

                                        if (in_array($value, array('p_plant_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodel-union_code', 'p_plant_code', $model->getAttributeLabel('p_plant_code')); ?>
                                            </div>      
                                            <?php
                                        }
                                        if (in_array($value, array('p_mcc_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodel-p_plant_code', 'p_mcc_code', $model->getAttributeLabel('p_mcc_code')); ?>
                                            </div>   
                                            <?php
                                        }
                                        if (in_array($value, array('p_bmc_code'))) {
                                            if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->union_bmc($model, $form, 'reportsmodel-union_code', 'p_bmc_code', Yii::t('app', 'BMC')); ?>
                                                </div>
                                                <?php
                                            } else if (isset($value_array[1]) && $value_array[1] == 'area_code') {
                                                ?>
                                                <div class="col-sm-3  <?= $multiple_class ?>">
                                                    <?= Yii::$app->dropdown->area_bmc($model, $form, 'reportsmodel-area_code', 'p_bmc_code', Yii::t('app', 'BMC'), $multiple, false, false); ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodel-p_mcc_code', 'p_bmc_code', $model->getAttributeLabel('p_bmc_code')); ?>
                                                </div>     
                                                <?php
                                            }
                                        }
                                        if (in_array($value, array('state_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->dropdown('state_code', $model, $form, '', 'State Name', false, '', false, true, $multiple); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('region_code'))) {
                                            ?>
                                            <div class="col-sm-3  <?= $multiple_class ?>">
                                                <?= Yii::$app->dropdown->depend_dropdown('region_code', $model, $form, 'reportsmodel-state_code', 'form-group col-sm-12', 'Region Name', 'region_code', false, 0, [], $multiple, '', false, true, $multiple, false); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('area_code'))) {
                                            ?>
                                            <div class="col-sm-3  <?= $multiple_class ?>">
                                                <?= Yii::$app->dropdown->depend_dropdown('area_code', $model, $form, 'reportsmodel-region_code', 'form-group col-sm-12', 'Area Name', 'area_code', false, 0, [], $multiple, '', false, true, $multiple, false); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_milk_class'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('p_milk_class'), false, 'p_milk_class'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_ltr_kg'))) {
                                            echo Yii::$app->dropdown->dropdownStatic('p_ltr_kg', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value), false, $value, false);
                                        }
                                        if (in_array($value, array('p_customer_type'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->customer_type($model, $form, 'reportsmodel-p_bmc_code', 'p_customer_type', $model->getAttributeLabel('p_customer_type'), FALSE); ?>
                                            </div> 
                                            <?php
                                        }
                                        if (in_array($value, array('p_customer_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->customer_code($model, $form, 'reportsmodel-p_bmc_code,reportsmodel-p_customer_type', 'p_customer_code', $model->getAttributeLabel('p_customer_code'), FALSE); ?>
                                            </div> 
                                            <?php
                                        }
                                        if (in_array($value, array('p_payment_cycle_code'))) {
                                            $where = json_encode(['data_lock_bmc' => 1]);
                                            echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                                            echo Html::hiddenInput('member_billing_lock_check', '', ['id' => 'member_billing_lock_check']);
                                            if (isset($value_array[1]) && isset($value_array[2]) && $value_array[1] == 'default') {
                                                echo Html::hiddenInput('p_customer_type', $value_array[2], ['id' => 'reportsmodel-p_customer_type']);
                                                $where = json_encode(['data_lock_member' => 1]);
                                            }
                                            if (isset($value_array[1]) && $value_array[1] == 'type_check') {
                                                echo Html::hiddenInput('type_check', TRUE, ['id' => 'reportsmodel-type_check']);
                                            }
                                            echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'reportsmodel-union_code,reportsmodel-p_bmc_code,reportsmodel-p_customer_type,applicable_for,data_lock_bmc,member_billing_lock_check,reportsmodel-type_check', 'p_payment_cycle_code', $model->getAttributeLabel('p_payment_cycle_code'), FALSE, FALSE); ?>
                                            </div>                                        
                                            <?php
                                        }
                                        if (in_array($value, array('p_staff_member_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'reportsmodel-union_code', 'form-group col-sm-3', $model->getAttributeLabel('p_staff_member_code'), 'p_staff_member_code'); ?>
                                            </div> 
                                            <?php
                                        }
                                        if (in_array($value, array('p_provisional_member_code', 'p_mpp_survey_id', 'p_VCG_M_Id'))) {
                                            if (Yii::$app->request->queryParams && isset(Yii::$app->request->queryParams['code'])) {
                                                $model->{$value} = Yii::$app->request->queryParams['code'];
                                            }
                                            echo Html::activeHiddenInput($model, $value);
                                        }
                                        if (in_array($value, array('p_lang_code'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php
                                                echo Yii::$app->dropdown->dropdownStatic('language_list', $model, $form, 'form-group', $model->getAttributeLabel('Language'), false, $value, false);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        if (in_array($value, array('p_month'))) {
                                            ?>
                                            <div class="col-sm-3">
                                                <?=
                                                $form->field($model, 'p_month')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control '],
                                                    'mask' => '99-9999',])
                                                ?>                                               
                                                <?php
                                            }

                                            if (in_array($value, array('p_dcsc_code'))) {
                                                $depend = 'reportsmodel-p_bmc_code';
                                                if (isset($value_array[1])) {
                                                    $depend = 'reportsmodel-p_bmc_code,reportsmodel-p_billing_for,reportsmodel-route_code';
                                                }
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?= Yii::$app->dropdown->merge_dcs_customer($model, $form, $depend, 'p_dcsc_code', Yii::t('app', 'Name')); ?>
                                                </div>
                                                <?php
                                            }
                                            if (in_array($value, array('p_billing_for'))) {
                                                echo Yii::$app->dropdown->dropdownStatic('billing_for', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value), false, $value, false);
                                            }
                                            if (in_array($value, array('p_transporter_code'))) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->all_route_transporter($model, $form, 'reportsmodel-p_plant_code,reportsmodel-p_mcc_code,reportsmodel-p_bmc_code', 'p_transporter_code', $model->getAttributeLabel('Transporter'), FALSE, '', FALSE, TRUE); ?>
                                                </div>
                                                <?php
                                            }
                                            if (in_array($value, array('p_party_master_code'))) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= Yii::$app->dropdown->dropdown('party_master', $model, $form, '', 'Party', FALSE, 'p_party_master_code'); ?>
                                                </div>
                                                <?php
                                            }
                                            if (in_array($value, array('p_trip_code'))) {
                                                if (isset($value_array[1]) && $value_array[1] == 'p_vehicle_code') {
                                                    ?>
                                                    <div class="col-sm-3">
                                                        <?= Yii::$app->dropdown->depend_dropdown('vehicle_trip', $model, $form, 'reportsmodel-p_vehicle_code', 'form-group col-sm-4', $model->getAttributeLabel('p_trip_code')); ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="col-sm-3">
                                                        <?= Yii::$app->dropdown->dropdown('trip_code', $model, $form, '', $model->getAttributeLabel($value), false, 'p_trip_code'); ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            if (in_array($value, array('p_vehicle_code'))) {
                                                ?>
                                                <div class="col-sm-3 val_dcs_code">
                                                    <?= Yii::$app->dropdown->vehicle($model, $form, 'p_vehicle_code', $model->getAttributeLabel('vehicle_code')); ?>
                                                </div>
                                                <?php
                                            }
                                            if (in_array($value, array('p_qty_from', 'p_qty_to', 'p_fat_from', 'p_fat_to', 'p_snf_from', 'p_snf_to'))) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?= $form->field($model, $value)->textInput(['maxlength' => true]) ?>
                                                </div>  
                                                <?php
                                            }
                                            if (in_array($value, array('p_login_type'))) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <?php
                                                    echo Yii::$app->dropdown->dropdownStatic('login_type_report', $model, $form, 'form-group', $model->getAttributeLabel('login_type'), false, $value, false);
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (isset($data['report_type'])) {
                                            echo $form->field($model, 'report_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type'], ['prompt' => Yii::t('app', 'Select Type')]);
                                        }

                                        echo Html::activeHiddenInput($model, 'p_union_code');
                                        echo Html::activeHiddenInput($model, 'p_union_name');
                                        echo Html::activeHiddenInput($model, 'p_dcs_name');
                                        echo Html::activeHiddenInput($model, 'p_route_name');
                                        $model->p_report_name = Html::encode($this->title);
                                        echo Html::activeHiddenInput($model, 'p_report_name');

                                        echo Html::activeHiddenInput($model, 'locale');
                                        echo Html::activeHiddenInput($model, 'digit_config');
                                        ?>

                                        <!--            <div class="clearfix"></div>-->
                                        <!--<div class="col-sm-3 mt25">-->
                                        <?php
                                        if ($param) {
//                                            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <!--</div>-->
                                    </div>
                                    <div class="modal-footer mt10 col-sm-12">
                                        <?php
                                        if ($param) {
                                            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
                                        }
                                        ?>
                                        <?= GhostHtml::submitButton('<i class="text-white fa fa-file-o"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'html', 'value' => 'pdf', 'id' => 'pdf', 'title' => Yii::t('app', 'pdf')]); ?>
                                        <?php
                                        if (isset($data['tcpdf']) && $data['tcpdf']) {
                                            $client_code = \Yii::$app->session->get('eiplCode');
                                            $titleTcpdf = (isset($data['titleTcpdf'])) ? $data['titleTcpdf'] : 'pdf';
                                            $iconClass = ' fa fa-file-pdf-o ';
                                            if (strtolower($client_code) == 'mmd' || strtolower($client_code) == 'elanad') {
                                                $titleTcpdf = 'Milktype Wise Bill';
                                                $iconClass = ' fa fa-file-text-o ';
                                            }
                                            echo GhostHtml::submitButton('<i class="text-white ' . $iconClass . '"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'html', 'value' => 'tcpdf', 'id' => 'tcpdf', 'title' => Yii::t('app', $titleTcpdf)]);

                                            if (strtolower($client_code) == 'mmd') {
                                                echo GhostHtml::submitButton('<i class="text-white ' . $iconClass . '"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'html', 'value' => 'tcpdf_two', 'id' => 'tcpdf_two', 'title' => Yii::t('app', 'Shift Wise Bill')]);
                                            }
                                        }
                                        ?>
                                        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($result != '') { ?>

                    <!--                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                                        
                        <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                                    </div>-->
                <?php } ?>

                <?php
                $class = 'beforeGridLoad';
                if (!empty($result)) {
                    $defaultToggle = false;
                }
                if (!empty($model->getErrors())) {
                    $defaultToggle = true;
                }
                ?>
            </div>
            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?>">
                <?php if (false && $result != '') { ?>
                    <?= GhostHtml::submitButton('<i class="text-white fa fa-file-pdf-o"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'submit', 'value' => 'pdf', 'id' => 'pdf', 'title' => Yii::t('app', 'pdf')]); ?>
                    <?php if (!isset($data['pdf'])) { ?>
                        <?= GhostHtml::submitButton('<i class="fa fa-file-code-o"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'submit', 'value' => 'csv', 'id' => 'csv', 'title' => Yii::t('app', 'csv')]); ?>
                        <?= GhostHtml::submitButton('<i class="fa fa-file-excel-o"></i>', ['class' => 'btn btn-default submit_btn apply-shortcut', 'name' => 'submit', 'value' => 'xls', 'id' => 'xls', 'title' => Yii::t('app', 'xls')]); ?>
                        <?php
                    }
                }
                ?>
                <div class="btn-group btn btn-default jasper_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>
            <?php ActiveForm::end(); ?>



            <?php if ($result != '') { ?>
                <div class="report-grid">
                    <?php echo $result; ?>
                </div>

            <?php }
            ?>
        </div>
    </div>            
    <?php
    $script = "
   $(document).ready(function() {
        if('" . Yii::$app->session->get('Federations') . "' != ''){
         var id='" . strtolower((new ReflectionClass($model))->getShortName() . '-federation_code') . "';   
         $('#'+id+' option:selected').val('" . Yii::$app->session->get('Federations') . "');
         $('#'+id).parent('div').hide(); 
         }
         
    });
";
    Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName() . '-' . 'search'));
    ?>

    <?php
    $script = "
    $('#reportsmodel-p_lang_code').val(1);
    var languageCode = $('#reportsmodel-p_lang_code').val();
    setLanguageData(languageCode);
    function setLanguageData(languageCode){
        $('#reportsmodel-locale').val('en');
        $('#reportsmodel-digit_config').val(0);
        if(languageCode == 1){
            $('#reportsmodel-locale').val('hn');
            $('#reportsmodel-digit_config').val(1);
        }
    }
    $('#reportsmodel-p_lang_code').on('change', function(){
        languageCode = $('#reportsmodel-p_lang_code').val();
        setLanguageData(languageCode);
    });
       $('#reportsmodel-p_union_name').val($('select#reportsmodel-union_code option:selected').text());
        $('#reportsmodel-p_union_code').val($('select#reportsmodel-union_code option:selected').val());
    $('#reportsmodel-union_code').change(function() {
        $('#reportsmodel-p_union_name').val($('select#reportsmodel-union_code option:selected').text());
        $('#reportsmodel-p_union_code').val($('select#reportsmodel-union_code option:selected').val());
    });
    $('#reportsmodel-p_route_code').change(function() {
        $('#reportsmodel-p_route_name').val($('select#reportsmodel-p_route_code option:selected').text());
    });
    $('#reportsmodel-p_dcs_code').change(function() {
       $('#reportsmodel-p_dcs_name').val($('select#reportsmodel-p_dcs_code option:selected').text()); 
   });
     $('#reportsmodel-with_and_without_milktype').change(function() {
            if($('#reportsmodel-with_and_without_milktype').val()=='With Milk Type' || $('#reportsmodel-with_and_without_milktype').val()==''){
                $('#reportsmodel-p_milk_type').removeAttr('disabled', 'false');
                if($('#reportsmodel-with_and_without_milktype').val()=='') {
                    $('#reportsmodel-p_milk_type').val('');
                } else {
                    $('#reportsmodel-p_milk_type').val(0);
                }
                $('#reportsmodel-p_milk_type option:contains(\'NA\')').remove();  
            }
            else
            {
                $('#reportsmodel-p_milk_type').attr('disabled', 'true');
                //$('#reportsmodel-p_milk_type').html('<option value=\'0\'>NA</option>');
                $('#reportsmodel-p_milk_type option:first').after($('<option/>', { 'value': '','selected':'selected', text: '" . Yii::t('app', 'NA') . "'}));      
            }
    });
   $(document).ready(function() {
       $('#reportsmodel-from_shift option:contains(\'All\')').remove();    
       $('#reportsmodel-to_shift option:contains(\'All\')').remove();
       $('#reportsmodel-shift option:contains(\'All\')').remove();
       if($('#reportsmodel-with_and_without_milktype').val()=='Without Milk Type'){
            $('#reportsmodel-p_milk_type').attr('disabled', 'true');
            $('#reportsmodel-p_milk_type option:first').after($('<option/>', { 'value': '','selected':'selected', text: '" . Yii::t('app', 'NA') . "'}));      
       }
    });
    
//    $(document).ready(function(){  
//        if('" . $report . "'=='VendorBillMmd'){
//            hideRoute();
//            $(document).on('change','#reportsmodel-p_billing_for', function() {
//                hideRoute();
//            });
//        }
//    });
//    $(document).on('change','#reportsmodel-p_billing_for', function() {
//        if('" . $report . "'=='VendorBillMmd'){
//            hideRoute();
//        }
//    });
//    function hideRoute(){
//        var type =  $('#reportsmodel-p_billing_for option:selected').val();
//        if(type == '1'){
//            $('.dd_route').show();
//        }else {
//            $('.dd_route').hide();
//            $('.dd_route select').val('');
//            $('.dd_route select').trigger('change');
//        }
//    }
";
    $this->registerJs($script, View::POS_END, 'shift');
    ?>
    <?php
    $is_not_dcs_array = (!is_array($model->p_dcs_code) && $model->p_dcs_code == '0') ? 1 : 0;
    $is_not_bmc_array = (!is_array($model->p_bmc_code) && $model->p_bmc_code == '0') ? 1 : 0;
    $script = "
        var is_not_dcs_array = " . $is_not_dcs_array . ";
        var is_not_bmc_array = " . $is_not_bmc_array . ";
            
        $('.is_matched').each(function() {
            var dropdown = $(this).find('select');
            if (dropdown.length) {
                dropdown.find('option[value=\"\"][selected]').remove();
                dropdown.find('option[value=\"0\"][selected]').filter(function() {
                    return $(this).text() === \"0\";
                }).remove();
                dropdown.trigger('change.select2');
            }
        });
        

        if('" . $report . "'=='ConsolidatedMilkCollectionAllshiftData' || '" . $report . "'=='RmrdMilkCollection'){
        $('#reportsmodel-p_member_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_member_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
            if('" . $model->p_member_code . "'=='0'){
        $('#reportsmodel-p_member_code').val(0);      
    }
});
}
if('" . $report . "'=='ConsolidatedMilkCollectionAllshiftData' || '" . $report . "'=='RmrdMilkCollection'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    //$('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
});
}

if('" . $report . "'=='ConsolidatedDcsMilkCollectionDate' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateShift' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateWithout' || '" . $report . "'=='ConsolidatedDcsMilkCollectionDateShiftWithout' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDate' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateShift' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateWithout' || '" . $report . "'=='ConsolidatedUnionMilkCollectionDateShiftWithout' || '" . $report . "'=='DcsCollectionDispatchDifferenceReport' || '" . $report . "'=='DcsCollectionDispatchDifferenceReportWithMilkType'){
$('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
    if(is_not_dcs_array){
        $('#reportsmodel-p_dcs_code').val(0);      
    }
    if($('#reportsmodel-union_code').val()=='0'){ 
         $('#reportsmodel-p_dcs_code').prop('disabled',false);
         $('#reportsmodel-p_dcs_code').val(0);
         $('#reportsmodel-p_route_code').prop('disabled',false);
         $('#reportsmodel-p_route_code').val(0);   
    }
    if($('#reportsmodel-p_route_code').val()=='0'){ 
         $('#reportsmodel-p_dcs_code').prop('disabled',false);
         $('#reportsmodel-p_dcs_code').val(0);
    }
});
$('#reportsmodel-p_route_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_route_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_route_code . "'=='0'){
        $('#reportsmodel-p_route_code').val(0);      
    }
});
if('" . $report . "'=='DcsCollectionDispatchDifferenceReport' || '" . $report . "'=='DcsCollectionDispatchDifferenceReportWithMilkType'){ 
} else {
    $('#reportsmodel-union_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
          if('" . $model->p_union_code . "'=='0'){
        $('#reportsmodel-union_code').val(0);
         $('#reportsmodel-p_union_name').val('All');
        $('#reportsmodel-p_union_code').val(0);
   }
}
}
if('" . $report . "'=='UnionCollectionDispatchDifferenceReport' || '" . $report . "'=='UnionCollectionDispatchDifferenceReportWithOutMilkType' || '" . $report . "'=='SocietyDetails' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport' || '" . $report . "'=='VariationMilkTypeDateWise' || '" . $report . "'=='VariationMilkTypeVillageWise' || '" . $report . "'=='VariationDateWise' || '" . $report . "'=='VariationVillageWise' || '" . $report . "'=='VariationPercentageWise' || '" . $report . "'=='DifferenceReport' || '" . $report . "'=='DifferenceReportDateWise' || '" . $report . "'=='DifferenceReportVillageWise' || '" . $report . "'=='BmcCollection' || '" . $report . "'=='GprsDataReconciliation' || '" . $report . "'=='ActualBmcCollection'){ 
    
    $('#reportsmodel-p_route_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#reportsmodel-p_route_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_route_code . "'=='0'){
        $('#reportsmodel-p_route_code').val(0);      
    }
    
    if($('#reportsmodel-union_code').val()=='0'){ 
         $('#reportsmodel-p_route_code').prop('disabled',false);
         $('#reportsmodel-p_route_code').val(0);   
    }
    
    });
    $('#reportsmodel-union_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
        if('" . $model->p_union_code . "'=='0'){
        $('#reportsmodel-p_union_code').val(0);
        $('#reportsmodel-union_code').val(0);
    }
}

if('" . $report . "'=='BlockWiseCollection'){ 
    $('#reportsmodel-p_block_name').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_block_name option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
            if('" . $model->p_block_name . "'=='0'){
            $('#reportsmodel-p_block_name').val(0);      
        }
        if($('#reportsmodel-p_sub_district_code').val() != ''){
            $('#reportsmodel-p_block_name').removeAttr('disabled', 'false');
        }
    });
    
}
if('" . $report . "'=='InchargeRemuneration'){
     $('#reportsmodel-p_mcc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_mcc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_mcc_code . "'=='0'){
            $('#reportsmodel-p_mcc_code').val(0);      
        }
        if($('#reportsmodel-p_plant_code').val() == '0'){
            $('#reportsmodel-p_mcc_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_mcc_code').val(0);
        }
    });

    $('#reportsmodel-p_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_bmc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if(is_not_bmc_array){
            $('#reportsmodel-p_bmc_code').val(0);        
        }
        if($('#reportsmodel-p_mcc_code').val() == '0'){
            $('#reportsmodel-p_bmc_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_bmc_code').val(0);
        }
    });
}
if('" . $report . "'!='MccDayBookDispatchHub' && '" . $report . "'!='MemberMilkCollectionSummary' && '" . $report . "'!='DcsCollectionVsDispatchGraph'&& '" . $report . "'!='BMCPayment'&& '" . $report . "'!='VendorMilkPayment'&& '" . $report . "'!='MemberMilkPayment'&& '" . $report . "'!='VendorMilkBill'&& '" . $report . "'!='MemberMilkBill'&& '" . $report . "'!='VendorBill'&& '" . $report . "'!='InchargeRemuneration'&& '" . $report . "'!='VendorMilkPaymentVarddan'&& '" . $report . "'!='MemberBillAbstract'&& '" . $report . "'!='VendorMilkBillVarddan'&& '" . $report . "'!='VendorBillMmd' && '" . $report . "'!='FarmerIncentive'&& '" . $report . "'!='VendorMilkBillSnmilk'&&'" . $report . "'!='VendorMilkBillJgf'&& '" . $report . "'!='VlccTransactionDataReport'&& '" . $report . "'!='VendorMilkBillAnig'&& '" . $report . "'!='VendorMilkBillShivPrasad'&& '" . $report . "'!='MemberMilkBillShivPrasad'&& '" . $report . "'!='VlccTransactionDataReportRegionAll'){
    $('#reportsmodel-p_plant_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_plant_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_plant_code . "'=='0'){
            $('#reportsmodel-p_plant_code   ').val(0);      
        }
        if($('#reportsmodel-p_union_code').val() == '0'){
            $('#reportsmodel-p_plant_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_plant_code').val(0);
        }
    });

    $('#reportsmodel-p_mcc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_mcc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->p_mcc_code . "'=='0'){
            $('#reportsmodel-p_mcc_code').val(0);        
        }
        if($('#reportsmodel-p_plant_code').val() == '0'){
            $('#reportsmodel-p_mcc_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_mcc_code').val(0);
        }
    });

    $('#reportsmodel-p_bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_bmc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if(is_not_bmc_array){
            $('#reportsmodel-p_bmc_code').val(0);        
        }
        if($('#reportsmodel-p_mcc_code').val() == '0'){
            $('#reportsmodel-p_bmc_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_bmc_code').val(0);
        }
    });
}
if('" . $report . "'=='ConsolidatedMilkCollectionDate' || '" . $report . "'=='ConsolidatedMilkCollectionDateShift' || '" . $report . "'=='ShiftReportNameWise' || '" . $report . "'=='SocietyWiseMemberRegister' || '" . $report . "'=='UnionWiseMemberRegister' || '" . $report . "'=='MemberWisePaymentRegister' || '" . $report . "'=='MemberClassificationRegister' || '" . $report . "'=='MemberPaymentHeldup' || '" . $report . "'=='SocietyDetails' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport' || '" . $report . "'=='VariationMilkTypeDateWise' || '" . $report . "'=='VariationMilkTypeVillageWise' || '" . $report . "'=='VariationDateWise' || '" . $report . "'=='VariationVillageWise' || '" . $report . "'=='VariationPercentageWise' || '" . $report . "'=='DifferenceReport' || '" . $report . "'=='DifferenceReportDateWise' || '" . $report . "'=='DifferenceReportVillageWise' || '" . $report . "'=='BmcCollection' || '" . $report . "'=='GprsDataReconciliation' || '" . $report . "'=='ActualBmcCollection'){
    $('#reportsmodel-p_dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#reportsmodel-p_dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if(is_not_dcs_array){
            $('#reportsmodel-p_dcs_code').val(0);        
        }
        if($('#reportsmodel-p_bmc_code').val()=='0'){
            $('#reportsmodel-p_dcs_code').removeAttr('disabled', 'false');
            $('#reportsmodel-p_dcs_code').val(0);
        }
    });
}

if('" . $report . "'=='MemberWisePaymentRegister' || '" . $report . "'=='MemberPaymentHeldup' || '" . $report . "'=='MemberClassificationRegister' || '" . $report . "'=='SocietyDetails'){
    $('#reportsmodel-p_member_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        if($('#reportsmodel-p_dcs_code').val()=='0'){ 
            $('#reportsmodel-p_member_code').prop('disabled',false);
            $('#reportsmodel-p_member_code').val(0);
        }
    });
}
if('" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport'){
    $('#reportsmodel-p_milk_class option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
}

$('.jasper_report_modal_toggle').on('click', function(){
    $('#jasper_report_search_filter').modal('toggle');
});

";

    if ($defaultToggle) {
        $script .= "
        $(document).ready(function () {
            $('#jasper_report_search_filter').modal('toggle');
        });
    ";
    }
    $this->registerJs($script, View::POS_READY, 'dep-drop-member');
    ?>