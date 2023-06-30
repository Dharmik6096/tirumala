<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'plant_code', 'value' => 'plantCode.name', 'visible' => true, 'filter' => true],
    ['attribute' => 'mcc_plant_code'],
    ['attribute' => 'mcc_plant_code_ex'],
    ['attribute' => 'ref_code'],
    ['attribute' => 'name'],
    ['attribute' => 'local_name', 'filter' => false],
    ['attribute' => 'capacity', 'value' => 'capacity0.value'],
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
    [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
//    ['attribute' => 'contact_person'],
//    ['attribute' => 'local_contact_person_name','visible'=>false,'filter'=>false],
//    ['attribute' => 'email','visible'=>false,],
//    ['attribute' => 'mobile_no','visible'=>false,],
    ['attribute' => 'description', 'visible' => false, 'filter' => false],
    ['attribute' => 'gst_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->mcc_plant_code, 'mccPlant');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->mcc_plant_code, 'mccPlant');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->mcc_plant_code, 'mccPlant');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->mcc_plant_code, 'mccPlant');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->mcc_plant_code, 'mccPlant');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
    ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'recovery_validate',
        'filter' => FALSE,
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->recovery_validate]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->recovery_validate] : '';
        },],
];

$grid_option = [
    'id' => 'mcc-plant-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'name,mcc_plant_code,tbl-mcc-plant/delete'],
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->name, 'data-val' => $model->mcc_plant_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Contact Details'];
            return GhostHtml::a('<i class="fa fas fa-user-circle"></i>', ['/organisation/tbl-mcc-plant/contact-details', 'id' => $model->mcc_plant_code], $options);
        }, 'mapping' => function ($url, $model) {
            $options = ['data-val' => $model->mcc_plant_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'MCC Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-mcc-plant/mcc-mapping', 'id' => $model->mcc_plant_code], $options);
        },
        'silos-info' => function ($url, $model) {
            $options = ['data-name' => $model->name, 'data-val' => $model->mcc_plant_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Silos Info.'];
            return GhostHtml::a('<i class="fa fa-plus-square"></i>', ['/organisation/tbl-mcc-plant/silos-info', 'id' => $model->mcc_plant_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>