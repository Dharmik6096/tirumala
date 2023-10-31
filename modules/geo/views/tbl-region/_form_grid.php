<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
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
    ['attribute' => 'region_name'],
    ['label' => 'State Name', 'attribute' => 'state_code', 'value' => 'stateCode.state_name'],
    ['attribute' => 'local_name', 'filter' => false],
    ['attribute' => 'description', 'visible' => false, 'filter' => false],
    ['attribute' => 'address', 'visible' => true, 'filter' => false],
    ['attribute' => 'local_address', 'visible' => true, 'filter' => false],
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->region_code, 'mccPlant');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->region_code, 'region');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->region_code, 'region');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->region_code, 'region');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->region_code, 'region');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
];

$grid_option = [
    'id' => 'region-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'region_name,region_code,tbl-region/delete'],
        'contact-details' => function ($url, $model) {
    $options = ['data-name' => $model->region_name, 'data-val' => $model->region_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details'];
    return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/geo/tbl-region/contact-details', 'id' => $model->region_code], $options);
},
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>