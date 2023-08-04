<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'doc_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'master_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('master_type', $searchModel, 'master_type'),
        'value' => function ($model) {
            return isset($model->master_type) ? Yii::$app->dropdown->getRecords('master_type')['data'][$model->master_type] : '';
        },],
        ['attribute' => 'is_mandate',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_mandate'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_mandate]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_mandate] : '';
        },],
];

$grid_option = [
    'id' => 'tbl-document-mapping-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'document-mapping' => function ($url, $model) {
            $options = ['data-name' => $model->master_type, 'data-val' => $model->mapping_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Document Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/document/tbl-document-mapping/document-mapping', 'id' => $model->mapping_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

