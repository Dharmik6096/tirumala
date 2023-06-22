<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code'],
        ['attribute' => 'plant_code_ex'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'name'],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'capacity', 'value' => 'capacity0.value'],
        [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
//    ['attribute' => 'contact_person'],
//    ['attribute' => 'mobile_no'],
//    ['attribute' => 'email'],
    ['attribute' => 'description', 'visible' => false, 'filter' => false],
        ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->plant_code, 'plant');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->plant_code, 'plant');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->plant_code, 'plant');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->plant_code, 'plant');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->plant_code, 'plant');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
        ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'plant-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'mapping' => function ($url, $model) {
            $options = ['data-name' => $model->name, 'data-val' => $model->plant_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Prouduct Group Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-plant/map-product-groups', 'id' => $model->plant_code], $options);
        },
        'delete' => ['option' => 'name,plant_code,tbl-plant/delete'],
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->name, 'data-val' => $model->plant_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details'];
            return GhostHtml::a('<i class="fa fas fa-user-circle"></i>', ['/organisation/tbl-plant/contact-details', 'id' => $model->plant_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>