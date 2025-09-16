<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'animal_treatment_request_id', 'value' => function($model) {
            return !empty($model->animal_treatment_request_id) ? Yii::$app->general->getforeignkey($model->animalTreatmentRequestId, 'case_no') : '';
        }, 'filter' => false],
    ['attribute' => 'disease_id', 'value' => function($model) {
            // return !empty($model->disease_id) ? Yii::$app->general->getforeignkeyWithComma($model, $model->disease_id, 'diseaseId', 'disease_name') : '';
            return !empty($model->disease_id) ? Yii::$app->general->getforeignkey($model->diseaseId, 'disease_name') : '';
        }, 'filter' => false],
    ['attribute' => 'milking_status'],
    ['attribute' => 'milk_production'],
    ['attribute' => 'symptom_id', 'value' => function($model) {
            return !empty($model->symptom_id) ? Yii::$app->general->getforeignkey($model->symptomId, 'symptom_name') : '';
        }, 'filter' => false],
    ['attribute' => 'remarks'],
    ['attribute' => 'lat_long'],
    ['attribute' => 'case_fee'],
    ['attribute' => 'bank_name'],
    ['attribute' => 'gateway'],
    ['attribute' => 'payment_mode'],
    [
        'attribute' => 'tran_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tran_datetime);
        }],
];

$grid_option = [
    'id' => 'diagnosis-details-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
