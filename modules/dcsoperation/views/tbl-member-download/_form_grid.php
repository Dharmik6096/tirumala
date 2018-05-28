<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name'],
    ['attribute' => 'is_download', 'value' => function($model) {
            return $model->is_download == 0 ? Yii::t('app', 'Done') : Yii::t('app', 'Pending');
        }, 'filter' => false],
    ['attribute' => 'upload_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->upload_datetime);
}],
];
$grid_option = [
    'id' => 'member-download-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>