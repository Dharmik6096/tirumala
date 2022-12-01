<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'doc_group', 'value' => function($model) {
            return isset($model->doc_group) ? Yii::$app->dropdown->getRecords('doc_group')['data'][$model->doc_group] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('doc_group', $searchModel, 'doc_group'),],
        ['attribute' => 'doc_name'],
        ['attribute' => 'doc_ext', 'value' => function($model) {
            return !empty($model->doc_ext) && !empty(Yii::$app->dropdown->getRecords('doc_ext')['data'][$model->doc_ext]) ? Yii::$app->dropdown->getRecords('doc_ext')['data'][$model->doc_ext] : (!empty($model->doc_ext) ? $model->doc_ext : '');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('doc_ext', $searchModel, 'doc_ext'),],
];

$grid_option = [
    'id' => 'tbl-scheme-document-master-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = '';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/welfarescheme/tbl-scheme-document-master/update', 'id' => $model->doc_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
