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
    ['attribute' => 'user_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCodeById, 'name');
        }],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return (strtolower($model->user_type) == 'vsp' || strtolower($model->user_type) == 'farmer') ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '';
        }, 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return strtolower($model->user_type) == 'farmer' ? Yii::$app->general->getforeignkey($model->userCodeById, 'name') : '';
        }, 'filter' => false],
    ['attribute' => 'eipl_app_feedback_item_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->eiplAppFeedbackItemCode, 'feedback_item_name');
        }],
    ['attribute' => 'feedback_message', 'value' => function($model) {
            return nl2br(Yii::$app->general->asciiToTextConvert($model->feedback_message));
        }],
    [
        'attribute' => 'feedback_message_datetime', 'enableSorting' => false,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->feedback_message_datetime);
        }, 'filter' => false],
    ['attribute' => 'user_type'],
    ['attribute' => 'feedback_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('feedback_status', $searchModel, 'feedback_status'),
        'value' => function ($model) {
            return isset($model->feedback_status) ? Yii::$app->dropdown->getRecords('feedback_status')['data'][$model->feedback_status] : '';
        },
        'options' => ['style' => 'width:16.84%'],],
];

$grid_option = [
    'id' => 'feedback-master-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'feedback-activity-status' => function ($url, $model) {
            $twentyFourHoursAgo = date("Y-m-d H:i:s", strtotime('-24 hours'));
            if ($model->created_at >= $twentyFourHoursAgo) {
                return GhostHtml::a('<i class="fa fa-circle" style="color: #07a309 !important;"></i>', '', ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Feedback Activity']);
            } else {
                return GhostHtml::a('', '', ['class' => 'hidden-button m-r-lg']);
            }
        },
        'view' => true,
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
