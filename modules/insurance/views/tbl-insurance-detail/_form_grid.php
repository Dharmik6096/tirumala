<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Html;
use yii\web\View;

?>
<?php

// $defaultToggle = false;
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant Ex Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'plant_code_ex');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC Ex Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'mcc_plant_code_ex');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Ex Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code_ex');
        }, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => true],
    ['attribute' => 'dcs_name'],
    ['attribute' => 'insurance_master_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->insuranceMasterCode, 'insurance_description');
        }, 'filter' => false],
    ['attribute' => 'sr_no'],
    ['attribute' => 'member_id'],
    ['attribute' => 'member_code', 'value' => function($model) {
            return $model->member_code;
        }, 'filter' => true],
    ['attribute' => 'member_name'],
    ['attribute' => 'adhar_no', 'value' => function($model) {
            return Yii::$app->general->maskAadhar($model->adhar_no);
        }, 'filter' => false
    ],
    ['attribute' => 'age'],
    ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        },
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('gender', $searchModel, 'gender_code')],
    [
        'attribute' => 'dob',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->dob);
        }],
    [
        'attribute' => 'date_of_joining_scheme',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_of_joining_scheme);
        }],
    ['attribute' => 'nominee_member_name'],
    ['attribute' => 'originating_org_type', 'filter' => FALSE,
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->originating_org_type, 'originating_org_type');
        }
    ],
    ['attribute' => 'status', 'filter' => FALSE,
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->status, 'insurance_status');
        }
    ],
];

$grid_option = [
    'id' => 'insurance-master-detail-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => function ($url, $model) {
            $class = $model->disableAction('edit');
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->insurance_detail_code];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'delete' => ['option' => 'member_name,insurance_detail_code,/insurance/tbl-insurance-detail/delete,disableAction()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
// if (!empty($searchModel->errors)) {
//     $defaultToggle = true;
// }
// $script = "";
// if ($defaultToggle) {
//     $script .= "
//         $(document).ready(function () {
//             $('#search_filter').modal('toggle');
//         });
//     ";
// }
// $this->registerJs($script, View::POS_READY, 'insurance-detail');
?>
