<?php
$attribute = [
    ['attribute' => 'insurance_detail_code', 'filter' => false],
    ['attribute' => 'member_id', 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'filter' => FALSE],
    ['attribute' => 'member_name', 'filter' => false],
    ['attribute' => 'adhar_no', 'value' => function($model) {
            return Yii::$app->general->maskAadhar($model->adhar_no);
        }, 'filter' => false
    ],
    ['attribute' => 'dob', 'filter' => false],
    ['attribute' => 'age', 'filter' => false],
    ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        }, 'filter' => false],
    ['attribute' => 'nominee_adhar_no', 'value' => function($model) {
            return Yii::$app->general->maskAadhar($model->adhar_no);
        }, 'filter' => false
    ],
    ['attribute' => 'nominee_member_name', 'filter' => false],
];

$grid_option = [
    'id' => 'insurance-master-member-detail',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>