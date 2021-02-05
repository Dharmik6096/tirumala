<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false],
    ['attribute' => 'org_type', 'label' => Yii::t('app', 'Org. Type'),
        'value' => function($model) {
            return $model->getOrgType($model, 'type');
        }],
    ['attribute' => 'org_code', 'label' => Yii::t('app', 'Org. Code'), 'filter' => false, 'value' => function($model) {
            return $model->getOrgType($model, 'code');
        }],
    ['attribute' => 'org_code', 'label' => Yii::t('app', 'Org. Name'), 'filter' => false, 'value' => function($model) {
            return $model->getOrgType($model, 'name');
        }],
    ['attribute' => 'name'],
    ['attribute' => 'username'],
    ['attribute' => 'password', 'filter' => false],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'email'],
];
$grid_option = [
    'id' => 'user-android',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
