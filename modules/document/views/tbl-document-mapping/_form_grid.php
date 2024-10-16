<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'doc_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
        }, 'visible' => true],
        ['attribute' => 'master_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->masterType, 'master_type_name');
        }, 'filter' => Yii::$app->dropdown->dropdownfilter('documnet_master_type', $searchModel, 'master_type', Yii::t('app', 'Select'))],
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
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

