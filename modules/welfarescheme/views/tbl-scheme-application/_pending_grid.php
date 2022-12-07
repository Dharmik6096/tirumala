<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'scheme_id'],
        ['attribute' => 'application_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->application_date);
        }],
    'member_code',
    'scheme_value',
    'min_pouring_day',
    'min_pouring_qty',
    'actual_pouring_day',
    'actual_pouring_qty',
    'remarks',
];

$grid_option = [
    'id' => 'tbl-scheme-application-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'approve-application' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Approve Application')];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/welfarescheme/tbl-scheme-application/approve-application', 'id' => $model->application_id, 'app_approval_id' => 7], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
