<?php

use yii\helpers\Html;
use kartik\grid\GridView;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'banner_code', 'filter' => FALSE, 'visible' => FALSE],
        [
        'attribute' => 'from_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => FALSE],
        [
        'attribute' => 'to_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => FALSE],
        ['attribute' => 'tap_operation', 'filter' => FALSE],
        ['attribute' => 'tap_event'],
        ['attribute' => 'title'],
        ['attribute' => 'description'],
        ['attribute' => 'seq_no'],
        ['attribute' => 'banner_for', 'filter' => FALSE, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'banner-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'views' => function($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Banner View'];
            return Html::a('<i class="fa fa-eye"></i>', ['/general/tbl-banner/view', 'id' => $model->banner_code], $options);
        },
        'delete' => ['option' => 'banner_code,banner_code,/general/tbl-banner/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
