<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'craeted_by', 'label' => Yii::t('app', 'Assign By'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->createdBy, 'name');
        }, 'filter' => false],
    ['attribute' => 'assign_to', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'assign_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->assign_date);
        }, 'filter' => false],
    ['attribute' => 'assign_time', 'filter' => false],
    ['attribute' => 'assign_remarks', 'filter' => false],
    ['attribute' => 'complaint_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complaint_status', $searchModel, 'complaint_status'),
        'value' => function ($model) {
            return isset($model->complaint_status) ? Yii::$app->dropdown->getRecords('complaint_status')['data'][$model->complaint_status] : '';
        }, 'filter' => false],
    [
        'attribute' => 'attachement',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->attachment)) {
                $absoluteBaseUrl = Url::base(true);
                $path = $absoluteBaseUrl . '/web/uploads/software-complaint-docs/';
                return Html::a('<i class="fa fa-download"><i/>', $path . $model->attachment, ['target' => '_blank']);
            }
        }],
];

$grid_option = [
    'id' => 'complaint-assign-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
