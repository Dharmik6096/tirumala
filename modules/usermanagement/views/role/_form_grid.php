<?php

use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\UserManagementModule;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use yii\web\View;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\modules\UserManagement\models\rbacDB\search\RoleSearch $searchModel
 * @var yii\web\View $this
 */
$this->title = UserManagementModule::t('back', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$attribute = [
    [
        'attribute' => 'description',
        'value' => function(Role $model) {
            if ($model->checkNotVendor())
                return Html::a($model->description, ['view', 'id' => $model->name], ['data-pjax' => 0]);
            else
                return $model->description;
        },
        'format' => 'raw',
    ],
    'name',
];

$grid_option = [
    'id' => 'role-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) {
            $class = $model->checkNotVendor() ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Permission Group', 'class' => $class];
            return Html::a('<i class="fa fa-key"></i>', ['/user-management/role/view', 'id' => $model->name], $options);
        },
        'edit' => function($url, $model) {
            $class = $model->checkNotVendor() ? '' : 'link-disable';
            $url = str_replace('edit', 'update', $url);
            $url = ['/user-management/role/update', 'id' => $model->name];
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update', 'class' => $class];
            return Html::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        //'update' => true,
        'delete' => ['option' => 'name,name,/user-management/role/delete,checkNotVendor()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>