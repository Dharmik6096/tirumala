<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'dcs_code', 'filter' => false],
    ['label' => Yii::t('app', 'DCS') . ' Ref Code', 'attribute' => 'dcs_code', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'). ' Name', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '';
        }, 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code'), 'value' => function($model) {
            return ($model->member_type == 'NonMember') ? $model->member_code : Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'filter' => false],
    ['attribute' => 'member_name', 'label' => Yii::t('app', 'Member'). ' Name', 'value' => function($model) {
            return ($model->member_type == 'NonMember') ? $model->member_name : Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => false],
    ['attribute' => 'member_type'],
    ['attribute' => 'mobile_number'],
    ['attribute' => 'address'],
    ['attribute' => 'case_type_id', 'value' => function($model) {
            return !empty($model->case_type_id) ? Yii::$app->general->getforeignkey($model->caseTypeId, 'case_type_name') : '';
        }, 'filter' => true],
    ['attribute' => 'member_animal_tag_id', 'value' => function($model) {
            return !empty($model->member_animal_tag_id) ? Yii::$app->general->getforeignkey($model->memberAnimalTagId, 'tag_no') : '';
        }, 'filter' => true],
    ['attribute' => 'disease_id', 'value' => function($model) {
            return !empty($model->disease_id) ? Yii::$app->general->getforeignkey($model->diseaseId, 'disease_name') : '';
        }, 'filter' => true],
    ['attribute' => 'animal_type_id', 'value' => function($model) {
            return !empty($model->animal_type_id) ? Yii::$app->general->getforeignkey($model->animalTypeId, 'animal_type_name') : '';
        }, 'filter' => true],
    ['attribute' => 'gender_id', 'value' => function($model) {
            return !empty($model->gender_id) ? Yii::$app->general->getforeignkey($model->genderId, 'gender') : '';
        }, 'filter' => true],
    ['attribute' => 'breed_id', 'value' => function($model) {
            return !empty($model->breed_id) ? Yii::$app->general->getforeignkey($model->breedId, 'breed_name') : '';
        }, 'filter' => true],
    ['attribute' => 'year'],
    ['attribute' => 'month'],
    [
        'attribute' => 'tran_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tran_datetime);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'member-animal-treatment-request-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'vetenary-assistance' => function ($url, $model) {
            $options = ['title' => 'Print', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/veterinary/tbl-animal-treatment-request/vetenary-assistance', 'id' => $model->animal_treatment_request_id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
