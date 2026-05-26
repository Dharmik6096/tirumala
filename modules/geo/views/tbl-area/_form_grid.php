<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<div class="">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
    ['label' => 'Region Name', 'attribute' => 'region_name', 'value' => 'regionCode.region_name'],
    ['label' => 'State Name', 'attribute' => 'state_code', 'value' => 'stateCode.state_name'],
    ['attribute' => 'area_name'],
    ['attribute' => 'local_name', 'filter' => false],
    ['attribute' => 'description', 'visible' => false, 'filter' => false],
    ['attribute' => 'address', 'visible' => true, 'filter' => false],
    ['attribute' => 'local_address', 'visible' => true, 'filter' => false],
    // Contact Detail
    [
        'label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function ($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->area_code, 'mccPlant');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
    [
        'label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function ($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->area_code, 'area');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
    [
        'label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function ($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->area_code, 'area');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
    [
        'label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function ($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->area_code, 'area');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
    [
        'label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function ($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->area_code, 'area');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
];

$grid_option = [
    'id' => 'area-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'area_name,area_code,tbl-area/delete,checkDelete()'],
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->area_name, 'data-val' => $model->area_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Contact Details'];
            return GhostHtml::a('<i class="fas fa-user-circle"></i>', ['/geo/tbl-area/contact-details', 'id' => $model->area_code], $options);
        },
        'mapping' => function ($url, $model) {
            $options = ['data-val' => $model->area_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Area Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/geo/tbl-area/area-mapping', 'id' => $model->area_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>