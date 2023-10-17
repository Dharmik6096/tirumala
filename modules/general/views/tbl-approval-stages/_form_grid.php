<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'process_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->processCode, 'process_desc');
        }, 'visible' => TRUE, 'filter' => TRUE],
    ['attribute' => 'approval_mode',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('approval_mode', $searchModel, 'approval_mode'),
        'value' => function ($model) {
            return isset($model->approval_mode) ? Yii::$app->dropdown->getRecords('approval_mode')['data'][$model->approval_mode] : '';
        },],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'approval-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
//        'view' => true,
        'edit' => function ($url, $model) {
            $url = Url::to(['tbl-approval-stages/update', 'id' => $model->approval_stages_code]);
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit']);
        },
        'delete' => ['option' => 'process_name,approval_stages_code,tbl-approval-stages/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
