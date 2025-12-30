<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'activityStatus', 'label' => '', 'visible' => true, 'value' => function ($model) {
            return Yii::$app->general->generateActivityStatus($model, 'complain_assignment_datetime', 'Complain Activity');
        }, 'format' => 'raw', 'contentOptions' => ['class' => 'sticky-column']],
        ['attribute' => 'complain_code', 'visible' => true, 'filter' => true],
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'contact_person'],
        ['attribute' => 'mobile_no'],
        ['attribute' => 'complain_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->complain_datetime);
        }],
        ['attribute' => 'complain_type_code', 'value' => 'complainFors.complain_type', 'filter' => false, 'visible' => false],
        ['attribute' => 'affects_data', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
        'value' => function($model) {
            return $model->affects_data == 1 ? 'Yes' : 'No';
        }],
        ['attribute' => 'physical_damage', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
        'value' => function($model) {
            return $model->physical_damage == 1 ? 'Yes' : 'No';
        }],
        ['attribute' => 'asset_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->asset, 'asset_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'serial_number', 'visible' => true, 'filter' => false],
        [
        'attribute' => 'complain_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complain_status', $searchModel, 'complain_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status]) ? Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status] : '';
        }],
        ['attribute' => 'user_code', 'value' => function ($model) {
            return !empty($model->contactDetailsCodes) ? $model->contactDetailsCodes->name . '(' . $model->contactDetailsCodes->mobile_no . ')' : '';
        }, 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $url = Url::to(['tbl-complain/update', 'id' => $model->complain_code]);
            $class = '';
            if (!$model->checkEditable()) {
                $url = '#';
                $class = 'disabled';
            }
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->complain_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        'delete' => ['option' => 'complain_code,complain_code,tbl-complain/delete,canDelete()'],
        'assign-complain' => function ($url, $model) {
            $url = Url::to(['assign-complain', 'complain_code' => $model->complain_code]);
            $class = ($model->checkAssign()) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Assign', 'class' => 'assign-complain' . $class, 'data-complain_code' => $model->complain_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-user"></i>', $url, $options);
        },
        'resolve-complain' => function ($url, $model) {
            $url = Url::to(['tbl-complain/resolve-complain', 'id' => $model->complain_code]);
            $status = $model->getComplainStatus($model->complain_code);
            $class = !isset($status) ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->complain_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-registered"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
