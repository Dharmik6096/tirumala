<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'dcs_code'],
    ['attribute' => 'dcs_name', 'value' => 'dcsCode.dcs_name'],
    ['attribute' => 'purchase_rate_code'],
    ['attribute' => 'reference_code'],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
    ['attribute' => 'download_date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->download_date_time);
}],
    ['attribute' => 'is_download', 'value' => function($model) {
            return $model->is_download == 0 ? Yii::t('app', 'Done') : Yii::t('app', 'Pending');
        }, 'filter' => false],
];
$grid_option = [
    'id' => 'member-download-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>