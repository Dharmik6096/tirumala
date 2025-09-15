<?php
$attribute = [
    ['attribute' => 'dcs_code'],
    ['label' => Yii::t('app', 'DCS') . ' Ref Code', 'attribute' => 'dcs_code', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '';
        }, 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'filter' => TRUE],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'email'],
    ['attribute' => 'tag_no'],
    ['attribute' => 'animal_type_id'],
    ['attribute' => 'gender_id'],
    ['attribute' => 'breed_id'],
    ['attribute' => 'year'],
    ['attribute' => 'month'],
    ['attribute' => 'no_of_calving'],
    ['attribute' => 'last_date_of_calving'],
    ['attribute' => 'pregnancy_status'],
    ['attribute' => 'pregnancy_month'],
    ['attribute' => 'pregnancy_month_on_date'],
    ['attribute' => 'milking_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('milking_status', $searchModel, 'milking_status'),
        'value' => function ($model) {
            return isset($model->milking_status) ? Yii::$app->dropdown->getRecords('milking_status')['data'][$model->milking_status] : '';
        },
        'options' => ['style' => 'width:16.84%'],],
];

$grid_option = [
    'id' => 'member-animal-tag-details-grid',
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
