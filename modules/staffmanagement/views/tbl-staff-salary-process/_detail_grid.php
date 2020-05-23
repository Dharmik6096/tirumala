<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'salary_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->salaryHeadCode, 'salary_head_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'type_of_head',
        'filter' => FALSE,
        'value' => function($model) {
            return isset($model->type_of_head) ? Yii::$app->dropdown->getRecords('type')['data'][$model->type_of_head] : 'N/A';
        },],
    ['attribute' => 'actual_value', 'visible' => true, 'filter' => false],
    ['attribute' => 'value', 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'member-provisional-animal-details-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
        