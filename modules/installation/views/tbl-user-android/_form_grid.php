<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
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
    ['attribute' => 'role_code','label' => 'Role Name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->userCode, ['roleCode'], 'description');
        }],               
];
$grid_option = [
    'id' => 'user-android',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
//         'mapping' => function ($url, $model) {
//            $options = ['data-name' => $model->username, 'data-val' => $model->user_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Role Mapping'];
//            return GhostHtml::a('<i class="fa fa-key"></i>', ['/installation/tbl-user-android/map-roles', 'id' => $model->user_code], $options);
//        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
