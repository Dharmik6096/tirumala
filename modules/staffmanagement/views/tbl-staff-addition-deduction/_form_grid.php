<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false,],
    ['attribute' => 'staff_member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
        }],
    [
        'attribute' => 'app_from_date',
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'minViewMode' => 'months',
                'format' => 'mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_month($model->app_from_date);
        }],
    ['attribute' => 'tr_date',
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->tr_date);
        },
    ],
    ['attribute' => 'type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('type', $searchModel, 'type'),
        'value' => function($model) {
            return ($model->type == 1) ? Yii::t('app', 'Deduction') : Yii::t('app', 'Addition');
        }
    ],
    ['attribute' => 'amount',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->amount);
        },
    ],
    ['attribute' => 'installment_no',],
  
    ['attribute' => 'remark', 'visible' => FALSE],
];
$grid_option = [
    'id' => 'web-staff-attend-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
        