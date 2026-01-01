<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'animal_treatment_request_id', 'value' => function($model) {
            return !empty($model->animal_treatment_request_id) ? Yii::$app->general->getforeignkey($model->animalTreatmentRequestId, 'case_no') : '';
        }, 'filter' => false],
    ['attribute' => 'diagnosis_detail_id', 'value' => function($model) {
            return !empty($model->diagnosis_detail_id) ? Yii::$app->general->getforeignkey($model->diagnosisDetailId, 'remarks') : '';
        }, 'filter' => false],
    ['attribute' => 'ref_code'],
    ['attribute' => 'medicine_id', 'value' => function($model) {
            return !empty($model->medicine_id) ? Yii::$app->general->getforeignkey($model->medicineId, 'medicine_name') : '';
        }, 'filter' => false],
    ['attribute' => 'batch_no'],
    ['attribute' => 'qty'],
    ['attribute' => 'uom', 'value' => function($model) {
            return !empty($model->uom) ? Yii::$app->general->getforeignkey($model->uomDetail, 'unit_name') : '';
        }, 'filter' => false],
    ['attribute' => 'route'],
    ['attribute' => 'remarks'],
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
    'id' => 'treatment-details-grid',
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
