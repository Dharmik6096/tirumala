<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'module_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'DCS'), 'attribute' => 'module_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Date'), 'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->creatorId, 'applicable_date'));
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Shift'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->creatorId, ['shiftCode'], 'shift');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Pick Date'), 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->pick_datetime);
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Process Date'), 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->updated_at);
        }, 'filter' => false],
    ['attribute' => 'total_count',],
    ['attribute' => 'success_count',],
    ['attribute' => 'error_count',],
    ['attribute' => 'file_name',],
    ['attribute' => 'file_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'file_status'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->file_status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->file_status] : '';
        },],
];

$grid_option = [
    'id' => 'ftp-txn-log-a',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-collection' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Detail', 'class' => 'view-detail ' . $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/bkgprocess/tbl-ftp-txn-log/view-collection', 'id' => $model->ftp_txn_log_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['list']);
?>