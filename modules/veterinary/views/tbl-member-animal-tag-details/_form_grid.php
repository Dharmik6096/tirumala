<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'dcs_code', 'filter' => false],
    ['label' => Yii::t('app', 'DCS') . ' Ref Code', 'attribute' => 'dcs_code', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'). ' Name', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '';
        }, 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member'). ' Name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => false],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'email'],
    ['attribute' => 'tag_no'],
    ['attribute' => 'animal_type_id', 'value' => function($model) {
            return !empty($model->animal_type_id) ? Yii::$app->general->getforeignkey($model->animalTypeId, 'animal_type_name') : '';
        }],
    ['attribute' => 'gender_id', 'value' => function($model) {
            return !empty($model->gender_id) ? Yii::$app->general->getforeignkey($model->genderId, 'gender') : '';
        }],
    ['attribute' => 'breed_id', 'value' => function($model) {
            return !empty($model->breed_id) ? Yii::$app->general->getforeignkey($model->breedId, 'breed_name') : '';
        }],
    ['attribute' => 'year'],
    ['attribute' => 'month'],
    ['attribute' => 'no_of_calving'],
    [
        'attribute' => 'last_date_of_calving',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->last_date_of_calving);
        }],
    ['attribute' => 'pregnancy_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('pregnancy_status', $searchModel, 'pregnancy_status'),
        'value' => function ($model) {
            return isset($model->pregnancy_status) ? Yii::$app->dropdown->getRecords('pregnancy_status')['data'][$model->pregnancy_status] : '';
        },],
    ['attribute' => 'pregnancy_month'],
    [
        'attribute' => 'pregnancy_month_on_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->pregnancy_month_on_date);
        }],
    ['attribute' => 'milking_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('milking_status', $searchModel, 'milking_status'),
        'value' => function ($model) {
            return isset($model->milking_status) ? Yii::$app->dropdown->getRecords('milking_status')['data'][$model->milking_status] : '';
        },],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'member-animal-tag-details-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-detail' => function ($url, $model) {
            $options = ['data-code' => $model->member_animal_tag_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Member Animal Tag Detail'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/veterinary/tbl-member-animal-tag-details/view', 'id' => $model->member_animal_tag_id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
