<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblFederations;
use app\modules\setting\models\TblProfiles;
use app\modules\geo\models\TblStates;
use app\modules\globalmaster\models\TblLandUnit;
use app\modules\dcsaccounting\models\TblFinancialYear;
use yii\web\View;
use ReflectionClass;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\db\Query;
use app\modules\organisation\models\TblRouteMappingSources;
use yii\helpers\Html;
use yii\helpers\Json;
use app\modules\geo\models\TblRegion;

class DropDown extends Component {

    private $class = 'form-group padding-right-5 col-sm-2';

    public function state($model, $form, $name = 'state_code', $islable = false, $disable = false, $multiple = false, $searchable = true) {

        $this->setClass($form, $name);
        $state = new TblStates;

        $selected = '';
        if (!empty(Yii::$app->session->get('States')) && count(explode(',', Yii::$app->session->get('States'))) == 1) {
            $selected = explode(',', Yii::$app->session->get('States'));
        }
        $model->{$name} = !empty($selected) ? $selected : $model->{$name};

        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $state->getActiveStates($model->$name), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select State', 'disabled' => $disable, 'multiple' => $multiple]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($state->getActiveStates($model->$name), ['prompt' => 'Select State', 'disabled' => $disable, 'multiple' => $multiple])->label($islable);
        }
        if (!empty($selected)) {
            $script = "$(document).ready(function() {
                    $('#" . strtolower((new ReflectionClass($model))->getShortName() . '-' . $name) . "').parent('div').parent().hide();               
                    });";
            Yii::$app->view->registerJs($script, View::POS_END, 'state-hide');
        }
    }

    public function district($model, $form, $depends, $name = 'district_code', $islable = false, $multiple = false, $readonly = false) {

        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/geo/tbl-districts/district-list', 'Select District', $multiple, $model->$name, $readonly);
    }

    public function uniondistrict($model, $form, $depends, $name = 'district_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);

        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-unions/district-list', Yii::t('app', 'Select District'), $multiple, $model->$name, $readonly);
    }

    public function unionpaymentcycle($model, $form, $depends, $name = 'dcs_payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-member-payment/payment-cycle-list', 'Select Payment Cycle', $multiple, $model->$name, $readonly);
    }

    public function unionpaymentcyclewithdate($model, $form, $depends, $name = 'dcs_payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-member-payment/payment-cycle-list-with-date', 'Select Payment Cycle', $multiple, $model->$name, $readonly);
    }

    public function dcsvillage($model, $form, $depends, $name = 'village_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/village-list', 'Select Village', $multiple, $model->$name, $readonly);
    }

    public function vendordcs($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/load-vendor-society', 'Select Society', $multiple, $model->$name);
    }

    public function bankdistrict($model, $form, $depends, $name = 'district_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-banks/bank-district-list', 'Select District', $multiple, $model->$name);
    }

    public function bankdepended($model, $form, $depends, $name = 'bank_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-banks/bank-list', 'Select Bank', $multiple);
    }

    public function federation($model, $form, $name = 'federation_code', $islable = false, $disable = false) {

        $this->setClass($form, $name);
        $federation = new TblFederations();
        echo $form->field($model, $name)->dropDownList($federation->getActiveFederation($model->$name), ['prompt' => 'Select Federation', 'disabled' => $disable])->label($islable);
    }

    public function dcsdestinationtype($model, $form, $depends, $name = 'destination_type', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/destination-list', 'Select Destination Type', $multiple);
    }

    public function routedestinationtype($model, $form, $depends, $name = 'destination_type', $islable = false, $multiple = false, $type = 'to') {
        $this->setClass($form, $name);
        $url = $type == 'to' ? '/organisation/tbl-route-mapping/to-destination-list' : '/organisation/tbl-route-mapping/from-destination-list';
        $this->dependedDropdown($model, $form, $depends, $name, $islable, $url, 'Select Destination Type', $multiple);
    }

    public function financialyear($model, $form, $name = 'financial_year_code', $islable = false, $disable = false, $searchable = true) {
        $this->setClass($form, $name);
        $routs = new TblFinancialYear();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $routs->getLatestYear(), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Year', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($routs->getLatestYear(), ['prompt' => 'Select Year', 'disabled' => $disable])->label($islable);
        }
    }

    public function profile($model, $form, $name = 'profile_id', $islable = false, $disable = false, $searchable = true) {
        $this->setClass($form, $name);
        $profiles = new TblProfiles();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $profiles->getAllRoles(), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Profile', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($profiles->getAllRoles(), ['prompt' => 'Select Profile', 'disabled' => $disable])->label($islable);
        }
    }

    public function shift($model, $form, $name = 'shift', $islable = false, $class = '', $disable = false, $searchable = true) {
        $list = ['1' => 'Morning', '2' => 'Evening'];
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $list, 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Shift', 'disabled' => $disable, 'class' => 'form-control ' . $class]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($list, ['prompt' => 'Select Shift', 'disabled' => $disable, 'class' => 'form-control ' . $class])->label($islable);
        }
    }

    public function year($model, $form, $name = 'year', $islable = false, $class = '', $disable = false, $searchable = true, $start, $end) {
        $yearStart = date('Y') - $start;
        $yearEnd = date('Y') + $end;
        $years = [];
        for ($yearStart; $yearStart <= $yearEnd; $yearStart++) {
            $years[] = $yearStart;
        }
        echo $form->field($model, $name)->dropDownList($years, ['prompt' => 'Select Year', 'disabled' => $disable, 'class' => 'form-control ' . $class])->label($islable);
    }

    private function setClass($form, $name) {
        if (array_key_exists($name, $form->options))
            $this->class = $form->options[$name];
        else if (isset($form->options['field-class']))
            $this->class = $form->options['field-class'];
    }

    public function union($model, $form, $depends, $name = 'union_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-unions/union-list', Yii::t('app', 'Select Union'), $multiple/* ,$model->$name */);
    }

    public function federation_union($model, $form, $name = 'union_code', $islable = false, $readonly = false, $searchable = true, $multiple = false) {
        $this->setClass($form, $name);
        $disable = $readonly ? 'disabled' : false;
        $islable = $islable ? Yii::t('app', $islable) : false;
        $unionModel = new \app\modules\organisation\models\TblUnions();
        $selected = '';
        if (!empty(Yii::$app->session->get('Unions')) && count(explode(',', Yii::$app->session->get('Unions'))) == 1) {
            $selected = Yii::$app->session->get('Unions');
        }
        $model->{$name} = !empty($selected) ? $selected : $model->{$name};
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $unionModel->getActiveUnions(1), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => Yii::t('app', 'Select Union'), 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name, ['options' => ['multiple' => $multiple]])->dropDownList($unionModel->getActiveUnions(1), ['prompt' => Yii::t('app', 'Select Union'), 'disabled' => $disable])->label($islable);
        }
        if (!empty($selected)) {
            $script = "$(document).ready(function() {
                   $('#" . strtolower((new ReflectionClass($model))->getShortName() . '-' . $name) . "').parent('div').parent().hide();               
                });";
            Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName() . '-' . $name));
        }
    }

    public function union_dcs($flag, $model, $form, $depends, $class = '', $label = false, $name = '', $readonly = false) {
        $check_list = '';
        $label = $label ? Yii::t('app', $label) : false;
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $check_list = explode(',', Yii::$app->session->get('Dcs'));
            $check_list = implode('-', $check_list);
        }
        return $this->depend_dropdown('dcs', $model, $form, $depends, $class, $label, $name, $readonly, 1, $check_list);
    }

    public function union_routes($model, $form, $depends, $class = '', $label = false, $name = '', $readonly = false) {
        $check_list = '';
        $routes = new TblRouteMappingSources();
        $check_list = implode('-', $routes->getRoutesWithDcs());
        return $this->depend_dropdown('routemapping', $model, $form, $depends, $class, $label, $name, $readonly, 1, $check_list);
    }

    public function route_dcs($model, $form, $depends, $name = '', $islable = false, $multiple = false, $multiselect = false, $id = '', $readonly = false) {
//$this->dependedDropdown($model, $form, $depends, $name, $islable, '/geo/tbl-districts/district-list', 'Select District', $multiple, $model->$name, $readonly);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/route-dcs-list', Yii::t('app', 'Select Dcs'), $multiple, '', $readonly, '', true, TRUE);
        } else if ($multiselect) {
            $this->dependedDropdownMultiple($model, $form, $depends, $name, $id, $islable, '/organisation/tbl-dcs/route-dcs-list');
        } else {
            return $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/route-dcs-list', Yii::t('app', 'Select Dcs'), '', '', $readonly);
        }
    }

    public function bank($model, $form, $name = 'bank_code', $islable = false, $disable = false, $searchable = true) {
        $this->setClass($form, $name);
        $data = $this->getLabels('bank');
        $records = $this->withLocal($data, $model);
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $records, 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Bank', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($records, ['prompt' => 'Select Bank', 'disabled' => $disable])->label($islable);
        }
    }

    public function defaultlandunit($model, $form, $name = 'land_unit', $islable = false, $disable = false, $searchable = true) {
        $this->setClass($form, $name);
        $routs = new TblLandUnit();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $routs->getDefaultValues(), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Data', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($routs->getDefaultValues(), ['prompt' => 'Select Data', 'disabled' => $disable])->label($islable);
        }
    }

    public function vehicle($model, $form, $name = 'vehicle_code', $islable = false, $disable = false, $km_base = false, $searchable = true) {
        $this->setClass($form, $name);
        $vehicle = new \app\modules\transporter\models\TblVehicleMaster();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $vehicle->vehicle($km_base), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Vehicle', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($vehicle->vehicle($km_base), ['prompt' => 'Select Vehicle', 'disabled' => $disable])->label($islable);
        }
    }

    public function bmcroutecode($model, $form, $name = 'route_code', $islable = false, $disable = false, $bmc_code = '', $searchable = true) {
        $this->setClass($form, $name);
        $routes = new \app\modules\organisation\models\TblRouteMapping();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $routes->route($bmc_code), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Route Code', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($routes->route($bmc_code), ['prompt' => 'Select Route Code', 'disabled' => $disable])->label($islable);
        }
    }

    public function transporterpaymentcycle($model, $form, $depends, $name = 'transporter_payment_cycle', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-transporter-payment/payment-cycle-list', 'Select Payment Cycle', $multiple, $model->$name, $readonly);
    }

    public function vehicletransporter($model, $form, $depends, $name = 'vehicle_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-vehicle-master/depend-vehicles', 'Select Vehicle', $multiple, $model->$name);
    }

    public function generalPartyMaster($model, $form, $depends, $name = 'general_party_master_code', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-general-party-master/party-list', 'Select Party', $multiple, $model->$name);
    }

    public function union_plant($model, $form, $depends, $name = 'plant_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoSelect = TRUE) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-plant/plant-list', Yii::t('app', 'Select Plant'), $multiple, $extra_param, $readonly);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-plant/plant-list', Yii::t('app', 'Select Plant'), $multiple, $extra_param, $readonly);
        }
        if ($autoSelect) {
            $script = "$(document).ready(function() {
                var modelname = '" . strtolower((new ReflectionClass($model))->getShortName()) . "';
                var fieldName = '" . strtolower($name) . "';
                $('#'+modelname+'-'+fieldName).on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    var length = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').length;
                    var plant = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').val();
                    var unionCode = $('#" . $depends . "').val();
                    if(unionCode != '' && unionCode != null && unionCode != undefined && unionCode != 'Loading ...' && length == 1) {
                        $('#'+modelname+'-'+fieldName).val(plant);
                        $('#'+modelname+'-'+fieldName).trigger('select2:select');
                        $('#'+modelname+'-'+fieldName).trigger('change');
                    }
                });
            });";
            Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName()) . '_union_plant');
        }
    }

    public function plant_mcc($model, $form, $depends, $name = 'mcc_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $multiselect = false, $id = '', $async = true) {
        $this->setClass($form, $name);
        if ($multiselect) {
            $this->dependedDropdownMultiple($model, $form, $depends, $name, $id, $islable, '/organisation/tbl-mcc-plant/mcc-list');
        } else {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-mcc-plant/mcc-list', Yii::t('app', 'Select MCC'), $multiple, $extra_param, $readonly, '', true, true, $async);
        }
        $script = "$(document).ready(function() {
            var modelname = '" . strtolower((new ReflectionClass($model))->getShortName()) . "';
            var fieldName = '" . strtolower($name) . "';
            $('#'+modelname+'-'+fieldName).on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
               var length = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').length;
                var mcc = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').val();
                var plantCode = $('#" . $depends . "').val();
                if(plantCode!='' && plantCode != null && plantCode != undefined && plantCode != 'Loading ...' && length == 1) {
                    $('#'+modelname+'-'+fieldName).val(mcc);
                    $('#'+modelname+'-'+fieldName).trigger('select2:select');
                    $('#'+modelname+'-'+fieldName).trigger('change');
                }
            });
        });";
        Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName()) . '_plant_mcc');
    }

    public function mcc_bmc($model, $form, $depends, $name = 'bmc_code', $islable = false, $multiple = false, $id = '', $extra_param = '', $readonly = false, $async = true) {
        $this->setClass($form, $name);

        $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/bmc-list', Yii::t('app', 'Select BMC'), $multiple, $extra_param, $readonly, $id, true, true, $async);
        $hasBMC = Yii::$app->session->get('hasBMC');
        $script = "$(document).ready(function() {
                    var modelname = '" . strtolower((new ReflectionClass($model))->getShortName()) . "';
                    var fieldName = '" . strtolower($name) . "';
                    var hasBMC = `$hasBMC`;
                    $('#'+modelname+'-'+fieldName).on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                       var length = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').length;
                        var bmc = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').val();
                            var mccCode = $('#" . $depends . "').val();
                        if(mccCode!='' && mccCode != null && mccCode != undefined && mccCode != 'Loading ...' && length == 0) {
                            $('#'+modelname+'-'+fieldName).parent('div').parent().show();
                        } else if(mccCode!='' && mccCode != null && mccCode != undefined && mccCode != 'Loading ...' && length == 1) {
                            $('#'+modelname+'-'+fieldName).val(bmc);
                            if(hasBMC == 0){
                                $('#'+modelname+'-'+fieldName).parent('div').parent().hide();
                            }
                            $('#'+modelname+'-'+fieldName).trigger('select2:select');
                            $('#'+modelname+'-'+fieldName).trigger('change');
                        } else if(hasBMC == 0) {
                            $('#'+modelname+'-'+fieldName).parent('div').parent().hide();               
                        }
                    });
                });";
        Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName()) . '_bmc_hide');
    }

    public function channel_bmc($model, $form, $depends, $name = 'bmc_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $multiselect = false, $id = '') {
        $this->setClass($form, $name);
        if ($multiselect) {
            $this->dependedDropdownMultiple($model, $form, $depends, $name, $id, $islable, '/organisation/tbl-dcs-bmc/channel-bmc-list');
        } else {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/channel-bmc-list', Yii::t('app', 'Select BMC'), $multiple, $extra_param, $readonly);
        }
    }

    public function bmc_society($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/dcs-list', Yii::t('app', 'Select Society'), $multiple, $extra_param, $readonly, '', true, $autoClose);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/dcs-list', Yii::t('app', 'Select Society'), $multiple, $extra_param, $readonly);
        }
    }

    public function transfer_bmc_society($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-master-transfer/dcs-list', Yii::t('app', 'Select Society'), $multiple, $extra_param, $readonly, '', true, $autoClose);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-master-transfer/dcs-list', Yii::t('app', 'Select Society'), $multiple, $extra_param, $readonly);
        }
    }

    public function bmcDropdown($model, $form, $depends, $name = 'bmc_code', $islable = '', $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/bmc-list-union', 'Select BMC', $multiple, $model->$name, $readonly);
    }

    public function mccDropDown($model, $form, $name = 'mcc_code', $islable = false, $disable = false, $id = '', $searchable = true) {
        $this->setClass($form, $name);
        $mcc = new \app\modules\organisation\models\TblMccPlant();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $mcc->getMCCList(''), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select MCC', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($mcc->getMCCList(''), ['prompt' => 'Select MCC', 'id' => $id, 'disabled' => $disable])->label($islable);
        }
    }

    public function androiddevicelist($model, $form, $depends, $name = 'device_id', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/installation/tbl-android-installation/device-list', 'Select Device', $multiple, $model->$name, $readonly);
    }

    public function configFor($model, $form, $name = 'config_for', $islable = false, $disable = false, $notin = [], $searchable = true) {
        $this->setClass($form, $name);
        $config = new \app\modules\configuration\models\TblConfig();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $config->configForList($notin), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select App', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($config->configForList($notin), ['prompt' => 'Select App', 'disabled' => $disable])->label($islable);
        }
    }

    public function all_routes($model, $form, $depends, $name = 'route_code', $islable = false, $multiple = false, $readonly = false, $is_return = FALSE, $input_name = '') {
        $this->setClass($form, $name);
        if ($is_return) {
            return $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-route-mapping/all-route-list', Yii::t('app', 'Select Route'), $multiple, $model->$name, $readonly, '', TRUE, '', $is_return, $input_name, FALSE);
        } else if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-route-mapping/all-route-list', Yii::t('app', 'Select Route'), $multiple, '', $readonly, '', true, TRUE);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-route-mapping/all-route-list', Yii::t('app', 'Select Route'), $multiple, $model->$name, $readonly, '', true, '', false, '', FALSE);
        }
    }

    public function customer_type($model, $form, $depends, $name = 'customer_type', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-customer-master/customer-type', Yii::t('app', 'Select Type'), $multiple, $model->$name, $readonly);
        $script = "$(document).ready(function() {
                        var modelname = '" . strtolower((new ReflectionClass($model))->getShortName()) . "';
                        var fieldName = '" . strtolower($name) . "';
                        $('#'+modelname+'-'+fieldName).on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                            var length = $('#'+modelname+'-'+fieldName+' option[value!=\"all\"][value!=\'\']').length;
                            if(length == 0) {
                                $('#'+modelname+'-'+fieldName).parent('div').parent().hide();
                            } else if(length == 1) {
                                $('#'+modelname+'-'+fieldName).val('DCS');
                                $('#'+modelname+'-'+fieldName).parent('div').parent().hide();
                                $('#'+modelname+'-'+fieldName).trigger('select2:select');
                                $('#'+modelname+'-'+fieldName).trigger('change');
                            } else {
                                $('#'+modelname+'-'+fieldName).parent('div').parent().show();               
                            }
                        });
                    });";
        Yii::$app->view->registerJs($script, View::POS_END, 'customer_type_hide');
    }

    public function merge_dcs_customer($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/merge-dcs-customer-list', Yii::t('app', 'Select Name'), $multiple, $extra_param, $readonly);
    }

    public function transfer_type($model, $form, $depends, $name = 'transfer_type', $islable = false, $multiple = false, $readonly = false) {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-master-transfer/transfer-type-list', Yii::t('app', 'Select Transfer Type'), $multiple, '', $readonly);
    }

    public function memberRateChart($model, $form, $depends, $name = 'rate_chart_member', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsoperation/tbl-purchase-rate/chart-list', Yii::t('app', 'Select Member Rate Chart'), $multiple);
    }

    public function unionpaymentcycledcs($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/payment-cycle-dcs-list', Yii::t('app', 'Select Dcs'), $multiple, $model->$name, $readonly);
    }

    public function bill_head($model, $form, $depends, $name = 'bill_head_code', $islable = true, $flag = 'D') {
        $this->setClass($form, $name);
        $action = ($flag == 'D') ? '/vsp/tbl-bill-head/list-dcswise' : '/vsp/tbl-bill-head/list-unionwise';
        $this->dependedDropdown($model, $form, $depends, $name, $islable, $action, 'Select Bill Head', false, $model->$name, false);
    }

    public function customer_code($model, $form, $depends, $name = 'customer_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-customer-master/customer-code-list', Yii::t('app', 'Select Name'), $multiple, $model->$name, $readonly, '', true, '', FALSE, '', FALSE);
    }

    public function activate_customer_code($model, $form, $depends, $name = 'customer_code', $islable = false, $multiple = false, $readonly = false, $async = true) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-customer-master/activate-customer-code-list', Yii::t('app', 'Select Name'), $multiple, $model->$name, $readonly, '', true, '', false, '', $async);
    }

    public function activate_member_code($model, $form, $depends, $name = 'customer_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsoperation/tbl-member/activate-member-code-list', Yii::t('app', 'Select Member'), $multiple, $model->$name, $readonly);
    }

    public function dcsRateChart($model, $form, $depends, $name = 'rate_chart_dcs', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsoperation/tbl-purchase-rate/chart-list', Yii::t('app', 'Select Rate Chart'), $multiple);
    }

    public function paymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-payment-cycle/payment-cycle-list', Yii::t('app', 'Select Payment Cycle'), $multiple, 'where', $readonly, '', true, '', false, '', false);
    }

    public function paymentCycleWithDate($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-payment-cycle/payment-cycle-list-with-date', Yii::t('app', 'Select Payment Cycle'), $multiple, 'where', $readonly);
    }

    public function billHead($model, $form, $depends, $name = 'bill_head_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/vsp/tbl-bill-head/bill-head-list', Yii::t('app', 'Select Bill Head'), $multiple, $model->$name, $readonly);
    }

    public function union_mcc($model, $form, $depends, $name = 'mcc_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-mcc-plant/union-mcc-list', Yii::t('app', 'Select MCC'), $multiple, '', $readonly);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-mcc-plant/union-mcc-list', Yii::t('app', 'Select MCC'), $multiple, $model->$name, $readonly);
        }
    }

    public function union_bmc($model, $form, $depends, $name = 'bmc_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/union-bmc-list', Yii::t('app', 'Select BMC'), $multiple, '', $readonly);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/union-bmc-list', Yii::t('app', 'Select BMC'), $multiple, $model->$name, $readonly);
        }
    }

    public function RemunerationPaymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-remuneration-summary/remuneration-payment-cycle', Yii::t('app', 'Select Payment Cycle'), $multiple, '', $readonly);
    }

    public function mccRemunerationPaymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-mcc-remuneration-summary/remuneration-payment-cycle', Yii::t('app', 'Select Payment Cycle'), $multiple, '', $readonly);
    }

    public function HoldReleasePaymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-vendor-payment-hold-release/hold-release-payment-cycle', Yii::t('app', 'Select Payment Cycle'), $multiple, '', $readonly);
    }

    public function destination_code_list($model, $form, $depends, $name = 'destination_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/tankermovement/tbl-bmc-milk-dispatch/destination-code-list', Yii::t('app', 'Select Code'), $multiple, '', $readonly);
    }

    public function processName($model, $form, $depends, $name = 'destination_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/configuration/tbl-config/config-process-list', Yii::t('app', 'Select Process Name'), $multiple, '', $readonly);
    }

    public function leaveType($model, $form, $depends, $name = 'leave_type', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/staffmanagement/tbl-staff-leave-master/leave-type', Yii::t('app', 'Select Leave Type'), $multiple, '', $readonly);
    }

    public function org_sap_code($model, $form, $depends, $name = 'sap_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-set/org-sap-code-list', Yii::t('app', 'Select SAP Code'), $multiple, $model->$name, $readonly);
    }

    public function datewise_bmc_list($model, $form, $depends, $name = 'transporter_code', $islable = false, $multiple = false, $extra_param = '', $readOnly = FALSE, $searchable = FALSE, $multiselect = false) {
        $this->setClass($form, $name);
        if ($multiselect) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/payment/tbl-transporter-payment/datewise-bmc-list', Yii::t('app', 'Select BMC'), $multiple, $extra_param, $readOnly);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-transporter-payment/datewise-bmc-list', Yii::t('app', 'Select BMC'), $multiple, $extra_param, $readOnly);
        }
    }

    public function payment_head($model, $form, $depends, $name = 'transporter_payment_head_code', $islable = false, $multiple = false, $readonly = false, $prompt = 'Select') {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-transporter-payment-head/payment-head-list', Yii::t('app', $prompt), $multiple, '', $readonly);
    }

    public function routeVehicleDateWise($model, $form, $depends, $name = 'route_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-vehicle-km-info/route-list', 'Select Route', $multiple, $model->$name, $readonly);
    }

    public function places($model, $form, $depends, $name = 'ownmccid', $islable = false, $multiple = false, $readonly = false, $prompt = 'Select') {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-mcc-plant/places-list', Yii::t('app', $prompt), $multiple, '', $readonly);
    }

    public function product($model, $form, $depends, $name = 'product_code', $islable = false, $multiple = false, $id = '', $extra_param = '', $readonly = false) {
        $this->setClass($form, $name);
        $this->select2Dropdown($model, $form, $depends, $name, $islable, '/product/tbl-product/product-list', Yii::t('app', 'Select Product'), $multiple, $extra_param, $readonly, $id);
    }

    public function moduleType($model, $form, $name = 'module_type', $islable = false, $disable = false, $searchable = true) {
        $this->setClass($form, $name);
        $alert = new \app\modules\sms\models\TblAlertNotification();
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $name)->widget(Select2::classname(), [
                'data' => $alert->getModuleType(), 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select Module Type', 'disabled' => $disable]]
            )->label($islable);
        } else {
            echo $form->field($model, $name)->dropDownList($alert->getModuleType(), ['prompt' => 'Select Vehicle', 'disabled' => $disable])->label($islable);
        }
    }

    public function merge_bmc_dcs($model, $form, $depends, $name = 'applicable_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs/merge-bmc-dcs-list', 'Select', $multiple, $model->$name, $readonly);
    }

    public function mccBillHead($model, $form, $depends, $name = 'mcc_bill_head_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/vsp/tbl-mcc-bill-head/bill-head-list', Yii::t('app', 'Select Bill Head'), $multiple, $model->$name, $readonly);
    }

    public function welfareScheme($model, $form, $depends, $name = 'scheme_id', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/welfarescheme/tbl-scheme-application/scheme-list', Yii::t('app', 'Select Scheme'), $multiple, $extra_param, $readonly, '', true, $autoClose);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/welfarescheme/tbl-scheme-application/scheme-list', Yii::t('app', 'Select Scheme'), $multiple, $extra_param, $readonly);
        }
    }

    public function welfareSchemeApplication($model, $form, $depends, $name = 'application_id', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/welfarescheme/tbl-scheme-application/application-list', Yii::t('app', 'Select Application'), $multiple, $extra_param, $readonly, '', true, $autoClose);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/welfarescheme/tbl-scheme-application/application-list', Yii::t('app', 'Select Application'), $multiple, $extra_param, $readonly);
        }
    }

    public function all_route_transporter($model, $form, $depends, $name = 'transporter_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-route-mapping/all-route-transporter-list', Yii::t('app', 'Select Transporter'), $multiple, $model->$name, $readonly);
    }

    public function vehicleOpenTrip($model, $form, $depends, $name = 'trip_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/tankermovement/tbl-vehicle-trip/open-trip-list', Yii::t('app', 'Select Trip Code'), $multiple, $extra_param, $readonly, '', true, $autoClose);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/tankermovement/tbl-vehicle-trip/open-trip-list', Yii::t('app', 'Select Trip Code'), $multiple, $extra_param, $readonly);
        }
    }

    public function vehicleOpenTripDetail($model, $form, $depends, $name = 'trip_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/tankermovement/tbl-vehicle-trip/open-trip-detail-list', Yii::t('app', 'Select Trip Code'), $multiple, $extra_param, $readonly);
    }

    public function vehicleMasterOpen($model, $form, $depends, $name = 'vehicle_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false, $autoClose = true) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-vehicle-master/vehicle-open-list', Yii::t('app', 'Select Vehicle'), $multiple, $extra_param, $readonly);
    }

    public function datewise_transporter_list($model, $form, $depends, $name = 'transporter_code', $islable = false, $multiple = false, $extra_param = '', $readOnly = FALSE, $searchable = FALSE, $multiselect = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-transporter-payment/datewise-transporter-list', Yii::t('app', 'Select Transporter'), $multiple, $extra_param, $readOnly, '', $searchable);
    }

    public function approval_level($model, $form, $depends, $name = 'applicable_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/general/tbl-approval-stages/level-list', 'Select', $multiple, $model->$name, $readonly);
    }

    public function indent_product($model, $form, $depends, $name = 'product_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-indent-product/product-list', 'Select', $multiple, $model->$name, $readonly);
    }

    public function bom_code($model, $form, $depends, $name = 'spare_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-bom/bom-list', 'Select', $multiple, $model->$name, $readonly);
    }

    public function complain_problem($model, $form, $depends, $name = 'complain_problem', $islable = false, $multiple = false, $readonly = false) {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/complaint/tbl-complain/problem-list', Yii::t('app', 'Select Problem'), $multiple, '', $readonly);
    }

    public function asset_list($model, $form, $depends, $name = 'asset_code', $islable = false, $multiple = false, $readOnly = FALSE) {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-master/asset-lists', Yii::t('app', 'Select Asset'), $multiple, '', $readOnly);
    }

    public function assign_list($model, $form, $depends, $name = 'user_code', $islable = false, $multiple = false, $readOnly = FALSE) {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/complaint/tbl-complain/assign-list', Yii::t('app', 'Select User'), $multiple, '', $readOnly);
    }

    public function task_user_selection($model, $form, $depends, $name = 'user_code', $islable = false, $multiple = false, $readOnly = FALSE) {
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/tms/tbl-task/task-user-selection', Yii::t('app', 'Select User'), $multiple, '', $readOnly);
    }

    public function asset_bom_list($model, $form, $depends, $name = 'spare_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-bom/asset-bom-list', 'Select', $multiple, $model->$name, $readonly);
    }

    public function old_sr_no($model, $form, $depends, $name = 'serial_number', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-detail-bom/old-sr-no', 'Select', $multiple, $model->$name, $readonly);
    }

    public function new_sr_no($model, $form, $depends, $name = 'serial_number', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/assetmanagement/tbl-asset-detail/new-sr-no', 'Select', $multiple, $model->$name, $readonly);
    }

    public function BonusPaymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-bonus-payment/payment-cycle-list', Yii::t('app', 'Select Payment Cycle'), $multiple, '', $readonly);
    }

    public function PartyPaymentCycle($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-party-payment/payment-cycle-list', Yii::t('app', 'Select Payment Cycle'), $multiple, '', $readonly);
    }

    public function tap_event($model, $form, $depends, $name = 'tap_event', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/general/tbl-banner/tap-event-list', Yii::t('app', 'Select Tap Event'), $multiple, '', $readonly);
    }

    public function UserList($model, $form, $depends, $name = 'user_code', $islable = false, $multiple = false, $readonly = false, $url = '/organisation/tbl-route-mapping/user-list') {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, $url, Yii::t('app', 'Select User'), $multiple, '', $readonly);
    }

    public function product_group($model, $form, $depends, $name = 'product_group_code', $islable = false, $multiple = false, $id = '', $extra_param = '', $readonly = false) {
        $this->setClass($form, $name);
        $this->select2Dropdown($model, $form, $depends, $name, $islable, '/product/tbl-product-group/product-group-list', Yii::t('app', 'Select'), $multiple, $extra_param, $readonly, $id);
    }

    public function insurance_dcs($model, $form, $depends, $name = 'dcs_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/insurance/tbl-insurance-detail-summary/dcs-list', Yii::t('app', 'Select Society'), $multiple, $model->$name, $readonly);
    }

    public function product_master($model, $form, $depends, $name = 'product_code', $islable = false, $multiple = false, $extra_param = '', $readOnly = FALSE, $searchable = FALSE, $multiselect = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-product/product-list', Yii::t('app', 'Select Product'), $multiple, $extra_param, $readOnly);
    }

    public function contactDetails($model, $form, $depends, $name = 'detail_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/details/tbl-contact-details/contact-details-list', Yii::t('app', 'Select'), $multiple, '', $readonly);
    }

    public function vehicleQaInspectionList($model, $form, $depends, $name = 'vehicle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/tankermovement/tbl-vehicle-qa-inspection/get-vehicle-list', Yii::t('app', 'Select'), $multiple, '', $readonly);
    }

    public function vehicleList($model, $form, $depends, $name = 'vehicle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-vehicle-master/get-vehicle-list', Yii::t('app', 'Select'), $multiple, '', $readonly);
    }

    public function chamberNoList($model, $form, $depends, $name = 'chamber_no', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/transporter/tbl-vehicle-master/get-chamber-list', Yii::t('app', 'Select Chamber'), $multiple, '', $readonly);
    }

    public function ledgerList($model, $form, $depends, $name = 'ledger_code', $islable = false, $multiple = false, $readonly = false, $is_return = false, $input_name = '') {
        $this->setClass($form, $name);
        return $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsaccounting/tbl-ledgers/get-ledger-list', Yii::t('app', 'Select Ledger'), $multiple, '', $readonly, '', true, '', $is_return, $input_name);
    }

    public function depend_select2($model, $form, $name, $url, $dep_id = '') {
        echo $form->field($model, $name)->widget(Select2::classname(), [
            'initValueText' => 'Products', // set the initial display text
            'options' => ['placeholder' => 'Search here...', 'class' => 'form-group'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'type' => 'post',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { 
                    var did="' . $dep_id . '";
                    var dep_code="";
                    if(did!=="")
                    {
                       dep_code=$("#"+did).val();
                    }
                    return {q:params.term,depend_code:dep_code}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ]]);
    }

    private function dependedDropdown($model, $form, $depends, $name, $islable = false, $url = '', $placeholder = '', $multiple = false, $extraParam = '', $readonly = false, $id = '', $searchable = true, $session = '', $is_return = FALSE, $input_name = '', $async = true) {
        $class = $readonly ? 'depend-control' : '';
        $depends = explode(',', $depends);

        $options = [];
        $options['readonly'] = $readonly;
        $options['class'] = 'form-control ' . $class;
        if (!empty($id)) {
            $options['id'] = $id;
        }
        if ($multiple)
            $placeholder = FALSE;
        $dropDownType = DepDrop::TYPE_DEFAULT;
        if (isset($searchable) && $searchable) {
            $dropDownType = DepDrop::TYPE_SELECT2;
        }
//        $name = ($name == '') ? $data['name'] : $name;

        $selected = '';
        if ($session == 'session_union' && !empty(Yii::$app->session->get('Unions')) && count(explode(',', Yii::$app->session->get('Unions'))) == 1) {
            $selected = Yii::$app->session->get('Unions');
            $model->{$name} = !empty($selected) ? $selected : $model->{$name};
        }
//         'select2Options' => ['pluginOptions' => ['allowClear' => true,]],
        $selfId = strtolower((new ReflectionClass($model))->getShortName() . '-' . $name);
        if ($is_return) {
            return $form->field($model, !empty($input_name) ? $input_name : $name)
                            ->widget(DepDrop::classname(), [
                                'type' => $dropDownType,
                                'data' => [$model->{$name} => $model->{$name}],
                                'name' => !empty($input_name) ? $input_name : $name,
                                'select2Options' => ['options' => ['placeholder' => $placeholder], 'pluginOptions' => ['allowClear' => true]],
                                'options' => ['multiple' => $multiple],
                                'pluginOptions' => [
                                    'depends' => $depends,
                                    'placeholder' => $placeholder,
                                    'url' => Url::to([$url]),
                                    'allParam' => ["'" . $extraParam . "'"],
                                    'initialize' => true,
                                    'allowClear' => true,
                                    'ajaxSettings' => [
                                        'async' => $async,
                                        'beforeSend' => new \yii\web\JsExpression("
                                            function(jqXHR, settings) {
                                                return handleDepdropBeforeSend({
                                                    depends: " . json_encode($depends) . ",
                                                    selfId: '{$selfId}'
                                                });
                                            }
                                        "),
                                    ],
                                ],
                                'options' => $options
                            ])->label($islable);
        } else {
            echo $form->field($model, $name)
                    ->widget(DepDrop::classname(), [
                        'type' => $dropDownType,
                        'data' => [$model->{$name} => $model->{$name}],
                        'name' => $name,
                        'select2Options' => ['options' => ['placeholder' => $placeholder], 'pluginOptions' => ['allowClear' => true]],
                        'options' => ['multiple' => $multiple],
                        'pluginOptions' => [
                            'depends' => $depends,
                            'placeholder' => $placeholder,
                            'url' => Url::to([$url]),
                            'allParam' => ["'" . $extraParam . "'"],
                            'initialize' => true,
                            'allowClear' => true,
                            'ajaxSettings' => [
                                'async' => $async,
                                'beforeSend' => new \yii\web\JsExpression("
                                    function(jqXHR, settings) {
                                        return handleDepdropBeforeSend({
                                            depends: " . json_encode($depends) . ",
                                            selfId: '{$selfId}'
                                        });
                                    }
                                "),
                            ],
                        ],
                        'options' => $options
                    ])->label($islable);
        }

        if (!empty($selected)) {
            $script = "$(document).ready(function() {
                   $('#" . strtolower((new ReflectionClass($model))->getShortName() . '-' . $name) . "').parent('div').parent().hide();               
                    });";
            Yii::$app->view->registerJs($script, View::POS_END, strtolower((new ReflectionClass($model))->getShortName() . '-' . $name));
        }
    }

    public function depend_dropdown($flag, $model, $form, $depends, $class = '', $label = false, $name = '', $readonly = false, $check = 0, $checkList = [], $multiselect = FALSE, $prompt = '', $tab = FALSE, $searchable = true, $multiselect2Dropdown = false, $async = true, $listView = false) {
        $data = $this->getLabels($flag);
        $fields = explode(',', $data['fields']);
        $checkValid = in_array('checkValid', $data);
        $field_value = !empty($model->{$fields[0]}) ? $model->{$fields[0]} : 0;
        if (is_array($field_value)) {
            $field_value = implode('*', $field_value);
        }
        $control_name = ($name == '') ? $data['name'] : $name;
        $dependArray = !empty($data['dependArray']) ? $data['dependArray'] : [];
        $placeholder = $data['prompt'];
        $url = '/site/get-data';
        $allParam = [$data['model'], $data['depend'], $field_value, $data['fields'], $check, $checkList, $checkValid, $dependArray];

        if ($multiselect2Dropdown) {
            $this->select2Dropdown($model, $form, $depends, $name, $label, $url, $placeholder, $multiselect, $allParam, $readonly, '', $searchable, true, $async, $listView);
            return;
        }
        if ($multiselect) {
            $this->depend_dropdown_multiple($flag, $model, $form, $depends, $class, $label, $name, $check, $checkList);
            return;
        }

        $class = $readonly ? 'depend-control' : '';
        $depends = explode(',', $depends);

        $tabIndex = ($tab) ? -1 : '';
        $dropDownType = DepDrop::TYPE_DEFAULT;
        if (isset($searchable) && $searchable) {
            $dropDownType = DepDrop::TYPE_SELECT2;
        }
        $selfId = strtolower((new ReflectionClass($model))->getShortName() . '-' . $control_name);
        echo $form->field($model, $control_name)
                ->widget(DepDrop::classname(), [
                    'type' => $dropDownType,
                    'data' => [$model->{$control_name} => $model->{$control_name}],
                    'name' => $control_name,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'pluginOptions' => [
                        'depends' => $depends,
                        'placeholder' => $placeholder,
                        'url' => Url::to([$url]),
                        'allParam' => $allParam,
                        'initialize' => true,
                        'ajaxSettings' => [
                            'async' => $async,
                            'beforeSend' => new \yii\web\JsExpression("
                                function(jqXHR, settings) {
                                    return handleDepdropBeforeSend({
                                        depends: " . json_encode($depends) . ",
                                        selfId: '{$selfId}'
                                    });
                                }
                            "),
                        ],
                    ],
                    'options' => [
                        'readonly' => $readonly,
                        'class' => 'form-control ' . $class,
                        'tabindex' => $tabIndex
                    ]
                ])->label($label);
    }

    public function dropdown($flag, $model, $form, $class = 'form-group padding-right-5 col-sm-2', $label = false, $disable = false, $name = '', $addAll = false, $searchable = true, $multiple = false, $listView = false) {
        $data = $this->getLabels($flag);
        $control_name = ($name == '') ? $data['name'] : $name;
        $records = $this->withoutLocal($data, $model);

        if (in_array($flag, array(('shift_applicability')))) {
            arsort($records, SORT_NATURAL | SORT_FLAG_CASE);
        }
        if ($addAll) {
            $records = [0 => 'All'] + $records;
        }
        $htmlOptions = ['prompt' => $data['prompt'], 'disabled' => $disable];
        if ($listView) {
            unset($htmlOptions['prompt']);
            $htmlOptions['multiple'] = true;
            $htmlOptions['style'] = 'height: 150px;';
        }
        if (isset($searchable) && $searchable) {
            return $form->field($model, $control_name)->widget(Select2::classname(), [
                        'data' => $records, 'pluginOptions' => ['allowClear' => true, 'multiple' => $multiple], 'options' => ['placeholder' => $data['prompt'], 'disabled' => $disable]]
                    )->label($label);
        }
        return $form->field($model, $control_name)->dropDownList($records, $htmlOptions)->label($label);
    }

    public function withoutLocal($labelData, $old_model) {
        $fields = explode(',', $labelData['fields']);
        $model_name = Yii::$app->path->define($labelData['model']);
        $model = new $model_name();
        $tablename = $model->tablename();
        $where = [];
        if ($model->hasAttribute('is_active')) {
            $where[$tablename . '.is_active'] = 1;
        }
        $whereCondition = [];
        if (isset($labelData['whereCondition'])) {
            foreach ($labelData['whereCondition'] as $key => $value) {
                if (strpos($key, '.') !== false) {
                    $whereCondition[$key] = $value;
                } else {
                    $whereCondition[$tablename . '.' . $key] = $value;
                }
            }
        }
        if (isset($labelData['rlsWhereCondition']) && !empty($labelData['applyRls'])) {
            foreach ($labelData['rlsWhereCondition'] as $key => $value) {
                $whereCondition[$key] = $value;
            }
        }
        $where = array_merge($whereCondition, $where);

        $select_fields[] = $tablename . '.' . $fields[0];
        if (isset($labelData['concatfield'])) {
            $select_fields[] = "CONCAT(" . $tablename . "." . $fields[1] . ',\' - \',' . $labelData["concatfield"] . ") AS " . $fields[1];
        } else {
            $select_fields[] = $tablename . '.' . $fields[1];
        }

        if (!empty($fields[2])) {
            array_push($select_fields, $fields[2]);
        }
        if (!empty($fields[3])) {
            array_push($select_fields, $fields[3]);
        }

        if (isset($old_model->{$fields[0]}) && $old_model->{$fields[0]} != '') {
            $unionQuery = $model->find()
                    ->select($select_fields);
            if (isset($labelData['joinwith'])) {
                foreach ($labelData['joinwith'] as $val) {
                    $unionQuery->joinWith($val);
                }
            }
            $unionQuery->where([$tablename . '.' . $fields[0] => $old_model->{$fields[0]}])
                            ->createCommand()->rawSql;

            $tmp_query = $model->find()->select($select_fields);
            if (isset($labelData['joinwith'])) {
                foreach ($labelData['joinwith'] as $val) {
                    $tmp_query->joinWith($val);
                }
            }
            $tmp_query->where($where)->union($unionQuery);
            $query = new Query();
            $records = $query->select('*')->from(['u' => $tmp_query])->orderBy($fields[1])->all();
        } else {
            $records = $model->find()->select($select_fields);
            if (isset($labelData['joinwith'])) {
                foreach ($labelData['joinwith'] as $val) {
                    $records = $records->joinWith($val);
                }
            }
            $records = $records->where($where)->orderBy($tablename . '.' . $fields[1])->all();
        }

        return ArrayHelper::map($records, $fields[0], function($array, $key) use ($fields) {
                    $fields[2] = isset($fields[2]) ? substr(strrchr($fields[2], "."), 1) ?: $fields[2] : '';
                    if (!empty($fields[2]) && !empty($array[$fields[2]]))
                        $value = $array[$fields[1]] . '(' . $array[$fields[2]] . ')';
                    else
                        $value = $array[$fields[1]];

                    $fields[3] = isset($fields[3]) ? substr(strrchr($fields[3], "."), 1) ?: $fields[3] : '';
                    if (!empty($fields[3]) && !empty($array[$fields[3]]))
                        $value = $value . ' - ' . $array[$fields[3]];
                    return $value;
                });
    }

    public function dropdownStatic($flag, $model, $form, $class = 'form-group padding-right-5 col-sm-2', $label = false, $disable = false, $name = '', $addAll = false, $removeKey = false, $searchable = true, $return = false, $multiple = false) {
        if (in_array($flag, ['organizations_type'])) {
            (Yii::$app->session->get('organizations_type') == 'UNION') ? $flag = 'organizations_type_union' : $flag = 'organizations_type_federation';
        }
        $data = $this->getRecords($flag);
        $control_name = ($name == '') ? $data['name'] : $name;
        $records = $data['data'];

//client wise dropdown option remove
        $client_code = \Yii::$app->session->get('eiplCode');
        if (isset($data['client_wise_rmv']) && isset($data['client_wise_rmv'][$client_code])) {
            foreach ($data['client_wise_rmv'][$client_code] as $value) {
                unset($records[$value]);
            }
        }

        if (!in_array($flag, array('p_type', 'payment_release_type'))) {
            asort($records, SORT_NATURAL | SORT_FLAG_CASE);
        }

        if ($addAll) {
            $records = [0 => Yii::t('app', 'All')] + $records;
        }
        if ($removeKey) {
            foreach ($data['remove_key'] as $value) {
                unset($records[$value]);
            }
        }

        if ($return) {
            return $form->field($model, $control_name, ['options' => ['class' => $class]])->widget(Select2::classname(), [
                        'data' => $records, 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => $data['prompt'], 'disabled' => $disable, 'class' => $class]]
                    )->label($label);
        }
        if ($multiple) {
            return $form->field($model, $control_name, ['options' => ['class' => $class]])->widget(Select2::classname(), [
                        'data' => $records, 'pluginOptions' => ['allowClear' => true, 'multiple' => $multiple], 'options' => ['placeholder' => $data['prompt'], 'disabled' => $disable, 'class' => $class]]
                    )->label($label);
        }
        if (isset($searchable) && $searchable) {
            echo $form->field($model, $control_name, ['options' => ['class' => $class]])->widget(Select2::classname(), [
                'data' => $records, 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => $data['prompt'], 'disabled' => $disable, 'class' => $class]]
            )->label($label);
        } else {
            $options = ['prompt' => Yii::t('app', $data['prompt']), 'disabled' => $disable];
            if ($data['prompt'] === FALSE) {
                unset($options['prompt']);
            }
            echo $form->field($model, $control_name, ['options' => ['class' => $class]])->dropDownList($records, $options)->label(Yii::t('app', $label));
        }
    }

    public function dropdownfilterStatic($flag, $model, $name = '', $class = 'form-control', $addAll = false) {
        $data = $this->getRecords($flag);
        $control_name = ($name == '') ? $data['name'] : $name;
        $records = $data['data'];

        if (!in_array($flag, array('consumer_type_member', 'gender'))) {
            asort($records, SORT_NATURAL | SORT_FLAG_CASE);
        }

        if ($addAll) {
            $records = [0 => 'All'] + $records;
        }
        return Html::activeDropDownList($model, $control_name, $records, ['class' => $class, 'prompt' => $data['prompt']]);
    }

    private function dependedDropdownMultiple($model, $form, $depends, $name, $id = '', $islable = false, $url = '', $placeholder = '', $multiple = true, $extraParam = '', $readonly = false) {
        $depends = explode(',', $depends);
        $class = $readonly ? 'depend-control' : '';
        $selfId = strtolower((new ReflectionClass($model))->getShortName() . '-' . $name);
        if ($multiple)
            $placeholder = FALSE;
        echo $form->field($model, $name, ['options' => ['class' => $class]])->widget(DepDropComp::classname(), [
            'type' => DepDropComp::TYPE_MULTISELECT,
            'options' => [
                'multiple' => true,
            ],
            'model' => $model,
            'attribute' => $name,
            'data' => !empty($model->{$name}) ? array_values($model->{$name}) : [''],
            'value' => !empty($model->{$name}) ? array_values($model->{$name}) : [0],
            'multiSelectOptions' => [
                'id' => $id,
                'clientOptions' =>
                    [
                    'includeSelectAllOption' => true,
                    'numberDisplayed' => 1,
                    'disableIfEmpty' => true,
                    'buttonClass' => 'form-control text-left',
                    'buttonContainer' => '<div class="form-group"/>',
                    'buttonWidth' => '100%'
                ],
            ],
            'pluginOptions' => [
                'depends' => [$depends],
                'placeholder' => false,
                'url' => Url::to([$url]),
                'allParam' => ["'" . $extraParam . "'"],
                'initialize' => true,
                'ajaxSettings' => [
                    'beforeSend' => new \yii\web\JsExpression("
                        function(jqXHR, settings) {
                            return handleDepdropBeforeSend({
                                depends: " . json_encode($depends) . ",
                                selfId: '{$selfId}'
                            });
                        }
                    "),
                ],
            ]
        ])->label(Yii::t('app', $islable));
    }

    public function area_bmc($model, $form, $depends, $name = 'bmc_code', $islable = false, $multiple = false, $readonly = false, $async = true, $searchable = false, $listView = false) {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/geo/tbl-area/area-bmc-list', Yii::t('app', 'Select BMC'), $multiple, '', $readonly, '', $searchable, true, $async, $listView);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/geo/tbl-area/area-bmc-list', Yii::t('app', 'Select BMC'), $multiple, $model->$name, $readonly);
        }
    }

    public function getRecords($l) {
        $records = [
            'with_and_without_milktype' => [
                'name' => 'with_and_without_milktype',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['With Milk Type' => Yii::t('app', 'With Milk Type'), 'Without Milk Type' => Yii::t('app', 'Without Milk Type')],
            ],
            'p_type' => [
                'name' => 'p_type',
                'prompt' => Yii::t('app', 'Select Parameters'),
                'data' => [0 => Yii::t('app', 'Quantity (Ltr)'), 2 => Yii::t('app', 'Fat%'), 1 => Yii::t('app', 'SNF%'), 4 => Yii::t('app', 'Amount (Rs.)'), 3 => Yii::t('app', 'Pourers No.')],
            ],
            'organizations_type_union' => [
                'name' => 'organizations_type_union',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'UNION')],
            ],
            'organizations_type_federation' => [
                'name' => 'organizations_type_federation',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [0 => Yii::t('app', 'FEDERATION'), 1 => Yii::t('app', 'UNION')],
            ],
            'vendor' => [
                'name' => 'vendor',
                'prompt' => Yii::t('app', 'Select Vendor'),
                'data' => ['BIPL' => Yii::t('app', 'BIPL'), 'EIPL' => Yii::t('app', 'EIPL'), 'REIL' => Yii::t('app', 'REIL')],
            ],
            'member_class' => [
                'name' => 'member_class',
                'prompt' => Yii::t('app', 'Select Class'),
                'data' => [1 => Yii::t('app', 'APL'), 2 => Yii::t('app', 'BPL'), 3 => Yii::t('app', 'Class A'), 4 => Yii::t('app', 'Class B'), 5 => Yii::t('app', 'Class C')],
            ],
            'bank_status' => [
                'name' => 'bank_status',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [0 => Yii::t('app', 'With Bank'), 1 => Yii::t('app', 'Without Bank')],
            ],
            'frequency_data' => [
                'name' => 'frequency',
                'prompt' => Yii::t('app', 'Select Frequency'),
                'data' => [3 => Yii::t('app', '3 Hours'), 6 => Yii::t('app', '6 Hours'), 9 => Yii::t('app', '9 Hours'), 12 => Yii::t('app', '12 Hours')],
            ],
            'credit_type_data' => [
                'name' => 'credit_type',
                'prompt' => Yii::t('app', 'Select Credit Type'),
                'data' => [0 => Yii::t('app', 'Fixed'), 1 => Yii::t('app', 'Variable')],
            ],
            'calc_type' => [
                'name' => 'type',
                'prompt' => Yii::t('app', 'Select Head Type'),
                'data' => [0 => Yii::t('app', 'Addition'), 1 => Yii::t('app', 'Deduction')],
            ],
            'collection_type' => [
                'name' => 'collection_type',
                'prompt' => Yii::t('app', 'Select Collection Type'),
                'data' => [1 => Yii::t('app', 'Self'), 2 => Yii::t('app', 'Transporter')],
            ],
            'shift' => [
                'name' => 'shift',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['M' => Yii::t('app', 'Morning'), 'E' => Yii::t('app', 'Evening')],
            ],
            'milktype' => [
                'name' => 'milktype',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['C' => Yii::t('app', 'Cow'), 'B' => Yii::t('app', 'Buffalo'), 'M' => Yii::t('app', 'Mix')],
            ],
            'approval_status' => [
                'name' => 'is_approved',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Pending'), '1' => Yii::t('app', 'Approved'), '2' => Yii::t('app', 'Reject')],
            ],
            'update_status' => [
                'name' => 'is_updated',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Created'), '1' => Yii::t('app', 'Updated')],
            ],
            'p_ltr_kg' => [
                'name' => 'p_ltr_kg',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Ltr'), '1' => Yii::t('app', 'Kg')],
            ],
            'organization_type' => [
                'name' => 'organization_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['AMCS' => Yii::t('app', 'AMCS'), 'BMC' => Yii::t('app', 'BMC')],
            ],
            'default_snf' => [
                'name' => 'default_snf',
                'prompt' => Yii::t('app', 'Select Default SNF'),
                'data' => ['0' => Yii::t('app', 'No'), '1' => Yii::t('app', 'Yes')],
            ],
            'weight_setting' => [
                'name' => 'weight_setting',
                'prompt' => Yii::t('app', 'Select Weight'),
                'data' => ['1' => Yii::t('app', 'Single Digit Truncate'), '2' => Yii::t('app', 'Double Digit Truncate'), '3' => Yii::t('app', 'Single Digit Round'), '4' => Yii::t('app', 'Double Digit Round')],
            ],
            'quality_setting' => [
                'name' => 'x_col1',
                'prompt' => Yii::t('app', 'Select Quality'),
                'data' => ['1' => Yii::t('app', 'Round'), '2' => Yii::t('app', 'Truncate')],
            ],
            'collection_mode' => [
                'name' => 'collection_mode',
                'prompt' => Yii::t('app', 'Select Collection Mode'),
                'data' => ['SINGLE_MACHINE' => Yii::t('app', 'SINGLE_MACHINE'), 'DUAL_BY_TYPE' => Yii::t('app', 'DUAL_BY_TYPE'), 'DUAL_SEQ' => Yii::t('app', 'DUAL_SEQ')],
            ],
            'qty_mode' => [
                'name' => 'qty_mode',
                'prompt' => Yii::t('app', 'Select Qty Mode'),
                'data' => ['0' => Yii::t('app', 'Liter'), '1' => Yii::t('app', 'kg')],
            ],
            'based_on' => [
                'name' => 'based_on',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Shift' => Yii::t('app', 'Shift'), 'Day' => Yii::t('app', 'Day'), 'Payment Cycle' => Yii::t('app', 'Payment Cycle')],
            ],
            'shift_type' => [
                'name' => 'shift_type',
                'prompt' => Yii::t('app', 'Select Shift'),
                'data' => ['0' => Yii::t('app', 'All'), '1' => Yii::t('app', 'Morning/Evening')],
            ],
            'based_on_local' => [
                'name' => 'based_on_local',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Day' => Yii::t('app', 'Day')],
            ],
            'disp_in' => [
                'name' => 'disp_in',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Can'), '1' => Yii::t('app', 'Tanker')],
            ],
            'sap_collection_type' => [
                'name' => 'sap_collection_type',
                'prompt' => Yii::t('app', 'Select Collection Type'),
                'data' => ['0' => Yii::t('app', 'Member'), '1' => Yii::t('app', 'DCS')],
            ],
            'dispatch_setting' => [
                'name' => 'dispatch_setting',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'Auto Shift Lock'), '2' => Yii::t('app', 'Manual Shift Lock'), '3' => Yii::t('app', 'On Dispatch Shift Lock')],
            ],
            'destination_type' => [
                'name' => 'destination_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'BMC'), '1' => Yii::t('app', 'MCC'), '2' => Yii::t('app', 'PLANT')],
            ],
            'file_operation_type' => [
                'name' => 'file_operation_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Import'), 1 => Yii::t('app', 'Export')],
            ],
            'db_version' => [
                'name' => 'db_version',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['everest_amcs.db' => Yii::t('app', '1')],
            ],
            'installation_type' => [
                'name' => 'installation_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Online'), 1 => Yii::t('app', 'Offline')],
            ],
            'allow_app_login' => [
                'name' => 'allow_app_login',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')],
            ],
            'dpu_type' => [
                'name' => 'dpu_type',
                'prompt' => Yii::t('app', 'Select DPU Type'),
                'data' => [8 => Yii::t('app', '8 - Bit'), 32 => Yii::t('app', '32 - Bit'), 0 => Yii::t('app', 'Android AMCS'), 91 => Yii::t('app', 'BIPL'), 1 => Yii::t('app', 'Mobile AMCS'), 92 => Yii::t('app', 'PROMPT'), 93 => Yii::t('app', 'BIPL SMART')],
            ],
            'file_type' => [
                'name' => 'source_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'DPU'), 2 => Yii::t('app', 'AMCS')],
            ],
            'file_status' => [
                'name' => 'file_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Picked'), 2 => Yii::t('app', 'Processed'), 3 => Yii::t('app', 'Error'), 5 => Yii::t('app', 'Archive')],
            ],
            'is_dispatch_mandate' => [
                'name' => 'is_dispatch_mandate',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Not Require'), 1 => Yii::t('app', 'Require'), 2 => Yii::t('app', 'Auto')],
            ],
            'route_login_type' => [
                'name' => 'login_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['UNION' => Yii::t('app', 'UNION'), 'PLANT' => Yii::t('app', 'PLANT'), 'MCC' => Yii::t('app', 'MCC'), 'BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS'), 'ROUTE' => Yii::t('app', 'ROUTE')],
            ],
            'dcs_union_login_type' => [
                'name' => 'login_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['UNION' => Yii::t('app', 'UNION'), 'PLANT' => Yii::t('app', 'PLANT'), 'MCC' => Yii::t('app', 'MCC'), 'BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS')],
            ],
            'login_type' => [
                'name' => 'login_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['ALL' => Yii::t('app', 'ALL'), 'MEMBER' => Yii::t('app', 'MEMBER'), 'MCC' => Yii::t('app', 'MCC'), 'BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS'), 'PLANT' => Yii::t('app', 'PLANT'), 'ROUTE' => Yii::t('app', 'ROUTE'), 'UNION' => Yii::t('app', 'UNION')],
                'remove_key' => ['ALL']
            ],
            'is_quality_auto' => [
                'name' => 'is_quality_auto',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'MANUAL'), 1 => Yii::t('app', 'AUTOMATIC')],
            ],
            'boolean_value' => [
                'name' => 'boolean_value',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')],
            ],
            'rate_cal_for' => [
                'name' => 'rate_cal_for',
                'prompt' => Yii::t('app', 'Select Recalc For'),
                'data' => ['member' => Yii::t('app', 'Member'), 'bmc' => Yii::t('app', 'BMC'), 'both' => Yii::t('app', 'Both')],
                'remove_key' => ['both'],
            ],
            'payment_mode' => [
                'name' => 'payment_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Cash'), 1 => Yii::t('app', 'Credit')],
                'remove_key' => ['0'],
            ],
            'sap_file_status' => [
                'name' => 'sap_file_status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Sent'), 2 => Yii::t('app', 'Success'), 3 => Yii::t('app', 'Error'), 4 => Yii::t('app', 'Re-Sent')],
            ],
            'sap_file_type' => [
                'name' => 'sap_file_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['TblMilkCollection' => Yii::t('app', 'SD'), 'TblBmcCollection' => Yii::t('app', 'WQ')],
            ],
            'sap_data_post_status' => [
                'name' => 'sap_data_post_status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'In-Process'), 2 => Yii::t('app', 'Success'), 3 => Yii::t('app', 'Error')],
            ],
            'source_org_type' => [
                'name' => 'source_org_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['plant' => Yii::t('app', 'Plant'), 'bmc' => Yii::t('app', 'BMC')],
            ],
            'return_type' => [
                'name' => 'return_type',
                'prompt' => Yii::t('app', 'Select milk Return Type'),
                'data' => ['0' => Yii::t('app', 'Return Back'), 1 => Yii::t('app', 'Drain')],
            ],
            'bill_head_type' => [
                'name' => 'bill_head_type',
                'prompt' => Yii::t('app', 'Select Head Type'),
                'data' => [0 => Yii::t('app', 'Addition'), 1 => Yii::t('app', 'Deduction')],
            ],
            'loss_applied_to' => [
                'name' => 'loss_applied_to',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'VSP'), 2 => Yii::t('app', 'TPT')],
            ],
            'originating_type' => [
                'name' => 'originating_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Import'), 2 => Yii::t('app', 'Sync'), 3 => Yii::t('app', 'Auto Entry'), 4 => Yii::t('app', 'Pendrive Import'), 5 => Yii::t('app', 'Create'),],
            ],
            'p_organization_type' => [
                'name' => 'organization_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MCC' => Yii::t('app', 'MCC'), 'BMC' => Yii::t('app', 'BMC'), 'VLC' => Yii::t('app', 'DCS')],
            ],
            'rate_type' => [
                'name' => 'rate_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'BMC' => Yii::t('app', 'BMC')],
            ],
            'bill_head_for' => [
                'name' => 'bill_head_for',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'VENDOR' => Yii::t('app', 'VENDOR'), 'TRANSPORTER' => Yii::t('app', 'TRANSPORTER')],
            ],
            'bank_type' => [
                'name' => 'bank_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['IOB' => Yii::t('app', 'IOB'), 'Federal' => Yii::t('app', 'Federal'), 'AU' => Yii::t('app', 'AU'), 'NEFT' => Yii::t('app', 'NEFT'), 'HDFCNEFT' => Yii::t('app', 'HDFCNEFT'), 'HDFC' => Yii::t('app', 'HDFC'), 'BOM' => Yii::t('app', 'BOM')],
                'client_wise_rmv' => [
                    'ABT' => ['AU', 'Federal', 'IOB', 'NEFT']
                ]
            ],
            'payment_mode_member' => [
                'name' => 'payment_mode_member',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Cash'), 1 => Yii::t('app', 'Bank')],
            ],
            'lwp_type' => [
                'name' => 'lwp_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Full'), 0 => Yii::t('app', 'Half')],
            ],
            'type' => [
                'name' => 'type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Addition'), 0 => Yii::t('app', 'Deduction')],
            ],
            'designation_type' => [
                'name' => 'designation_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Staff'), 1 => Yii::t('app', 'Committee')],
            ],
            'report_status' => [
                'name' => 'report_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'With Milk Type'), '0' => Yii::t('app', 'Without Milk Type')],
            ],
            'is_type' => [
                'name' => 'is_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')],
            ],
            'requisition_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Draft' => Yii::t('app', 'Draft'), 'Sent' => Yii::t('app', 'Sent'), 'Rejected' => Yii::t('app', 'Rejected'), 'Under Dispatch' => Yii::t('app', 'Under Dispatch'), 'Dispatched' => Yii::t('app', 'Dispatched'), 'Delivered' => Yii::t('app', 'Delivered')],
            ],
            'requisition_type' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS')],
            ],
            'chamber_no' => [
                'name' => 'chamber_no',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4'],
            ],
            'owning_type' => [
                'name' => 'owning_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Own'), '1' => Yii::t('app', 'Rent')],
            ],
            'rate_recalc_type' => [
                'name' => 'recalc_type',
                'prompt' => Yii::t('app', 'Select Method'),
                'data' => ['all' => Yii::t('app', 'Rate ID Wise'), 'custom' => Yii::t('app', 'WEF Date Wise')],
            ],
            'is_on_role' => [
                'name' => 'is_on_role',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'On Role'), '0' => Yii::t('app', 'Off Role')],
            ],
            'leave_type' => [
                'name' => 'leave_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'PL'), 2 => Yii::t('app', 'SL'), 3 => Yii::t('app', 'CL'), 4 => Yii::t('app', 'C-Off'), 5 => Yii::t('app', 'LWP')],
            ],
            'receipt_at' => [
                'name' => 'receipt_at',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['PLANT' => Yii::t('app', 'PLANT'), 'VENDOR' => Yii::t('app', 'VENDOR')],
            ],
            'entry_type' => [
                'name' => 'entry_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['INDIVIDUAL' => Yii::t('app', 'Individual'), 'CONSOLIDATED' => Yii::t('app', 'Consolidated')],
            ],
            'route_type' => [
                'name' => 'route_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Can' => Yii::t('app', 'Can'), 'Tanker' => Yii::t('app', 'Tanker')],
            ],
            'EIPL_dpu_type' => [
                'name' => 'dpu_type',
                'prompt' => Yii::t('app', 'Select DPU Type'),
                'data' => [8 => Yii::t('app', '8 - Bit'), 32 => Yii::t('app', '32 - Bit'), 0 => Yii::t('app', 'Android AMCS'), 1 => Yii::t('app', 'Mobile AMCS')],
            ],
            'BIPL_dpu_type' => [
                'name' => 'dpu_type',
                'prompt' => Yii::t('app', 'Select DPU Type'),
                'data' => [91 => Yii::t('app', 'BIPL'), 93 => Yii::t('app', 'BIPL SMART')],
            ],
            'PROMPT_dpu_type' => [
                'name' => 'dpu_type',
                'prompt' => Yii::t('app', 'Select DPU Type'),
                'data' => [92 => Yii::t('app', 'PROMPT')],
            ],
            'vendor_type' => [
                'name' => 'vendor_type',
                'prompt' => Yii::t('app', 'Select Vendor'),
                'data' => ['BIPL' => Yii::t('app', 'BIPL'), 'EIPL' => Yii::t('app', 'EIPL'), 'PROMPT' => Yii::t('app', 'PROMPT')],
            ],
            'action_perform' => [
                'name' => 'action_perform',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['CREATE' => Yii::t('app', 'CREATE'), 'UPDATE' => Yii::t('app', 'UPDATE'), 'DELETE' => Yii::t('app', 'DELETE')],
                'remove_key' => ['CREATE'],
            ],
            'process_type' => [
                'name' => 'process_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'RATE' => Yii::t('app', 'RATE')],
            ],
            'data_type' => [
                'name' => 'data_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['PLAIN' => Yii::t('app', 'PLAIN'), 'ENCRYPTED' => Yii::t('app', 'ENCRYPTED')],
            ],
            'billing_based_on' => [
                'name' => 'Based On',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'RMRD' => Yii::t('app', 'RMRD')],
            ],
            'data_type_filter' => [
                'name' => 'data_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'All')],
            ],
            'asset_status' => [
                'name' => 'asset_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['-1' => Yii::t('app', 'In-Transit'), 0 => Yii::t('app', 'In-Store'), 1 => Yii::t('app', 'Outward'), 2 => Yii::t('app', 'In-Use')],
            ],
            'asset_type' => [
                'name' => 'asset_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'New'), 1 => Yii::t('app', 'Faulty')],
            ],
            'transporter_type' => [
                'name' => 'transporter_type',
                'prompt' => Yii::t('app', 'Billing Type'),
                'data' => [0 => Yii::t('app', 'Primary'), 1 => Yii::t('app', 'Secondary')],
            ],
            'billing_method' => [
                'name' => 'billing_method',
                'prompt' => Yii::t('app', 'Billing Type'),
                'data' => ['fix_rent_daily' => Yii::t('app', 'Fix Rent Daily'), 'km_wise_rate' => Yii::t('app', 'Km Wise Rate'), 'qty_wise_rate' => Yii::t('app', 'Qty Wise Rate'), 'fix_rent_daily_fuel_consumption' => Yii::t('app', 'Fix Rent Daily Fuel Consumption'), 'fix_rent_monthly_diesel_engine_oile' => Yii::t('app', 'Fix Rent Monthly Engine Oil'), 'fix_rent_daily_diesel_engine_oile' => Yii::t('app', 'Fix Rent Daily Engine Oil')],
            ],
            'default_milk_type' => [
                'name' => 'default_milk_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'C'), 2 => Yii::t('app', 'B'), 3 => Yii::t('app', 'M'), 4 => Yii::t('app', 'C/B'), 5 => Yii::t('app', 'B/M'), 6 => Yii::t('app', 'C/M'), 7 => Yii::t('app', 'ALL'), 8 => Yii::t('app', 'Auto')],
            ],
            'rate_class' => [
                'name' => 'rate_class',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'A'), 2 => Yii::t('app', 'B'), 3 => Yii::t('app', 'C')],
            ],
            'apply_for' => [
                'name' => 'apply_for',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Vendor'), 1 => Yii::t('app', 'Member')],
            ],
            'penalty_type' => [
                'name' => 'penalty_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Shortage'), 2 => Yii::t('app', 'Spoilage'), 3 => Yii::t('app', 'Spillage')],
            ],
            'report_collection_type' => [
                'name' => 'report_collection_type',
                'prompt' => Yii::t('app', 'Select Collection Type'),
                'data' => [0 => Yii::t('app', 'Milk Collection'), 1 => Yii::t('app', 'BMC Collection')],
            ],
            'type_wise_report' => [
                'name' => 'report_type_2',
                'prompt' => Yii::t('app', 'Select Report Type'),
                'data' => [0 => Yii::t('app', 'DCS Wise Monthly Comparison'), 1 => Yii::t('app', 'BMC Wise Monthly Comparison'), 2 => Yii::t('app', 'Route Wise Monthly Comparisom')],
            ],
            'dpu_status' => [
                'name' => 'dpu_status',
                'prompt' => Yii::t('app', 'Select DPU Status'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Error'), 2 => Yii::t('app', 'Processed'), -1 => Yii::t('app', 'All')],
            ],
            'billing_type' => [
                'name' => 'billing_type',
                'prompt' => Yii::t('app', 'Select Billing Type'),
                'data' => [1 => Yii::t('app', 'Member Payment + VSP Salary'), 2 => Yii::t('app', 'VSP Payment')],
            ],
            'order_on' => [
                'name' => 'order_on',
                'prompt' => Yii::t('app', 'Select Order On'),
                'data' => [1 => Yii::t('app', 'Date Shift - CC'), 2 => Yii::t('app', 'CC- Date Shift')],
            ],
            'loss_responsibility' => [
                'name' => 'loss_responsibility',
                'prompt' => Yii::t('app', 'Select TS Loss Responsibility'),
                'data' => [1 => Yii::t('app', 'Center Incharge'), 2 => Yii::t('app', 'Transporter'), 3 => Yii::t('app', 'MCC'), 4 => Yii::t('app', 'NONE')],
            ],
            'master_type' => [
                'name' => 'master_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'DCS' => Yii::t('app', 'DCS'), 'CUSTOMER' => Yii::t('app', 'CUSTOMER')],
            ],
            'route_type_trans' => [
                'name' => 'route_type_trans',
                'prompt' => Yii::t('app', 'Select Route Type'),
                'data' => ['0' => Yii::t('app', 'Transaction'), '1' => Yii::t('app', 'Master')],
            ],
            'check_for' => [
                'name' => 'check_for',
                'prompt' => Yii::t('app', 'Select Check For'),
                'data' => ['data_lock_bmc' => Yii::t('app', 'Data Lock - BMC'), 'sync_lock_bmc' => Yii::t('app', 'Sync Lock - BMC'), 'data_lock_member' => Yii::t('app', 'Data Lock - Member'), 'sync_lock_member' => Yii::t('app', 'Sync Lock - Member')],
            ],
            'data_status' => [
                'name' => 'data_status',
                'prompt' => Yii::t('app', 'Select Check For'),
                'data' => [0 => Yii::t('app', 'Lock'), 1 => Yii::t('app', 'Unlock')],
            ],
            'status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'In-Active'), '1' => Yii::t('app', 'Active')],
            ],
            'party_rate_type' => [
                'name' => 'party_rate_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Qty' => Yii::t('app', 'Qty'), '60/40' => Yii::t('app', '60/40'), '52/48' => Yii::t('app', '52/48')],
            ],
            'originating_type_flag' => [
                'name' => 'originating_type_flag',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Manual'), 1 => Yii::t('app', 'Excel'), 2 => Yii::t('app', 'GPRS'), 11 => Yii::t('app', '8-BIT'), 12 => Yii::t('app', '32-BIT'), 13 => Yii::t('app', 'EIPL'), 21 => Yii::t('app', 'BIPL'), 22 => Yii::t('app', 'BIPL'), 23 => Yii::t('app', 'AMCS'), 24 => Yii::t('app', 'AMCS LIVE')],
            ],
            'rate_price_type' => [
                'name' => 'rate_price_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Increase Rate'), 2 => Yii::t('app', 'Decrease Rate')],
            ],
            'antibiotic' => [
                'name' => 'antibiotic',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['AB+' => Yii::t('app', 'AB+'), 'AB-' => Yii::t('app', 'AB-')],
            ],
            'collection' => [
                'name' => 'collection',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['consecutive' => Yii::t('app', 'consecutive'), 'non-consecutive' => Yii::t('app', 'non-consecutive')],
            ],
            'send_status' => [
                'name' => 'send_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Picked'), 2 => Yii::t('app', 'Success'), 3 => Yii::t('app', 'Error')],
            ],
            'org_type' => [
                'name' => 'org_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS')],
            ],
            'approval_collection_type' => [
                'name' => 'approval_collection_type',
                'prompt' => Yii::t('app', 'Select Collection Type'),
                'data' => [1 => Yii::t('app', 'Milk Collection'), 2 => Yii::t('app', 'BMC Collection')],
            ],
            'rate_digit' => [
                'name' => 'rate_digit',
                'prompt' => Yii::t('app', 'Select Rate Digit'),
                'data' => [4 => Yii::t('app', '4-Digit'), 5 => Yii::t('app', '5-Digit')],
            ],
            'vehicle_use_type' => [
                'name' => 'vehicle_use_type',
                'prompt' => Yii::t('app', 'Billing Type'),
                'data' => [0 => Yii::t('app', 'Primary'), 1 => Yii::t('app', 'Secondary'), 2 => Yii::t('app', 'Both')],
            ],
            'billing_qty_flag' => [
                'name' => 'billing_qty_flag',
                'prompt' => Yii::t('app', 'Select Flag'),
                'data' => [1 => Yii::t('app', 'Actual QTY'), 2 => Yii::t('app', 'Vehicle Capacity'), 3 => Yii::t('app', 'Higher QTY')],
            ],
            'is_single_farmer' => [
                'name' => 'is_single_farmer',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Yes'), 0 => Yii::t('app', 'No')],
            ],
            'place_type' => [
                'name' => 'Type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['bmc' => Yii::t('app', 'BMC'), 'mcc' => Yii::t('app', 'MCC'), 'plant' => Yii::t('app', 'PLANT'), 'vendor' => Yii::t('app', 'VENDOR')],
            ],
            'product_type' => [
                'name' => 'Type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'Service'), '2' => Yii::t('app', 'Product')],
            ],
            'weigh_type' => [
                'name' => 'weigh_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'In'), 2 => Yii::t('app', 'Out')],
            ],
            'billing_for' => [
                'name' => 'billing_for',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'MEMBER'), 2 => Yii::t('app', 'VENDOR')],
            ],
            'tab_type' => [
                'name' => 'tab_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'EVEREST 7-INCH'), 2 => Yii::t('app', 'EVEREST 8-INCH'), 3 => Yii::t('app', 'STELLAPPS 7-INCH'), 4 => Yii::t('app', 'STELLAPPS 10-INCH'), 5 => Yii::t('app', 'STELLAPPS 8-INCH'), 6 => Yii::t('app', 'EVEREST ESP')],
            ],
            'applicability_type' => [
                'name' => 'applicability_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'BMC'), 2 => Yii::t('app', 'DCS'), 3 => Yii::t('app', 'Plant')],
            ],
            'transfer_type' => [
                'name' => 'transfer_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Send'), 1 => Yii::t('app', 'Receive')],
            ],
            'calc_based_on' => [
                'name' => 'type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['milk_collection' => Yii::t('app', 'Farmer Collection'), 'bmc_collection' => Yii::t('app', 'BMC Collection'), 'milk_collection_attendance' => Yii::t('app', 'Farmer Collection Attendance'), 'bmc_collection_attendance' => Yii::t('app', 'BMC Collection Attendance'), 'defined_amount_only' => Yii::t('app', 'Defined Amount Only'), 'final_addition' => Yii::t('app', 'Final Addition')],
            ],
            'sap_file' => [
                'name' => 'sap_file',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['0' => Yii::t('app', 'WQ'), '1' => Yii::t('app', 'VM')],
            ],
            'payment_cycle_type' => [
                'name' => 'payment_cycle_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['first' => Yii::t('app', 'First'), 'second' => Yii::t('app', 'Second'), 'third' => Yii::t('app', 'Third'), 'consecutive' => Yii::t('app', 'Consecutive')],
            ],
            'approved_status' => [
                'name' => 'approved_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Pending'), '1' => Yii::t('app', 'Approved')],
            ],
            'doc_group' => [
                'name' => 'doc_group',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['address_proof' => Yii::t('app', 'Address Proof'), 'application_form' => Yii::t('app', 'Application Form'), 'other' => Yii::t('app', 'Other'), 'bank_related' => Yii::t('app', 'Bank Related')],
            ],
            'doc_ext' => [
                'name' => 'doc_ext',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['.docx' => Yii::t('app', 'Word'), '.pdf' => Yii::t('app', 'Pdf'), 'image/jpeg' => Yii::t('app', 'JPG/JPEG')],
            ],
            'approval_level' => [
                'name' => 'level',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Level 1'), 2 => Yii::t('app', 'Level 2'), 3 => Yii::t('app', 'Level 3'), 4 => Yii::t('app', 'Level 4'), 5 => Yii::t('app', 'Level 5'), 6 => Yii::t('app', 'Level 6')],
            ],
            'approval_mode' => [
                'name' => 'approval_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['flexi' => Yii::t('app', 'Flexible'), 'strict' => Yii::t('app', 'Strict')],
            ],
            'ws_payment_mode' => [
                'name' => 'payment_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['online' => Yii::t('app', 'Online'), 'cheque' => Yii::t('app', 'Cheque')],
            ],
            'ws_approval_status' => [
                'name' => 'application_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['approved' => Yii::t('app', 'Approve'), 'rejected' => Yii::t('app', 'Reject')],
            ],
            'application_status' => [
                'name' => 'application_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['pending' => Yii::t('app', 'Pending'), 'registered' => Yii::t('app', 'Registered'), 'inprocess' => Yii::t('app', 'Inprocess'), 'approved' => Yii::t('app', 'Approved'), 'rejected' => Yii::t('app', 'Rejected'), 'disbursed' => Yii::t('app', 'Disbursed')],
            ],
            'applicable_for' => [
                'name' => 'applicable_for',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['self' => Yii::t('app', 'SELF'), 'other' => Yii::t('app', 'OTHER')],
            ],
            'plant_register_type' => [
                'name' => 'entry_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['CONSOLIDATED' => Yii::t('app', 'Consolidated'), 'INDIVIDUAL' => Yii::t('app', 'Individual')],
            ],
            'receiver_type' => [
                'name' => 'receiver_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['APP NOTIFICATION' => Yii::t('app', 'APP NOTIFICATION')],
            ],
            'notification_type' => [
                'name' => 'notification_type',
                'prompt' => Yii::t('app', 'Select Notification Type'),
                'data' => [1 => Yii::t('app', 'Alert'), 2 => Yii::t('app', 'Priptra'), 3 => Yii::t('app', 'AMCS Special Message'), 4 => Yii::t('app', 'Milk Bill') . ' (' . Yii::t('app', 'DCS') . ' Ref Code )', 5 => Yii::t('app', 'Bacteria Test'), 6 => Yii::t('app', 'Eipl Bill'), 7 => Yii::t('app', 'Multi Priptra'), 8 => Yii::t('app', 'Milk Bill') . ' (' . Yii::t('app', 'DCS') . ' Code Ex )'],
            ],
            'month' => [
                'name' => 'month',
                'prompt' => Yii::t('app', 'Select Month'),
                'data' => ['01' => Yii::t('app', 'Jan'), '02' => Yii::t('app', 'Feb'), '03' => Yii::t('app', 'Mar'), '04' => Yii::t('app', 'Apr'), '05' => Yii::t('app', 'May'), '06' => Yii::t('app', 'Jun'), '07' => Yii::t('app', 'Jul'), '08' => Yii::t('app', 'Aug'), '09' => Yii::t('app', 'Sep'), '10' => Yii::t('app', 'Oct'), '11' => Yii::t('app', 'Nov'), '12' => Yii::t('app', 'Dec')],
            ],
            'cash_payment' => [
                'name' => 'payment_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Cash')],
            ],
            'stop_payment_type' => [
                'name' => 'stop_reason',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['data_missing' => Yii::t('app', 'Data Missing'), 'dispute' => Yii::t('app', 'Dispute'), 'recovery_issue' => Yii::t('app', 'Recovery Issue')],
            ],
            'approval_mode' => [
                'name' => 'approval_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['flexi' => Yii::t('app', 'Flexible'), 'strict' => Yii::t('app', 'Strict')],
            ],
            'approval_type' => [
                'name' => 'approval_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'User'), '2' => Yii::t('app', 'Login Type')],
            ],
            'indent_approval_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Pending'), '1' => Yii::t('app', 'Partially Approved'), '2' => Yii::t('app', 'Approved'), '3' => Yii::t('app', 'Reject'), '4' => Yii::t('app', 'Cancelled'), '5' => Yii::t('app', 'Dispatch'), '6' => 'Accepted'],
            ],
            'indent_type' => [
                'name' => 'type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['mcc' => Yii::t('app', 'MCC Wise'), 'warehouse' => Yii::t('app', 'Warehouse Wise')],
            ],
            'animal_req_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Pending'), '1' => Yii::t('app', 'InProgress'), '2' => Yii::t('app', 'Complete'), '3' => Yii::t('app', 'Doctor Visited')],
            ],
            'product_stock_issue_reason' => [
                'name' => 'reason',
                'prompt' => Yii::t('app', 'Select Reason'),
                'data' => ['consumed' => Yii::t('app', 'Consumed'), 'screaped' => Yii::t('app', 'Screaped'), 'lost' => Yii::t('app', 'Lost')],
            ],
            'asset_detail_status' => [
                'name' => 'current_status',
                'prompt' => Yii::t('app', 'Select Asset Detail Status'),
                'data' => ['0' => Yii::t('app', 'New'), '1' => Yii::t('app', 'Repaired'), '2' => Yii::t('app', 'Faulty'), '3' => Yii::t('app', 'Scrapped')],
            ],
            'complain_for' => [
                'name' => 'complain_for',
                'prompt' => Yii::t('app', 'Select Complain For'),
                'data' => ['general_complain' => Yii::t('app', 'General Complain Type'), 'asset_complain' => Yii::t('app', 'Asset Complain Type')],
            ],
            'location_type' => [
                'name' => 'location_type',
                'prompt' => Yii::t('app', 'Select Location Type'),
                'data' => ['1' => Yii::t('app', 'Plant'), '2' => Yii::t('app', 'BMC'), '3' => Yii::t('app', 'DCS')],
            ],
            'complain_status' => [
                'name' => 'complain_status',
                'prompt' => Yii::t('app', 'Select Complain Status'),
                'data' => ['CREATED' => Yii::t('app', 'CREATED'), 'INPROGRESS' => Yii::t('app', 'INPROGRESS'), 'CLOSED' => Yii::t('app', 'CLOSED'), 'RESOLVED' => Yii::t('app', 'RESOLVED'), 'ASSIGNED' => Yii::t('app', 'ASSIGNED')],
            ],
            'resolved_status' => [
                'name' => 'resolved_status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => ['other' => Yii::t('app', 'Other'), 'service' => Yii::t('app', 'Service'), 'repair' => Yii::t('app', 'Repair'), 'replace' => Yii::t('app', 'Replace')],
            ],
            'activity_type' => [
                'name' => 'activity_type',
                'prompt' => Yii::t('app', 'Select Activity Status'),
                'data' => ['CREATED' => Yii::t('app', 'CREATED'), 'ASSIGN' => Yii::t('app', 'ASSIGN'), 'RE-ASSIGN' => Yii::t('app', 'RE-ASSIGN'), 'SERVICE' => Yii::t('app', 'SERVICE')],
            ],
            'old_spare_status' => [
                'name' => 'old_spare_status',
                'prompt' => Yii::t('app', 'Select Activity Status'),
                'data' => ['0' => Yii::t('app', 'Faulty'), '1' => Yii::t('app', 'Scrapped')],
            ],
            'task_performed_for' => [
                'name' => 'task_performed_for',
                'prompt' => Yii::t('app', 'Select Location Type'),
                'data' => ['PLANT' => Yii::t('app', 'PLANT'), 'BMC' => Yii::t('app', 'BMC'), 'DCS' => Yii::t('app', 'DCS')],
            ],
            'task_repeat_interval' => [
                'name' => 'task_repeat_interval',
                'prompt' => Yii::t('app', 'Select Repeat Interval'),
                'data' => ['0' => Yii::t('app', 'DO not Repeat'), '1' => Yii::t('app', 'Daily'), '2' => Yii::t('app', 'Weekly')],
            ],
            'master_types' => [
                'name' => 'master_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['plant' => Yii::t('app', 'Plant'), 'bmc' => Yii::t('app', 'BMC'), 'dcs' => Yii::t('app', 'DCS'), 'member' => Yii::t('app', 'Member'), 'mcc' => Yii::t('app', 'MCC'), 'transporter' => Yii::t('app', 'Transporter'), 'vehicle' => Yii::t('app', 'Vehicle'), 'provisional_member' => Yii::t('app', 'Provisional Member'), 'provisional_dcs' => Yii::t('app', 'Provisional DCS'), 'provisional_customer' => Yii::t('app', 'Provisional Customer'), 'customer' => Yii::t('app', 'Customer Master')],
            ],
            'payment_release_type' => [
                'name' => 'payment_release_type',
                'prompt' => FALSE,
                'data' => ['0' => Yii::t('app', 'With Release'), '1' => Yii::t('app', 'W/O Release')],
            ],
            'dcs_manual_collection_range_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Pending'), 2 => Yii::t('app', 'Approved'), 3 => Yii::t('app', 'Reject'), 4 => Yii::t('app', 'Close')],
            ],
            'payment_type' => [
                'name' => 'payment_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['MEMBER' => Yii::t('app', 'MEMBER'), 'VENDOR' => Yii::t('app', 'VENDOR')],
            ],
            'provisional_approval_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => Yii::t('app', 'Approve'), '2' => Yii::t('app', 'Reject')],
            ],
            'customer_category' => [
                'name' => 'customer category',
                'prompt' => Yii::t('app', 'Select Customer Category'),
                'data' => ['small' => Yii::t('app', 'small'), 'medium' => Yii::t('app', 'medium'), 'large' => Yii::t('app', 'large')],
            ],
            'provisional_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Pending' => Yii::t('app', 'Pending'), 'Register' => Yii::t('app', 'Register'), 'Inprogress' => Yii::t('app', 'Inprogress'), 'Approve' => Yii::t('app', 'Approve'), 'Reject' => Yii::t('app', 'Reject'), 'Reroute' => Yii::t('app', 'Re-route')],
            ],
            'org_type_shift_time' => [
                'name' => 'org_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['BMC' => Yii::t('app', 'BMC'), 'MCC' => Yii::t('app', 'MCC')],
            ],
            'collection_type_shift_time' => [
                'name' => 'collection_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['WEIGHT' => Yii::t('app', 'WEIGHT'), 'QUALITY' => Yii::t('app', 'QUALITY'), 'BMC' => Yii::t('app', 'BMC'), 'MEMBER' => Yii::t('app', 'MEMBER')],
            ],
            'tanker_rate_for' => [
                'name' => 'rate_for',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['purchase' => Yii::t('app', 'Purchase'), 'sale' => Yii::t('app', 'Sale')],
            ],
            'tanker_rate_gen_method' => [
                'name' => 'rate_gen_method_code',
                'prompt' => Yii::t('app', 'Select Rate Method'),
                'data' => ['1' => Yii::t('app', 'Formula'), '3' => Yii::t('app', 'Import')],
            ],
            'tanker_rate_type' => [
                'name' => 'rate_type_code',
                'prompt' => Yii::t('app', 'Select Rate Type'),
                'data' => ['1' => Yii::t('app', 'FAT+SNF'), '2' => Yii::t('app', 'QTY')],
            ],
            'party_payment_type' => [
                'name' => 'payment_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['purchase' => Yii::t('app', 'PURCHASE'), 'sale' => Yii::t('app', 'SALE')],
            ],
            'org_type_exceed' => [
                'name' => 'org_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['BMC' => Yii::t('app', 'BMC'), 'MCC' => Yii::t('app', 'MCC'), 'VLC' => Yii::t('app', 'VLC')],
            ],
            'banner_tap_operation' => [
                'name' => 'tap_operation',
                'prompt' => Yii::t('app', 'Select Tap Operation'),
                'data' => ['internal' => Yii::t('app', 'Internal'), 'external' => Yii::t('app', 'External'), 'pdf' => Yii::t('app', 'PDF')],
            ],
            'chiller_rate_type' => [
                'name' => 'rate_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['QTY' => Yii::t('app', 'QTY'), 'TS' => Yii::t('app', 'TS')],
            ],
            'txn_type' => [
                'name' => 'txn_type',
                'prompt' => Yii::t('app', 'Select txn type'),
                'data' => ['BIPL' => Yii::t('app', 'BIPL'), 'BIPLZIP' => Yii::t('app', 'BIPLZIP')],
            ],
            'feedback_status' => [
                'name' => 'feedback_status',
                'prompt' => Yii::t('app', 'Select Feedback Status'),
                'data' => [0 => Yii::t('app', 'Open'), 1 => Yii::t('app', 'In Process'), 2 => Yii::t('app', 'Closed')],
            ],
            'report_req_status' => [
                'name' => 'report_req_status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Picked'), 2 => Yii::t('app', 'Processed'), 3 => Yii::t('app', 'Error'), 4 => Yii::t('app', 'Blocked')],
            ],
            'performance_type' => [
                'name' => 'performance_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => ['0' => Yii::t('app', 'High'), '1' => Yii::t('app', 'Low')],
            ],
            'billing_type' => [
                'name' => 'billing_type',
                'prompt' => Yii::t('app', 'Select Payment Type'),
                'data' => ['remuneration' => Yii::t('app', 'Remuneration'), 'regular' => Yii::t('app', 'Regular')],
            ],
            'mode_of_payment' => [
                'name' => 'mode_of_payment',
                'prompt' => Yii::t('app', 'Select Payment Mode'),
                'data' => ['1' => Yii::t('app', 'Cash'), '2' => Yii::t('app', 'DD'), '3' => Yii::t('app', 'Check'), '4' => Yii::t('app', 'Other')],
            ],
            'machine_owned_type' => [
                'name' => 'machine_owned_type',
                'prompt' => Yii::t('app', 'Select Machine Owned Type'),
                'data' => [1 => Yii::t('app', 'Self'), 2 => Yii::t('app', 'Company')],
            ],
            'language_list' => [
                'name' => 'language_code',
                'prompt' => Yii::t('app', 'Select Language'),
                'data' => [0 => Yii::t('app', 'English'), 1 => Yii::t('app', 'Local')],
            ],
            'indent_group_by' => [
                'name' => 'group_by',
                'prompt' => Yii::t('app', 'Select Group By'),
                'data' => [0 => Yii::t('app', 'INDIVIDUAL'), 1 => Yii::t('app', 'DCS') . '-' . Yii::t('app', 'MEMBER')],
            ],
            'customer_type' => [
                'name' => 'customer_type',
                'prompt' => Yii::t('app', 'Select Customer Type'),
                'data' => ['0' => Yii::t('app', 'DCS'), '1' => Yii::t('app', 'Member')],
            ],
            'vcg_mrg_member_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => ['DRAFT' => Yii::t('app', 'DRAFT'), 'APPROVED' => Yii::t('app', 'APPROVED'), 'REJECTED' => Yii::t('app', 'REJECTED'), 'INACTIVATE' => Yii::t('app', 'INACTIVATE')],
            ],
            'vcg_mrg_meeting_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => ['CREATE' => Yii::t('app', 'CREATE'), 'IN PROGRESS' => Yii::t('app', 'IN PROGRESS'), 'COMPLETED' => Yii::t('app', 'COMPLETED')],
            ],
            'vcg_mrg_meeting_mom_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => ['OPEN' => Yii::t('app', 'OPEN'), 'IN PROGRESS' => Yii::t('app', 'IN PROGRESS'), 'CLOSED' => Yii::t('app', 'CLOSED'), 'CARRY FORWARD' => Yii::t('app', 'CARRY FORWARD')],
            ],
            'verified_flag' => [
                'name' => 'is_verify',
                'prompt' => Yii::t('app', 'Select Verified Flag'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Verify')],
            ],
            'basis_on' => [
                'name' => 'basis_on',
                'prompt' => Yii::t('app', 'Select Report Type'),
                'data' => [
                    1 => Yii::t('app', 'Date shift wise with milk type'),
                    2 => Yii::t('app', 'Date shift wise with out milk type'),
                    3 => Yii::t('app', 'Date wise with milk type'),
                    4 => Yii::t('app', 'Date wise with out milk type'),
                    5 => Yii::t('app', 'Month wise with milk type'),
                    6 => Yii::t('app', 'Month wise with out milk type'),
                    7 => Yii::t('app', 'Year wise with milk type'),
                    8 => Yii::t('app', 'Year wise with out milk type'),
                    9 => Yii::t('app', 'Consolidate milk type'),
                    10 => Yii::t('app', 'Consolidate with out milk type')
                ],
            ],
            'top_collection_on' => [
                'name' => 'top_collection_on',
                'prompt' => Yii::t('app', 'Select Report Type'),
                'data' => [1 => Yii::t('app', 'DCS Wise Member Wise'), 2 => Yii::t('app', 'Union Wise DCS Wise')
                ],
            ],
            'param_type' => [
                'name' => 'param_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Qty'), 2 => Yii::t('app', 'Amount')
                ],
            ],
            'erp_process_name' => [
                'name' => 'process_name',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Inventory Plant Dispatch'), 2 => Yii::t('app', 'Milk Receipt')],
            ],
            'data_post_status' => [
                'name' => 'data_post_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Pending'), 1 => Yii::t('app', 'Picked'), 2 => Yii::t('app', 'Sent To SAP'), 3 => Yii::t('app', 'ERROR'), 4 => Yii::t('app', 'Ack Initiated'), 5 => Yii::t('app', 'SAP Ack SUCCESS')],
            ],
            'organization_latlong_type' => [
                'name' => 'Type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['DCS' => Yii::t('app', 'DCS'), 'BMC' => Yii::t('app', 'BMC'), 'MCC' => Yii::t('app', 'MCC'), 'PLANT' => Yii::t('app', 'PLANT'), 'BULKVEN' => Yii::t('app', 'VENDOR'), 'HOME' => Yii::t('app', 'HOME'), 'OFFICE' => Yii::t('app', 'OFFICE'), 'OTHER' => Yii::t('app', 'OTHER')],
            ],
            'table_name' => [
                'name' => 'table_name',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['tbl_milk_collection' => Yii::t('app', 'Milk Collection'), 'tbl_bmc_collection' => Yii::t('app', 'BMC Collection')],
            ],
            'entry_type_collection' => [
                'name' => 'entry_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['backdate' => Yii::t('app', 'Back Date'), 'realtime' => Yii::t('app', 'Real Time')],
            ],
            'manual_approve_status' => [
                'name' => 'approval_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Pending' => Yii::t('app', 'Pending'), 'Inprogress' => Yii::t('app', 'Inprogress'), 'Approve' => Yii::t('app', 'Approve'), 'Reject' => Yii::t('app', 'Reject')],
            ],
            'billing_method_chiller_info' => [
                'name' => 'billing_method',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['fix_rent_daily' => Yii::t('app', 'Fix Rent Daily'), 'fix_rent_monthly' => Yii::t('app', 'Fix Rent Monthly')],
            ],
            'mcc_calc_based_on' => [
                'name' => 'type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['bmc_collection' => Yii::t('app', 'BMC Collection'), 'bmc_collection_attendance' => Yii::t('app', 'BMC Collection Attendance')],
            ],
            'product_requisition_type' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['DCS' => Yii::t('app', 'DCS')],
            ],
            'consumer_type' => [
                'name' => 'consumer_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Member'), 2 => Yii::t('app', 'Non-Member'), 3 => Yii::t('app', 'Vendor'), 4 => Yii::t('app', 'Institute'), 5 => Yii::t('app', 'Retail Sale'), 6 => Yii::t('app', 'Customer'), 7 => Yii::t('app', 'Other')],
            ],
            'credit_debit' => [
                'name' => 'credit_debit',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'Credit'), 0 => Yii::t('app', 'Debit')],
            ],
            'gender' => [
                'name' => 'gender',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => 'Male', 2 => 'Female', 3 => 'Transgender'],
            ],
            'originating_org_type' => [
                'name' => 'originating_org_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['PORTAL' => Yii::t('app', 'Portal'), 'VLC' => Yii::t('app', 'DCS')],
            ],
            'complaint_type' => [
                'name' => 'complaint_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Onsite'), 2 => Yii::t('app', 'Offsite'), 3 => Yii::t('app', 'Telephonic')
                ],
            ],
            'priority' => [
                'name' => 'priority',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'High'), 2 => Yii::t('app', 'Medium'), 3 => Yii::t('app', 'Low')
                ],
            ],
            'resolution_type' => [
                'name' => 'resolution_type',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Field Visit'), 2 => Yii::t('app', 'Telephonic')
                ],
            ],
            'complaint_status' => [
                'name' => 'complaint_status',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Create'), 2 => Yii::t('app', 'Assign'), 3 => Yii::t('app', 'Re-Assign'), 4 => Yii::t('app', 'Resolved')
                ],
            ],
            'hold_type' => [
                'name' => 'hold_type',
                'prompt' => Yii::t('app', 'Hold Type'),
                'data' => ['next_payment' => Yii::t('app', 'Next Payment'), 'permanent' => Yii::t('app', 'Permanent')],
            ],
            'operation_perform' => [
                'name' => 'action_perform',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['CREATE' => Yii::t('app', 'CREATE'), 'MODIFY' => Yii::t('app', 'MODIFY')],
            ],
            'indent_customer_type' => [
                'name' => 'customer_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['DCS' => Yii::t('app', 'DCS'), 'BULKVEN' => Yii::t('app', 'Customer')],
            ],
            'trip_status' => [
                'name' => 'trip_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['tankerfull' => Yii::t('app', 'Tankerfull'), 'open' => Yii::t('app', 'Open'), 'generated' => Yii::t('app', 'Generated'), 'closed' => Yii::t('app', 'Closed')],
            ],
            'lot_quality_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['discarded' => Yii::t('app', 'Discarded'), 'done' => Yii::t('app', 'Done'), 'pending' => Yii::t('app', 'Pending')],
            ],
            'insurance_status' => [
                'name' => 'status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['DRAFT' => Yii::t('app', 'Draft'), 'PUBLISH' => Yii::t('app', 'Publish'), 'PARTIAL_FINALIZE' => Yii::t('app', 'Partial Finalize'), 'FINALIZE' => Yii::t('app', 'Finalize')],
            ],
            'process_names' => [
                'name' => 'process_name',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Member Provisional' => Yii::t('app', 'Member Provisional'), 'Member Provisional Family Detail' => Yii::t('app', 'Member Provisional Family Detail')],
            ],
            'security_return_mode' => [
                'name' => 'security_return_mode',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['Bank' => Yii::t('app', 'Bank'), 'Cheque' => Yii::t('app', 'Cheque')],
            ],
            'milk_sale_on' => [
                'name' => 'milk_sale_on',
                'prompt' => Yii::t('app', 'Select Report Type'),
                'data' => [1 => Yii::t('app', 'Date shift wise'), 2 => Yii::t('app', 'Date wise')
                ],
            ],
            'billing_on' => [
                'name' => 'billing_on',
                'prompt' => Yii::t('app', 'Select Type'),
                'data' => [1 => Yii::t('app', 'Payment Cycle Wise'), 2 => Yii::t('app', 'Month Wise'), 3 => Yii::t('app', 'Year Wise'), 4 => Yii::t('app', 'Consolidate')
                ],
            ],
            'dispatch_type' => [
                'name' => 'dispatch_type',
                'prompt' => Yii::t('app', 'Select Dispatch Type'),
                'data' => ['summary' => Yii::t('app', 'Summary'), 'detail' => Yii::t('app', 'Detail')],
            ],
            'general_party_type' => [
                'name' => 'party_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['VEHICLE' => Yii::t('app', 'VEHICLE'), 'EMPLOYEE' => Yii::t('app', 'EMPLOYEE'), 'CHILLER' => Yii::t('app', 'CHILLER')],
            ],
            'party_type' => [
                'name' => 'party_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['conversion_vendor' => Yii::t('app', 'conversion_vendor'), 'sales_party' => Yii::t('app', 'sales_party'), 'sales_office' => Yii::t('app', 'sales_office')],
            ],
            'record_status' => [
                'name' => 'record_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['hold' => Yii::t('app', 'Hold'), 'accepted' => Yii::t('app', 'Accepted'), 'rejected' => Yii::t('app', 'Rejected')],
            ],
            'quality_config_process_name' => [
                'name' => 'process_name',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['BMC_MILK_DISPATCH' => Yii::t('app', 'BMC MILK DISPATCH'), 'PLANT_MILK_RECEIPT' => Yii::t('app', 'PLANT MILK RECEIPT'), 'PLANT_MILK_DISPATCH' => Yii::t('app', 'PLANT MILK DISPATCH')],
            ],
            'no_of_installment' => [
                'name' => 'no_of_installment',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'],
            ],
            'dcs_manual_collection_range_request_type' => [
                'name' => 'request_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Shift Lock'), 1 => Yii::t('app', 'Manual Collection')],
            ],
            'app_type' => [
                'name' => 'app_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [1 => Yii::t('app', 'HO'), 2 => Yii::t('app', 'Flutter'), 3 => Yii::t('app', 'Manual Collection'), 4 => Yii::t('app', 'Driver App')],
            ],
            'source_of_milk' => [
                'name' => 'source_of_milk',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => 'Sample Milk', '1' => 'Flush Milk', '2' => 'Other'],
            ],
            'milking_status' => [
                'name' => 'milking_status',
                'prompt' => Yii::t('app', 'Select Milking Status'),
                'data' => [0 => Yii::t('app', 'Dry'), 1 => Yii::t('app', 'In Milk')],
            ],
            'pregnancy_status' => [
                'name' => 'pregnancy_status',
                'prompt' => Yii::t('app', 'Select Pregnancy Status'),
                'data' => [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')],
            ],
            'diagnosis_milking_status' => [
                'name' => 'milking_status',
                'prompt' => Yii::t('app', 'Select Milking Status'),
                'data' => [0 => Yii::t('app', 'None'), 1 => Yii::t('app', 'Milking'), 2 => Yii::t('app', 'Pregenent')],
            ],
            'type_of_dcs' => [
                'name' => 'type_of_dcs',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'VLCC'), 1 => Yii::t('app', 'DOCK Farmer')],
            ],
            'log_status' => [
                'name' => 'log_status',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'Pending'), 2 => Yii::t('app', 'Success'), 3 => Yii::t('app', 'Error')],
            ],
            'attendances_regularization_status' => [
                'name' => 'is_approved',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['0' => Yii::t('app', 'Pending'), '1' => Yii::t('app', 'Partial Approved'), '2' => Yii::t('app', 'Approved'), '3' => Yii::t('app', 'Reject')],
            ],
            'department_wise' => [
                'name' => 'department_wise',
                'prompt' => Yii::t('app', 'Department'),
                'data' => [0 => Yii::t('app', 'All Departments'), 1 => Yii::t('app', 'My Department Only')],
            ],
            'originating_type_options' => [
                'name' => 'originating_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => [0 => Yii::t('app', 'PORTAL'), 1 => Yii::t('app', 'PORTAL'), 23 => Yii::t('app', 'Android AMCS'), 24 => Yii::t('app', 'Android AMCS')],
            ],
            'p_bank_type' => [
                'name' => 'p_bank_type',
                'prompt' => Yii::t('app', 'Select'),
                'data' => ['HDFCNEFT' => Yii::t('app', 'HDFCNEFT'), 'HDFC' => Yii::t('app', 'HDFC')],
            ],
            'bipl_type' => [
                'name' => 'bipl_type',
                'prompt' => Yii::t('app', 'Select Bipl Type'),
                'data' => ['0' => Yii::t('app', 'Member'), '1' => Yii::t('app', 'DCS'), '2' => Yii::t('app', 'Rate'), '3' => Yii::t('app', 'Rate Applicability')],
            ],
            'sap_status' => [
                'name' => 'sap_status',
                'prompt' => Yii::t('app', 'Select Status'),
                'data' => ['generate' => Yii::t('app', 'Generate'), 'regenerate' => Yii::t('app', 'Regenerate')],
            ],
        ];
        return $records[$l];
    }

    public function getLabels($l) {
        $label = [
            'manufacture' => ['name' => 'manufacturer_code', 'fields' => 'id,manufacturer_name', 'prompt' => 'Select Manufacture', 'model' => 'TblManufacturer'],
            'bank' => ['name' => 'bank_code', 'fields' => 'bank_code,bank_name,local_name', 'prompt' => 'Select Bank', 'model' => 'TblBanks'],
            'branch' => ['name' => 'branch_code', 'fields' => 'branch_code,branch_name~ifsc,local_name', 'prompt' => 'Select Branch', 'model' => 'TblBranch', 'depend' => 'bank_code', 'checkValid'],
            'sub-center' => ['name' => 'sub_center_code', 'fields' => 'sub_center_code,sub_center_name,local_name', 'prompt' => 'Select Sub Center', 'model' => 'TblSubCenter', 'depend' => 'dcs_code'],
            'destination' => ['name' => 'destination_code', 'fields' => 'bmc_code,bmc_name', 'prompt' => 'Select Destination', 'model' => 'TblDcsBmc', 'depend' => 'union_code'],
            'bmc' => ['name' => 'bmc_code', 'fields' => 'bmc_code,bmc_name~ref_code', 'prompt' => 'Select BMC', 'model' => 'TblDcsBmc', 'depend' => 'union_code'],
            'dcs' => ['name' => 'dcs_code', 'fields' => 'dcs_code,dcs_name~ref_code,local_name', 'prompt' => Yii::t('app', 'Select Society'), 'model' => 'TblDcs', 'depend' => 'union_code', 'checkValid'],
            'plant' => ['name' => 'plant_code', 'fields' => 'plant_code,name~ref_code,local_name', 'prompt' => 'Select Plant', 'model' => 'TblPlant', 'depend' => 'union_code', 'checkValid'],
            'mcc' => ['name' => 'mcc_code', 'fields' => 'mcc_plant_code,name~ref_code,local_name', 'prompt' => 'Select MCC', 'model' => 'TblMccPlant', 'depend' => 'union_code', 'checkValid'],
            'member' => ['name' => 'member_code', 'fields' => 'member_code,member_name~ref_code,local_name', 'prompt' => Yii::t('app', 'Select Member'), 'model' => 'TblMember', 'depend' => 'dcs_code'],
            'route' => ['name' => 'route_code', 'fields' => 'route_code,route_name,local_name', 'prompt' => 'Select Route', 'model' => 'TblRoutes', 'depend' => 'bmc_code'],
            'routemapping' => ['name' => 'route_code', 'fields' => 'route_code,route_name~ref_code,local_name', 'prompt' => 'Select Route', 'model' => 'TblRouteMapping', 'depend' => 'union_code'],
            'land_unit' => ['name' => 'land_unit', 'fields' => 'land_unit_code,land_unit_name,local_name', 'prompt' => 'Select Data', 'model' => 'TblLandUnit'],
            'unit_code' => ['name' => 'unit_code', 'fields' => 'unit_code,unit_name,local_name', 'prompt' => 'Select Unit', 'model' => 'TblUnits', 'local_model' => 'TblUnitsLocal'],
            'state_code' => ['name' => 'state_code', 'fields' => 'state_code,state_name,local_name', 'prompt' => 'Select State', 'model' => 'TblStates', 'local_model' => 'TblStatesLocal'],
            'district_code' => ['name' => 'district_code', 'fields' => 'district_code,district_name,local_name', 'prompt' => Yii::t('app', 'Select District'), 'model' => 'TblDistricts', 'local_model' => 'TblDistrictsLocal', 'depend' => 'state_code'],
            'sub_district_code' => ['name' => 'sub_district_code', 'fields' => 'sub_district_code,sub_district_name,local_name', 'prompt' => Yii::t('app', 'Select Sub District'), 'model' => 'TblSubDistricts', 'local_model' => 'TblSubDistrictsLocal', 'depend' => 'district_code'],
            'village_code' => ['name' => 'village_code', 'fields' => 'village_code,village_name,local_name', 'prompt' => Yii::t('app', 'Select Village'), 'model' => 'TblVillages', 'local_model' => 'TblVillagesLocal', 'depend' => 'sub_district_code'],
            'block_code' => ['name' => 'block_code', 'fields' => 'block_code,block_name,local_name', 'prompt' => 'Select Block', 'model' => 'TblBlocks', 'depend' => 'sub_district_code'],
            //'dcs_village_code' => ['name' => 'village_code', 'fields' => 'village_code,village_name,local_name', 'prompt' => 'Select Village', 'model' => 'TblVillages', 'local_model' => 'TblVillagesLocal', 'depend' => 'sub_district_code'],
            'hamlet_code' => ['name' => 'hamlet_code', 'fields' => 'hamlet_code,hamlet_name,local_name', 'prompt' => Yii::t('app', 'Select Hamlet'), 'model' => 'TblHamlets', 'local_model' => 'TblHamletsLocal', 'depend' => 'village_code'],
            //'animal_type_code'=>['name'=>'animal_type_code','fields'=>'animal_type_code,animal_type_name','prompt'=>'Select Animal Type','model'=>'TblAnimalType'],
            'vehicle_type_code' => ['name' => 'vehicle_type_code', 'fields' => 'vehicle_type_code,vehicle_type_name,local_name', 'prompt' => 'Select Vehicle Type', 'model' => 'TblVehicleType'],
            'milk_quality_type_code' => ['name' => 'milk_quality_type_code', 'fields' => 'milk_quality_type_code,milk_quality_type_name,local_name', 'prompt' => 'Select Milk Quality Type', 'model' => 'TblMilkQualityType'],
            //'milk_type'=>['name'=>'milk_quality_type_code','fields'=>'animal_type_code,animal_type_name','prompt'=>'Select Type','model'=>'TblAnimalType'],
            'milk_type_code' => ['name' => 'milk_type_code', 'fields' => 'animal_type_code,animal_type_name,local_name', 'prompt' => 'Select Milk Type', 'model' => 'TblAnimalType'],
            'dcs_type_code' => ['name' => 'dcs_type_code', 'fields' => 'dcs_type_code,dcs_type_name,local_name', 'prompt' => Yii::t('app', 'Select Society Type'), 'model' => 'TblDcsTypes'],
            'rate_type_code' => ['name' => 'rate_type_code', 'fields' => 'code,rate_type', 'prompt' => 'Select Rate Type', 'model' => 'TblRateType'],
            'rate_gen_method_code' => ['name' => 'rate_gen_method_code', 'fields' => 'code,method', 'prompt' => 'Select Rate Method', 'model' => 'TblRateGenerateMethod'],
            'shift_applicability' => ['name' => 'shift_applicability', 'fields' => 'id,shift', 'prompt' => 'Select Shift', 'model' => 'TblShift'],
            'shift' => ['name' => 'shift', 'fields' => 'id,shift', 'prompt' => 'Select Shift', 'model' => 'TblShift'],
            'deduction_type' => ['name' => 'deduction_type', 'fields' => 'deduction_type,deduction_type', 'prompt' => 'Select Deduction Type', 'model' => 'TblRateDeductionType'],
            'ref_type' => ['name' => 'ref_type', 'fields' => 'reference_type,reference_type', 'prompt' => 'Select Reference Type', 'model' => 'TblRateReferenceType'],
            'quality_param' => ['name' => 'quality_param', 'fields' => 'code,type', 'prompt' => 'Select Quality Param', 'model' => 'TblMilkType'],
            'criteria_type_code' => ['name' => 'criteria_type_code', 'fields' => 'code,criteria_name', 'prompt' => 'Select Criteria', 'model' => 'TblHeadLoadCriteria'],
            'product_group_code' => ['name' => 'product_group_code', 'fields' => 'product_group_code,product_group_name', 'prompt' => 'Select Product Group', 'model' => 'TblProductGroup'],
            'product' => ['name' => 'product_code', 'fields' => 'product_code,product_name,local_name', 'prompt' => 'Select Product', 'model' => 'TblProduct', 'depend' => 'union_code', 'dependArray' => ['x_col3']],
            'member_classification_type_code' => ['name' => 'member_classification_type_code', 'fields' => 'member_classification_type_code,member_classification_type', 'prompt' => 'Select Member Classification Type', 'model' => 'TblMemberClassificationType'],
            'member-type' => ['name' => 'member_type_code', 'fields' => 'member_type_code,member_type_name', 'prompt' => Yii::t('app', 'Select Member Type'), 'model' => 'TblMemberTypes'],
            'blood-group' => ['name' => 'bloodgroup_code', 'fields' => 'blood_group_code,blood_group', 'prompt' => 'Select Blood Group', 'model' => 'TblBloodgroup'],
            'religion' => ['name' => 'religion_code', 'fields' => 'religion_code,religion', 'prompt' => 'Select Religion', 'model' => 'TblReligion'],
            'gender' => ['name' => 'gender_code', 'fields' => 'gender_code,gender', 'prompt' => 'Select Gender', 'model' => 'TblGender'],
            'qualification' => ['name' => 'qualification_code', 'fields' => 'qualification_code,qualification_name', 'prompt' => 'Select Qualification', 'model' => 'TblQualification'],
            'caste-category' => ['name' => 'caste_category_code', 'fields' => 'caste_category_code,caste_category_name,local_name', 'prompt' => Yii::t('app', 'Select Caste/Category'), 'model' => 'TblCasteCategory'],
            'capacity' => ['name' => 'capacity_code', 'fields' => 'capacity_code,value', 'prompt' => 'Select Capacity', 'model' => 'TblCapacity'],
            'bmc_type' => ['name' => 'bmc_type_code', 'fields' => 'bmc_type_code,bmc_type_name', 'prompt' => 'Select BMC Type', 'model' => 'TblBmcType'],
            'device_manufacturer' => ['name' => 'device_manufacturer_id', 'fields' => 'id,manufacturer', 'prompt' => 'Select Device Manufacturer', 'model' => 'TblDeviceManufacturer'],
            'organisation_type' => ['name' => 'organisation_type_code', 'fields' => 'organisation_type_code,organisation_type', 'prompt' => 'Select Organisation Type', 'model' => 'TblOrganisationType'],
            'scheme_type' => ['name' => 'scheme_type_code', 'fields' => 'scheme_type_code,scheme_type', 'prompt' => 'Select Scheme Type', 'model' => 'TblSchemeType'],
            'transporter' => ['name' => 'transporter_code', 'fields' => 'transporter_code,transporter_name~vendor_code,local_name', 'prompt' => 'Select Transporter', 'model' => 'TblTransporter', 'depend' => 'union_code'],
            'relation' => ['name' => 'nominee_relation', 'fields' => 'relationship_code,relationship', 'prompt' => 'Select Relationship', 'model' => 'TblRelationship'],
            'rule_id' => ['name' => 'rule_id', 'fields' => 'rule_id,process_name', 'prompt' => 'Select Rule', 'model' => 'TblEmailProcessMaster'],
            'fuel_type_code' => ['name' => 'fuel_type_code', 'fields' => 'fuel_type_code,fuel_type', 'prompt' => 'Select Fuel Type', 'model' => 'TblFuelTypeMaster'],
            'vehicle_code' => ['name' => 'vehicle_code', 'fields' => 'vehicle_code,vehicle_type_code', 'prompt' => 'Select Vehicle', 'model' => 'TblVehicleMaster'],
            'route_code' => ['name' => 'route_code', 'fields' => 'route_code,route_name,,ref_code', 'prompt' => 'Select Route', 'model' => 'TblRouteMapping'],
            'transporter_code' => ['name' => 'transporter_code', 'fields' => 'transporter_code,transporter_name', 'prompt' => 'Select Transporter', 'model' => 'TblTransporter'],
            'bmc_code' => ['name' => 'bmc_code', 'fields' => 'bmc_code,bmc_name', 'prompt' => 'Select BMC', 'model' => 'TblDcsBmc'],
            'bmc_codes' => ['name' => 'bmc_code', 'fields' => 'bmc_code,bmc_name,local_name', 'prompt' => 'Select BMC', 'model' => 'TblDcsBmc', 'depend' => 'union_code', 'false'],
            'billing_type_code' => ['name' => 'billing_type_code', 'fields' => 'billing_type_code,billing_type', 'prompt' => 'Select Billing Type', 'model' => 'TblBillingType'],
            'transporter_payment_head_code' => ['name' => 'transporter_payment_head_code', 'fields' => 'transporter_payment_head_code,transporter_payment_head', 'prompt' => 'Select Payment Head', 'model' => 'TblTransporterPaymentHead'],
            'village-code' => ['name' => 'village_code', 'fields' => 'village_code,village_name', 'prompt' => 'Select Village', 'model' => 'TblVillages'],
            'department' => ['name' => 'department', 'fields' => 'department_id,department,local_name', 'prompt' => 'Select Department', 'model' => 'TblDepartment'],
            'customer_type' => ['name' => 'customer_type', 'fields' => 'customer_type,customer_desc', 'prompt' => 'Select Type', 'model' => 'TblCustomerType', 'whereCondition' => ['is_organisation' => 0, 'union_code' => !empty(Yii::$app->session->get('Unions')) ? explode(',', Yii::$app->session->get('Unions')) : '']],
            'transfer_master_type' => ['name' => 'master_type', 'fields' => 'master_type,master_type_text', 'prompt' => Yii::t('app', 'Select Master Type'), 'model' => 'TblTransferType'],
            'app_type' => ['name' => 'app_type', 'fields' => 'operator_type,api_name', 'prompt' => 'Select Type', 'model' => 'TblApiMaster', 'whereCondition' => ['receiver_type' => 'APP_NOTIFICATION']],
            'general_formula_code' => ['name' => 'general_formula_code', 'fields' => 'general_formula_code,formula,', 'prompt' => 'Select Formula', 'model' => 'TblGeneralFormula', 'depend' => 'union_code'],
            'default_bill_head_code' => ['name' => 'default_bill_head_code', 'fields' => 'default_bill_head_code,default_bill_head_name', 'prompt' => 'Select Default Bill head Type', 'model' => 'TblBillHeadDefault'],
            'reject_reason' => ['name' => 'rejection_reason_code', 'fields' => 'rejection_reason_code,rejection_reason', 'prompt' => Yii::t('app', 'Select Reject Reason'), 'model' => 'TblRejectionReason'],
            'transport_vehicle' => ['name' => 'vehicle_code', 'fields' => 'vehicle_code,parsing_no,', 'prompt' => Yii::t('app', 'Select Vehicle'), 'model' => 'TblVehicleMaster', 'depend' => 'transporter_code'],
            'staff_member_code' => ['name' => 'staff_member_code', 'fields' => 'staff_member_code,staff_member_name,', 'prompt' => Yii::t('app', 'Select Staff Member'), 'model' => 'TblStaffMember', 'depend' => 'union_code'],
            'designation_code' => ['name' => 'designation_code', 'fields' => 'designation_code,designation_name', 'prompt' => Yii::t('app', 'Select Designation'), 'model' => 'TblDesignation'],
            'tax_group_code' => ['name' => 'tax_group_code', 'fields' => 'tax_group_code,tax_group_name', 'prompt' => Yii::t('app', 'Select Tax Group'), 'model' => 'TblTaxGroup'],
            'tax' => ['name' => 'tax_code', 'fields' => 'tax_code,tax_name', 'prompt' => Yii::t('app', 'Select Tax Setting Name'), 'model' => 'TblTax'],
            'tax_code' => ['name' => 'tax_code', 'fields' => 'tax_code,tax_name', 'prompt' => Yii::t('app', 'Select Tax'), 'model' => 'TblTax'],
            'union_vehicle' => ['name' => 'vehicle_code', 'fields' => 'vehicle_code,parsing_no,', 'prompt' => Yii::t('app', 'Select Vehicle'), 'model' => 'TblVehicleMaster', 'depend' => 'union_code'],
            'dispatch_destination' => ['name' => 'customer_type', 'fields' => 'customer_type,customer_desc', 'prompt' => 'Select Type', 'model' => 'TblCustomerType', 'whereCondition' => ['is_bmc_dispatch' => 1, 'union_code' => !empty(Yii::$app->session->get('Unions')) ? explode(',', Yii::$app->session->get('Unions')) : '']],
            'bmc_silos' => ['name' => 'bmc_silos_info_code', 'fields' => 'bmc_silos_info_code,silo_no,', 'prompt' => Yii::t('app', 'Select Silo'), 'model' => 'TblBmcSilosInfo', 'depend' => 'module_code', 'dependArray' => ['module_name']],
            'qty_diff_type' => ['name' => 'qty_diff_type_code', 'fields' => 'qty_diff_type_code,qty_diff_type_name', 'prompt' => Yii::t('app', 'Select'), 'model' => 'TblQtyDiffType'],
            'union_trip' => ['name' => 'trip_code', 'fields' => 'trip_code,trip_code,', 'prompt' => Yii::t('app', 'Select Trip'), 'model' => 'TblVehicleTrip', 'depend' => 'union_code', 'dependArray' => ['trip_status']],
            'trip_challan' => ['name' => 'challan_no', 'fields' => 'challan_no,challan_no,', 'prompt' => Yii::t('app', 'Select Challan'), 'model' => 'TblBmcMilkDispatch', 'depend' => 'trip_code'],
            'bmc-dcs' => ['name' => 'dcs_code', 'fields' => 'dcs_code,dcs_name,local_name', 'prompt' => Yii::t('app', 'Select Society'), 'model' => 'TblDcs', 'depend' => 'bmc_code', 'checkValid'],
            'cmpl_product_code' => ['name' => 'cmpl_product_code', 'fields' => 'cmpl_product_code,cmpl_product_name', 'prompt' => 'Select Asset Type', 'model' => 'TblComplainProduct'],
            'asset_group_code' => ['name' => 'asset_group_code', 'fields' => 'asset_group_code,asset_group_name', 'prompt' => 'Select Asset Group', 'model' => 'TblAssetGroup'],
            'store_location_type' => ['name' => 'store_location_type', 'fields' => 'slt_code,slt_name', 'prompt' => 'Select Type', 'model' => 'TblStoreLocationType'],
            'slc_type' => ['name' => 'store_location_code', 'fields' => 'store_location_code,store_location_name,local_name', 'prompt' => Yii::t('app', 'Store Location'), 'model' => 'TblStoreLocation', 'depend' => 'store_location_type'],
            'asset_set' => ['name' => 'sap_code', 'fields' => 'asset_set_code,sap_code,sloc_code', 'prompt' => Yii::t('app', 'SAP Code'), 'model' => 'TblAssetSet', 'depend' => 'store_location_code'],
            'union_asset' => ['name' => 'asset_code', 'fields' => 'asset_code,asset_name,local_name', 'prompt' => Yii::t('app', 'Select Asset'), 'model' => 'TblAssetMaster', 'depend' => 'union_code'],
            'asset_code' => ['name' => 'asset_code', 'fields' => 'asset_code,asset_name,local_name', 'prompt' => Yii::t('app', 'Select Asset'), 'model' => 'TblAssetMaster', 'depend' => 'asset_group_code'],
            'customer_code' => ['name' => 'customer_code', 'fields' => 'customer_code,customer_name,local_name', 'prompt' => Yii::t('app', 'Select Customer'), 'model' => 'TblCustomerMaster', 'depend' => 'customer_type'],
            'store_location_code' => ['name' => 'store_location_code', 'fields' => 'store_location_code,store_location_name,local_name', 'prompt' => Yii::t('app', 'Store Location'), 'model' => 'TblStoreLocation', 'depend' => 'union_code'],
            'unit' => ['name' => 'unit_code', 'fields' => 'unit_code,unit_name,local_name', 'prompt' => 'Select Unit', 'model' => 'TblUnits', 'depend' => 'union_code'],
            'tax_group' => ['name' => 'tax_group_code', 'fields' => 'tax_group_code,tax_group_name,', 'prompt' => Yii::t('app', 'Select Tax Group'), 'model' => 'TblTaxGroup', 'depend' => 'union_code'],
            'report_code' => ['name' => 'report_code', 'fields' => 'report_code,report_name', 'prompt' => 'Select Report', 'model' => 'TblReportList'],
            'depend_tax_code' => ['name' => 'tax_code', 'fields' => 'tax_code,tax_name,', 'prompt' => Yii::t('app', 'Select Tax'), 'model' => 'TblTax', 'depend' => 'union_code'],
            'slab_bill_head' => ['name' => 'bill_head_code', 'fields' => 'bill_head_code,bill_head_name,default_bill_head_code', 'prompt' => 'Select Head', 'model' => 'TblBillHead', 'whereCondition' => ['has_slab' => 1]],
            'role_code' => ['name' => 'role_code', 'fields' => 'role_code,description', 'prompt' => 'Select Role', 'model' => 'TblRole'],
            'penalty_type' => ['name' => 'penalty_type', 'fields' => 'penalty_type_code,penalty_type,', 'prompt' => Yii::t('app', 'Select Penalty Type'), 'model' => 'TblCollectionPenaltyType', 'depend' => 'union_code'],
            'product_group' => ['name' => 'product_group_code', 'fields' => 'product_group_code,product_group_name,local_name', 'prompt' => 'Select Product Group', 'model' => 'TblProductGroup', 'depend' => 'union_code'],
            'device_code' => ['name' => 'weight_device_code', 'fields' => 'interfacing_device_code,device_name,', 'prompt' => Yii::t('app', 'Select Device'), 'model' => 'TblInterfacingDevice', 'depend' => 'union_code', 'dependArray' => ['device_type']],
//            'union_trip' => ['name' => 'trip_code', 'fields' => 'trip_code,trip_code,', 'prompt' => Yii::t('app', 'Select Trip'), 'model' => 'TblVehicleTrip', 'depend' => 'union_code', 'dependArray' => ['trip_status']],
            'vendor' => ['name' => 'vendor_master_code', 'fields' => 'vendor_master_code,vendor_name,vendor_code', 'prompt' => 'Select Vendor', 'model' => 'TblVendorMaster', 'depend' => 'union_code'],
            'channel' => ['name' => 'channel_master_code', 'fields' => 'channel_master_code,channel_desc', 'prompt' => 'Select Channel', 'model' => 'TblChannelMaster'],
            'mcc_bill_head' => ['name' => 'mcc_bill_head_code', 'fields' => 'mcc_bill_head_code,bill_head_name', 'prompt' => 'Select Bill head ', 'model' => 'TblMccBillHead', 'whereCondition' => ['has_slab' => 1]],
            'mcc_default_bill_head_code' => ['name' => 'default_bill_head_code', 'fields' => 'default_bill_head_code,default_bill_head_name', 'prompt' => 'Select Default Bill head Type', 'model' => 'TblMccBillHeadDefault'],
            'mcc_general_formula_code' => ['name' => 'general_formula_code', 'fields' => 'general_formula_code,formula,', 'prompt' => 'Select Formula', 'model' => 'TblMccGeneralFormula', 'depend' => 'union_code'],
            'rejection_responsibility' => ['name' => 'rejection_responsibility_code', 'fields' => 'rejection_responsibility_code,responsibility_name', 'prompt' => Yii::t('app', 'Select Responsibility'), 'model' => 'TblRejectionResponsibility'],
            'application_id' => ['name' => 'application_id', 'fields' => 'application_id,application_id,', 'prompt' => Yii::t('app', 'Select Application'), 'model' => 'TblSchemeApplication', 'depend' => 'scheme_id', 'dependArray' => ['application_status']],
            'scheme_id' => ['name' => 'scheme_id', 'fields' => 'scheme_id,scheme_name,', 'prompt' => Yii::t('app', 'Select Scheme'), 'model' => 'TblSchemeMaster', 'depend' => 'union_code'],
            'trip_code' => ['name' => 'trip_code', 'fields' => 'trip_code,trip_code', 'prompt' => 'Select Trip', 'model' => 'TblMilkVehicleEntry'],
            'grn_no' => ['name' => 'grn_no', 'fields' => 'grn_no,grn_no', 'prompt' => 'Select GRN No', 'model' => 'TblMilkVehicleEntry'],
            'user' => ['name' => 'id', 'fields' => 'id,name', 'prompt' => Yii::t('app', 'Select Parent'), 'model' => 'User'],
            'ref_no' => ['name' => 'ref_no', 'fields' => 'document_no,document_no,', 'prompt' => Yii::t('app', 'Select Ref No.'), 'model' => 'TblPlantDispatch', 'depend' => 'plant_code', 'dependArray' => ['bmc_code', 'status']],
            'approval_process' => ['name' => 'process_name', 'fields' => 'process_name,process_desc,', 'prompt' => Yii::t('app', 'Select Process'), 'model' => 'TblApprovalStagesProcess', 'depend' => 'union_code'],
            'indent-product' => ['name' => 'product_code', 'fields' => 'product_code,product_name,local_name', 'prompt' => 'Select Product', 'model' => 'TblProduct', 'depend' => 'union_code', 'dependArray' => ['is_indent']],
            'spare_code' => ['name' => 'spare_code', 'fields' => 'asset_code,asset_name', 'prompt' => 'Select Head', 'model' => 'TblAssetMaster', 'whereCondition' => ['is_spare' => 1]],
            'spare_code_detail_bom' => ['name' => 'spare_code', 'fields' => 'asset_code,asset_name', 'prompt' => 'Select Head', 'model' => 'TblAssetMaster'],
            'asset_bom_code' => ['name' => 'spare_code', 'fields' => 'spare_code,spare_code,', 'prompt' => Yii::t('app', 'Select Spare'), 'model' => 'TblAssetBom', 'depend' => 'asset_code'],
            'complain_escalation_name' => ['name' => 'complain_escalation_code', 'fields' => 'complain_escalation_code,escalation_name', 'prompt' => 'Select Complain  Escalation Name', 'model' => 'TblComplainEscalation'],
            'complain_type' => ['name' => 'complain_type_code', 'fields' => 'complain_type_code,complain_type', 'prompt' => 'Select Complain Type', 'model' => 'TblComplainType'],
            'contact_detail_complain_user' => ['name' => 'user_code', 'fields' => 'detail_code,firstname,mobile_no', 'prompt' => 'Select', 'model' => 'TblContactDetails'],
            'task_type' => ['name' => 'task_type_code', 'fields' => 'task_type_code,task_type,', 'prompt' => Yii::t('app', 'Select Task Type'), 'model' => 'TblTaskType', 'depend' => 'union_code'],
            'form_type' => ['name' => 'form_type_code', 'fields' => 'form_type_code,form_name,', 'prompt' => Yii::t('app', 'Select Form'), 'model' => 'TblFormType', 'depend' => 'task_type_code'],
            'task_form_type' => ['name' => 'task_type_code', 'fields' => 'task_type_code,task_type,', 'prompt' => Yii::t('app', 'Select Task Type'), 'model' => 'TblTaskType', 'depend' => 'union_code', 'dependArray' => ['has_form']],
            'hold_reason' => ['name' => 'hold_reason', 'fields' => 'hold_reason,description,', 'prompt' => Yii::t('app', 'Select Hold Reason'), 'model' => 'TblPaymentHoldReason'],
            'rate_class' => ['name' => 'rate_class', 'fields' => 'rate_class_code,rate_class', 'prompt' => 'Select Rate Class', 'model' => 'TblRateClass'],
            'region_code' => ['name' => 'region_code', 'fields' => 'region_code,region_name,local_name', 'prompt' => 'Select Region', 'model' => 'TblRegion', 'depend' => 'state_code'],
            'area_code' => ['name' => 'area_code', 'fields' => 'area_code,area_name,local_name', 'prompt' => 'Select Area', 'model' => 'TblArea', 'depend' => 'region_code'],
            'payment_head_code' => ['name' => 'payment_head_code', 'fields' => 'payment_head_code,payment_head_name', 'prompt' => 'Payment Head Name', 'model' => 'TblPaymentHead'],
            'party_master_code' => ['name' => 'party_name', 'fields' => 'party_master_code,party_name', 'prompt' => 'Applicable Name', 'model' => 'TblPartyMaster'],
            'party_master' => ['name' => 'party_master_code', 'fields' => 'party_master_code,party_name,owner_name', 'prompt' => 'Select Party', 'model' => 'TblPartyMaster'],
            'user_code' => ['name' => 'user_code', 'fields' => 'user_code,name', 'prompt' => Yii::t('app', 'Select User'), 'model' => 'User'],
            'login_user_code' => ['name' => 'login_user_code', 'fields' => 'app_login_id,user_name', 'prompt' => 'Select User Name', 'model' => 'TblEiplAppLogin', 'whereCondition' => ['master_type' => ['area', 'bmc', 'mccPlant', 'plant', 'region', 'routeMapping', 'union', 'user']]],
            'region' => ['name' => 'region_code', 'fields' => 'region_code,region_name,local_name', 'prompt' => 'Select Region', 'model' => 'TblRegion', 'depend' => 'union_code'],
            'relation_code' => ['name' => 'relationship_code', 'fields' => 'relationship_code,relationship', 'prompt' => 'Select Relationship', 'model' => 'TblRelationship'],
            'product_depend_group' => ['name' => 'product_code', 'fields' => 'product_code,product_name,local_name', 'prompt' => 'Select Product', 'model' => 'TblProduct', 'depend' => 'product_group_code'],
            'rule_code' => ['name' => 'rule_code', 'fields' => 'rule_code,rule_name,', 'prompt' => Yii::t('app', 'Select Rule'), 'model' => 'TblAlertRuleMaster', 'depend' => 'union_code', 'dependArray' => ['is_active']],
            'documnet_master_type' => ['name' => 'master_type_code', 'fields' => 'master_type_code,master_type_name', 'prompt' => 'Select Master Type', 'model' => 'TblDocumentMasterType'],
            'latlong_user' => ['name' => 'id', 'fields' => 'id,name,user_code', 'prompt' => Yii::t('app', 'Select Parent'), 'model' => 'User'],
            'dispatch_center_type' => ['name' => 'dispatch_center_type_code', 'fields' => 'dispatch_center_type_code,dispatch_center_type', 'prompt' => 'Select Dispatch Center Type', 'model' => 'TblDispatchCenterType', 'joinwith' => ['userDispatchCenterMapping'], 'rlsWhereCondition' => ['tbl_user_dispatch_center_mapping.user_code' => Yii::$app->session->get('UserCode')], 'applyRls' => !empty(Yii::$app->user->identity->user_type_id) ? !in_array(Yii::$app->user->identity->user_type_id, [2]) : ''],
            'dispatch_center' => ['name' => 'dispatch_center_code', 'fields' => 'dispatch_center_code,dispatch_center_name', 'prompt' => 'Select Dispatch Center', 'model' => 'TblDispatchCenter', 'joinwith' => ['dispatchCenterTypeCode', 'userDispatchCenterMapping'], 'concatfield' => 'tbl_dispatch_center_type.dispatch_center_type', 'rlsWhereCondition' => ['tbl_user_dispatch_center_mapping.user_code' => Yii::$app->session->get('UserCode')], 'applyRls' => !empty(Yii::$app->user->identity->user_type_id) ? !in_array(Yii::$app->user->identity->user_type_id, [2]) : ''],
            'insurance_master_list' => ['name' => 'insurance_master_code', 'fields' => 'insurance_master_code,insurance_description', 'prompt' => 'Select Insurance', 'model' => 'TblInsuranceMaster'],
            'forceSyncTables' => ['name' => 'table_name', 'fields' => 'table_name,table_desc', 'prompt' => 'Select Process', 'model' => 'TblForceSyncTableList'],
            'chamber_no' => ['name' => 'chamber_no', 'fields' => 'milk_vehicle_entry_qlty_code,chamber_no,', 'prompt' => Yii::t('app', 'Select Compartment'), 'model' => 'TblMilkVehicleEntryQlty', 'depend' => 'trip_code', 'dependArray' => ['status']],
            'dock_no' => ['name' => 'dock_no', 'fields' => 'dock_no,dock_name,dock_no', 'prompt' => Yii::t('app', 'Select Dock No'), 'model' => 'TblPlantDockMapping', 'depend' => 'plant_code'],
            'vehicle_transpoter' => ['name' => 'vehicle_code', 'fields' => 'vehicle_code,parsing_no,', 'prompt' => Yii::t('app', 'Select Vehicle'), 'model' => 'TblVehicleMaster', 'whereCondition' => ['vehicle_use_type' => [1, 2], 'union_code' => !empty(Yii::$app->session->get('Unions')) ? explode(',', Yii::$app->session->get('Unions')) : '']],
            'general_party_master' => ['name' => 'general_party_master_code', 'fields' => 'general_party_master_code,party_name~party_type,ref_code', 'prompt' => 'Select Party', 'model' => 'TblGeneralPartyMaster', 'depend' => 'bmc_code'],
            'bmc_chiller_info' => ['name' => 'chiller_info_code', 'fields' => 'chiller_info_code,owner_name,sap_vendor_code', 'prompt' => Yii::t('app', 'Select BMC Chiller Info'), 'model' => 'TblBmcChillerInfo', 'depend' => 'bmc_code'],
            'vehicle_trip' => ['name' => 'trip_code', 'fields' => 'trip_code,trip_code,', 'prompt' => Yii::t('app', 'Select Trip'), 'model' => 'TblVehicleTrip', 'depend' => 'vehicle_code'],
            'animal_type_additional' => ['name' => 'animal_type_additional_code', 'fields' => 'animal_type_additional_code,additional_animal_type_name,', 'prompt' => Yii::t('app', 'Select Additional Animal Type'), 'model' => 'TblAnimalTypeAdditional'],
            'asset_type' => ['name' => 'asset_type_code', 'fields' => 'asset_type_code,asset_type_name,', 'prompt' => Yii::t('app', 'Select Asset Type'), 'model' => 'TblAssetType'],
            'caseType' => ['name' => 'case_type_id', 'fields' => 'case_type_id,case_type_name,', 'prompt' => Yii::t('app', 'Select Case Type'), 'model' => 'TblCaseType', 'depend' => 'union_code'],
            'medicine_master' => ['name' => 'medicine_id', 'fields' => 'medicine_id,medicine_name,', 'prompt' => Yii::t('app', 'Select Medicine'), 'model' => 'TblMedicineMaster', 'depend' => 'union_code'],
            'committee_type_code' => ['name' => 'committee_type_code', 'fields' => 'committee_type_code,committee_type_name', 'prompt' => 'Select Type', 'model' => 'TblCommitteeType'],
            'Ledger_type' => ['name' => 'ledger_type_code', 'fields' => 'ledger_type_code,ledger_type_name,local_name', 'prompt' => 'Select Ledger Type', 'model' => 'TblLedgerTypes'],
            'Ledger_groups' => ['name' => 'ledger_group_code', 'fields' => 'ledger_group_code,ledger_group_name,local_name', 'prompt' => 'Select Ledger Group', 'model' => 'TblLedgerGroups'],
            'Ledgers' => ['name' => 'ledger_code', 'fields' => 'ledger_code,ledger_name,tbl_ledgers.local_name', 'prompt' => 'Select Ledger', 'model' => 'TblLedgers', 'joinwith' => ['voucherTypesCode'], 'whereCondition' => ['tbl_voucher_types.is_active' => 1, 'tbl_voucher_types.voucher_type' => 1]],
            'ledger_mapping' => ['name' => 'ledger_code', 'fields' => 'ledger_code,ledger_name,tbl_ledgers.local_name', 'prompt' => 'Select Ledger', 'model' => 'TblLedgers'],
        ];
        return $label[$l];
    }

    public function sp_union_dcs($flag, $model, $form, $depends, $class = '', $label = false, $name = '', $readonly = false) {
        $check_list = '';
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $check_list = explode(',', Yii::$app->session->get('Dcs'));
            $check_list = implode('-', $check_list);
        }
        return $this->sp_depend_dropdown('dcs', $model, $form, $depends, $class, $label, $name, $readonly, 1, $check_list);
    }

    public function sp_depend_dropdown($flag, $model, $form, $depends, $class = '', $label = false, $name = '', $readonly = false, $check = 0, $checkList = [], $searchable = true) {
        $class = $readonly ? 'depend-control' : '';
        $data = $this->getLabels($flag);
        $fields = explode(',', $data['fields']);
        $checkValid = in_array('checkValid', $data);
        $field_value = isset($model->{$fields[0]}) ? $model->{$fields[0]} : 0;
        $control_name = ($name == '') ? $data['name'] : $name;
        $dropDownType = DepDrop::TYPE_DEFAULT;
        if (isset($searchable) && $searchable) {
            $dropDownType = DepDrop::TYPE_SELECT2;
        }
        echo $form->field($model, $control_name)
                ->widget(DepDrop::classname(), [
                    'type' => $dropDownType,
                    'data' => [$model->{$control_name} => $model->{$control_name}],
                    'name' => $control_name,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'pluginOptions' => [
                        'depends' => [$depends],
                        'placeholder' => $data['prompt'],
                        'url' => Url::to(['/site/sp-get-data']),
                        'allParam' => [$data['model'], $data['depend'], $field_value, $data['fields'], $check, $checkList, $checkValid],
                        'initialize' => true,
                    ],
                    'options' => [
                        'readonly' => $readonly,
                        'class' => 'form-control ' . $class
                    ]
                ])->label($label);
    }

    public function dropdownfilter($flag, $model, $name = '', $prompt = '', $class = 'form-control') {
        $data = $this->getLabels($flag);
        $control_name = ($name == '') ? $data['name'] : $name;
        $records = $this->withoutLocal($data, $model);
        return Html::activeDropDownList($model, $control_name, $records, ['class' => $class, 'prompt' => empty($prompt) ? $data['prompt'] : $prompt]);
    }

    public function getTableData($flag) {
        $data = $this->getLabels($flag);
        $fields = explode(',', $data['fields']);
        $model_name = Yii::$app->path->define($data['model']);
        $model = new $model_name();
        $select_fields[] = $fields[0];
        $select_fields[] = $fields[1];
        if (!empty($fields[2])) {
            array_push($select_fields, $fields[2]);
        }
        return $model->find()->select($select_fields)->where(['is_active' => 1])->orderBy($model->tablename() . '.' . $fields[1])->asArray()->all();
    }

    private function depend_dropdown_multiple($flag, $model, $form, $depends, $class, $label, $name, $check, $checkList) {
        $id = strtolower((new ReflectionClass($model))->getShortName() . '-' . $name);
        $depends = explode(',', $depends);

        $data = $this->getLabels($flag);
        $fields = explode(',', $data['fields']);
        $checkValid = in_array('checkValid', $data);
        $field_value = isset($model->{$fields[0]}) ? $model->{$fields[0]} : 0;
        $control_name = ($name == '') ? $data['name'] : $name;
        $display_code = isset($data['display_code']) ? $data['display_code'] : FALSE;
        $selfId = strtolower((new ReflectionClass($model))->getShortName() . '-' . $control_name);
        echo $form->field($model, $control_name, ['options' => ['class' => $class]])->widget(DepDropComp::classname(), [
            'type' => DepDropComp::TYPE_MULTISELECT,
            'options' => [
                'multiple' => true,
            ],
            'model' => $model,
            'attribute' => $control_name,
            'data' => !empty($model->{$control_name}) ? array_values($model->{$control_name}) : [''],
            'value' => !empty($model->{$control_name}) ? array_values($model->{$control_name}) : [0],
            'multiSelectOptions' => [
                'id' => $id,
                'clientOptions' =>
                    [
                    'includeSelectAllOption' => true,
                    'numberDisplayed' => 0,
                    'disableIfEmpty' => true,
                    'buttonClass' => 'form-control text-left',
                    'buttonContainer' => '<div class="form-group"/>',
                    'buttonWidth' => '100%'
                ],
            ],
            'pluginOptions' => [
                'depends' => $depends,
                'placeholder' => false,
                'url' => Url::to(['/site/get-data']),
                'allParam' => [$data['model'], $data['depend'], $field_value, $data['fields'], $check, $checkList, $checkValid, $display_code],
                'initialize' => true,
                'ajaxSettings' => [
                    'beforeSend' => new \yii\web\JsExpression("
                        function(jqXHR, settings) {
                            return handleDepdropBeforeSend({
                                depends: " . json_encode($depends) . ",
                                selfId: '{$selfId}'
                            });
                        }
                    "),
                ],
            ]
        ])->label(Yii::t('app', $label));
    }

    public function org_type_rate($model, $form, $depends, $name = 'p_purchase_rate_code', $islable = false, $multiple = false, $extra_param = '', $readonly = false) {
        $this->setClass($form, $name); ///organisation/tbl-dcs/dcs-list
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsoperation/tbl-purchase-rate/get-org-rate', Yii::t('app', 'Select'), $multiple, $extra_param, $readonly);
    }

    public function customerType($model, $form, $depends, $name = 'customer_type', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-customer-master/get-customer-type', Yii::t('app', 'Select Customer Type'), $multiple, 'where', $readonly);
    }

    public function dispatchCenterType($model, $form, $depends, $name = 'dispatch_center_code', $islable = false, $multiple = false, $readonly = false, $extra_param = '', $id = '') {
        $this->setClass($form, $name);
        if ($multiple) {
            $this->select2Dropdown($model, $form, $depends, $name, $islable, '/product/tbl-dispatch-center-type/get-dispatch-center', Yii::t('app', 'Select Dispatch Center'), $multiple, $extra_param, $readonly, $id);
        } else {
            $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-dispatch-center-type/get-dispatch-center', Yii::t('app', 'Select Dispatch Center'), $multiple, 'where', $readonly);
        }
    }

    public function sp_dropdown($flag, $model, $form, $class = 'form-group padding-right-5 col-sm-2', $label = false, $sp_name, $sp_param) {
        $records = \Yii::$app->general->getSpDropData($sp_name, $sp_param);
        $value = ArrayHelper::map($records, 'id', function($records) {
                    return !empty($records['name']) ? $records['name'] : '';
                });
        echo $form->field($model, $flag)->widget(Select2::classname(), [
            'data' => $value, 'pluginOptions' => ['allowClear' => true], 'options' => ['placeholder' => 'Select ' . $label]]
        )->label($label);

//        return $form->field($model, $flag)->dropDownList($value, ['prompt' => 'Select ' . $label])->label($label);
    }

    public function sp_dep_dropdown($model, $form, $depends, $name = '', $islable = false, $session) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dynamicreport/default/get-sp-data-drop-list', 'Select ' . $islable, FALSE, '', FALSE, '', TRUE, $session);
    }

    public function poured_bmc($model, $form, $depends, $name = 'bmc_code', $islable = false, $multiple = false, $id = '', $extra_param = '', $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/organisation/tbl-dcs-bmc/poured-bmc-list', Yii::t('app', 'Select Poured BMC'), $multiple, $extra_param, $readonly, $id);
    }

    public function PaymentCycleUnion($model, $form, $depends, $name = 'payment_cycle_code', $islable = false, $multiple = false, $readonly = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/payment/tbl-payment-cycle/union-payment-cycle-list', Yii::t('app', 'Select Payment Cycle'), $multiple, 'where', $readonly);
    }

    private function select2Dropdown($model, $form, $depends, $name, $islable = false, $url = '', $placeholder = '', $multiple = false, $extraParam = '', $readonly = false, $id = '', $searchable = true, $autoClose = true, $async = true, $listView = false) {
        $class = $readonly ? 'depend-control' : '';
        $depends = explode(',', $depends);

        $options = [];
        $options['readonly'] = $readonly;
        $options['class'] = 'form-control ' . $class;
        if (!empty($id)) {
            $options['id'] = $id;
        }
        if ($listView) {
            $options['multiple'] = true;
            $options['style'] = 'height: 150px;';
        }
        if ($multiple)
            $placeholder = FALSE;
        $dropDownType = DepDrop::TYPE_DEFAULT;
        if (isset($searchable) && $searchable) {
            $dropDownType = DepDrop::TYPE_SELECT2;
        }
        // $name = ($name == '') ? $data['name'] : $name;
        $data = [];
        if (is_array($model->{$name})) {
            $data = array_combine(array_values($model->{$name}), array_values($model->{$name}));
        } else {
            $data = [$model->{$name} => $model->{$name}];
        }

        $allParam = is_array($extraParam) ? $extraParam : ["'" . $extraParam . "'"];
        $selfId = strtolower((new ReflectionClass($model))->getShortName() . '-' . $name);
        echo $form->field($model, $name)
                ->widget(DepDrop::classname(), [
                    'type' => $dropDownType,
                    'data' => $data,
                    'name' => $name,
                    'select2Options' => ['options' => ['placeholder' => $placeholder], 'pluginOptions' => ['allowClear' => true, 'multiple' => $multiple, 'closeOnSelect' => $autoClose]],
                    'pluginOptions' => [
                        'depends' => $depends,
                        'placeholder' => $placeholder,
                        'url' => Url::to([$url]),
                        'allParam' => $allParam,
                        'initialize' => true,
                        'allowClear' => true,
                        'ajaxSettings' => [
                            'async' => $async,
                            'beforeSend' => new \yii\web\JsExpression("
                                function(jqXHR, settings) {
                                    return handleDepdropBeforeSend({
                                        depends: " . json_encode($depends) . ",
                                        selfId: '{$selfId}'
                                    });
                                }
                            "),
                        ],
                    ],
                    'options' => $options
                ])->label($islable);

        $selected = Json::encode($data);
        if (!empty($selected)) {
            $id_dropdown = strtolower((new ReflectionClass($model))->getShortName()) . '-' . strtolower($name);
            $depends_js = json_encode($depends);
            $script = "$(document).ready(function() {
                    var modelname = '" . strtolower((new ReflectionClass($model))->getShortName()) . "';
                    var fieldName = '" . strtolower($name) . "';
                    var selected_val = '" . $selected . "';
                    var selected_val_json = $.parseJSON(selected_val);
                    var array_val = [];
                    var dropdown = $('#' + modelname + '-' + fieldName);
                    var multiple = " . json_encode($multiple) . ";
                    var listView = " . (!empty($listView) ? 'true' : 'false') . ";

                    dropdown.on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                        if (selected_val_json) {                            
                            var array_val = [];
                            $.each(selected_val_json, function(index, value) {
                                if(value != ''){
                                    $('#'+modelname+'-'+fieldName).find('option[value='+value+']').attr('selected', 'selected');
                                    array_val.push(value);
                                }
                            });
                                dropdown.val(array_val).trigger('change');
                            }
                        if (multiple) {
                            dropdown.find('option[value=\"all\"][value=\'\']').remove();
                            if (dropdown.find('option').length > 0 && !listView) {
                                var selectOption = new Option('Select all', 'all', false, false);
                                dropdown.prepend(selectOption);
                            }
                        } 
                    });

                    if (multiple) {
                        dropdown.on('select2:select', function (e) {
                            if (e.params && e.params.data && e.params.data.id === 'all') {
                                var select = $(this);
                                select.find('option:not([value=\"all\"])').prop('selected', true);
                                select.find('option[value=\"all\"]').prop('selected', false).trigger('change');
                            }
                        });
                        dropdown.on('select2:unselect', function(e) {
                            if (e.params && e.params.data && e.params.data.id !== 'all') {
                            $(this).find('option[value=\"all\"]').prop('selected', false).trigger('select2:select');
                            }
                        });
                    }
                    if (listView) {
                        $.each(" . $depends_js . ", function(i, parentId) {
                            $('#' + parentId).on('change', function() {
                                var val = $(this).val();
                                if (!val || val.length === 0) {
                                    dropdown.val(null).trigger('change');
                                    dropdown.find('option').remove();
                                }
                            });
                        });
                    }
                });";
            Yii::$app->view->registerJs($script, View::POS_END, $id_dropdown);
        }
    }

    public function DpuRateChart($model, $form, $depends, $name = 'rate_chart_member', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/dcsoperation/tbl-purchase-rate/dpu-chart-list', Yii::t('app', 'Select Rate Chart'), $multiple);
    }

    public function productBatch($model, $form, $depends, $name = 'sap_batch_no', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-product-stock/product-batch-list', Yii::t('app', 'Select Batch'), $multiple);
    }

    public function productBatchLstSixMonth($model, $form, $depends, $name = 'sap_batch_no', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-product-stock/product-batch-list-last-six-month', Yii::t('app', 'Select Batch'), $multiple);
    }

    public function productBatchNo($model, $form, $depends, $name = 'sap_batch_no', $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/product/tbl-plant-dispatch/plant-batch-list', Yii::t('app', 'Select Batch'), $multiple);
    }

    public function medicineBatch($model, $form, $depends, $name, $islable = false, $multiple = false) {
        $this->setClass($form, $name);
        $this->dependedDropdown($model, $form, $depends, $name, $islable, '/veterinary/tbl-medicine-stock/medicine-batch-list', Yii::t('app', 'Select Batch'), $multiple);
    }

}
