<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'activity_type', 'filter' => false],
        ['attribute' => 'created_at', 'filter' => false],
        ['attribute' => 'contact_person', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->complainActivity, 'contact_person');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'asset', 'value' => function ($model) {
            return Yii::$app->general->getmultiforeignkey($model->complainActivity, ['asset'], 'asset_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'problem_desc', 'value' => function ($model) {
            return Yii::$app->general->getmultiforeignkey($model->complainActivity, ['complainProblem'], 'problem_desc');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'assign_to', 'value' => function ($model) {
            return !empty($model->contactDetailsCodes) ? $model->contactDetailsCodes->name . '(' . $model->contactDetailsCodes->mobile_no . ')' : '';
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'complaint_activity',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $model, $grid_option, '', false);
?>
