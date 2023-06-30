<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use app\modules\installation\models\TblAndroidInstallationDetails;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => 'tblMccPlant.name', 'visible' => true, 'filter' => true],
        ['attribute' => 'bmc_code', 'value' => 'bmc_code'],
        ['attribute' => 'bmc_code_ex'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'bmc_name', 'value' => 'bmc_name'],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'bmc_type_code', 'value' => 'tblBmcType.bmc_type_name'],
        ['attribute' => 'x_col1', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->channelMaster, 'channel_desc');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'model', 'value' => 'model'],
        ['attribute' => 'capacity', 'value' => 'capacity0.value'],
        ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'gst_no', 'visible' => false, 'filter' => false],
        [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'manufacturer_code', 'value' => 'manufacturerCode.manufacturer_name'],
        ['attribute' => 'is_weight_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_weight_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
        },],
        ['attribute' => 'is_quality_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_quality_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
        },],
        ['attribute' => 'bmc_milk_type', 'value' => 'bmcMilkType.animal_type_name', 'visible' => false, 'filter' => false],
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->bmc_code, 'bmc');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->bmc_code, 'bmc');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->bmc_code, 'bmc');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->bmc_code, 'bmc');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->bmc_code, 'bmc');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
        ['attribute' => 'rate_calculate_on_merge',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'rate_calculate_on_merge'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->rate_calculate_on_merge]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->rate_calculate_on_merge] : '';
        },],
        ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'password', 'visible' => false, 'filter' => false],
        ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
        ['attribute' => 'address', 'visible' => false, 'filter' => false],
        ['attribute' => 'aadhaar_no'],
        ['attribute' => 'antibiotic_check',
        'value' => function($model) {
            return $model->antibiotic_check == 1 ? 'Yes' : 'No';
        }, 'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'sub-center-bmc-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view-record' => function ($url, $dcsmodel) {
            $options = ['data-name' => isset($dcsmodel->subCenterCode) ? $dcsmodel->subCenterCode->sub_center_name : '', 'data-val' => $dcsmodel->subcenter_code, 'title' => 'View'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/organisation/tbl-dcs-bmc/view', 'id' => $dcsmodel->bmc_code, 'type' => Yii::$app->request->get('type')], ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View'], $options);
        },
        'edit' => function ($url, $dcsmodel) {
            $options = ['data-name' => isset($dcsmodel->subCenterCode) ? $dcsmodel->subCenterCode->sub_center_name : '', 'data-val' => $dcsmodel->subcenter_code, 'title' => 'Update'];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/organisation/tbl-dcs-bmc/update', 'id' => $dcsmodel->bmc_code], ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'], $options);
        },
        'delete' => ['option' => 'bmc_name,bmc_code,tbl-dcs-bmc/delete'],
        'bank-details' => function ($url, $model) {
            $options = ['data-name' => $model->bmc_name, 'data-val' => $model->bmc_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Bank Details'];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/organisation/tbl-dcs-bmc/bank-details', 'id' => $model->bmc_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->bmc_name, 'data-val' => $model->bmc_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Contact Details'];
            return GhostHtml::a('<i class="fa fas fa-user-circle"></i>', ['/organisation/tbl-dcs-bmc/contact-details', 'id' => $model->bmc_code], $options);
        },
        'mapping' => function ($url, $model) {
            $options = ['data-val' => $model->bmc_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'BMC Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-dcs-bmc/bmc-mapping', 'id' => $model->bmc_code], $options);
        },
        'silos-info' => function ($url, $model) {
            $options = ['data-name' => $model->bmc_name, 'data-val' => $model->bmc_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Silos Info.'];
            return GhostHtml::a('<i class="fa fa-plus-square"></i>', ['/organisation/tbl-dcs-bmc/silos-info', 'id' => $model->bmc_code], $options);
        },
        'generate_sentbox' => function ($url, $model) {
            $existCount = 0;
            if (!empty($model->androidInstallation->android_installation_id)) {
                $id = $model->androidInstallation->android_installation_id;
                $detail = new TblAndroidInstallationDetails();
                $existCount = $detail->find()->where(['android_installation_id' => $id, 'is_active' => 1])->count();
            }
            $class = $existCount > 0 ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Export Sentbox'), 'class' => $class];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/organisation/tbl-dcs-bmc/export-sentbox', 'id' => $model->bmc_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index', 'dcs' => Yii::$app->request->get('dcs'), 'dcsname' => Yii::$app->request->get('dcsname'), 'subcenter' => Yii::$app->request->get('subcenter'), 'subname' => Yii::$app->request->get('subname'), 'type' => Yii::$app->request->get('type')]);
?>