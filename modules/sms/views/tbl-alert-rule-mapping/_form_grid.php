<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'rule_code', 'filter' => false],
        ['attribute' => 'rule_name', 'filter' => true],
        ['attribute' => 'is_active', 'value' => function($model) {
            return !empty($model->is_active) ? Yii::$app->dropdown->getRecords('status')['data'][$model->is_active] : '';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'alert-rule-master-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view-detail' => function ($url, $model) {
            $url = Url::to(['/sms/tbl-alert-rule-mapping/view', 'id' => $model->rule_code]);
            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View']);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
