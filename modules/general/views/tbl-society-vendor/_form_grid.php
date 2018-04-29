<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use yii\web\View;
?>


<?php

$attribute = [
    ['label'=>'Vendor','attribute'=>'role'],
];

$grid_option = [
    'id' => 'user-grid',
    'attributes' => $attribute,
   // 'filterModel' => $searchModel,
    'active_column' => false,
    'actions' => [
       'soc-map' => function ($url, $model) {
          $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Map Societies'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/general/tbl-society-vendor/create', 'id' => $model->role], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, false, $grid_option);
?> 