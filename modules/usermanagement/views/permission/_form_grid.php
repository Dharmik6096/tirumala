<?php

use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
use app\modules\usermanagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
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
 * @var webvimark\modules\UserManagement\models\rbacDB\search\PermissionSearch $searchModel
 * @var yii\web\View $this
 */
$this->title = UserManagementModule::t('back', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

/* $columns = [
  ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT, 'mergeHeader' => false,],
  [
  'attribute' => 'description',
  'value' => function($model) {
  if ($model->name == Yii::$app->getModule('user-management')->commonPermissionName) {
  return Html::a(
  $model->description, ['view', 'id' => $model->name], ['data-pjax' => 0, 'class' => 'label label-primary']
  );
  } else {
  return Html::a($model->description, ['view', 'id' => $model->name], ['data-pjax' => 0]);
  }
  },
  'format' => 'raw',
  ],
  'name',
  [
  'attribute' => 'group_code',
  'filter' => ArrayHelper::map(AuthItemGroup::find()->asArray()->all(), 'code', 'name'),
  'value' => function(Permission $model) {
  return $model->group_code ? $model->group->name : '';
  },
  ],
  [
  'class' => 'kartik\grid\ActionColumn',
  'width' => '125px',
  'contentOptions' => ['class' => 'action-icons'],
  'template' => '{view}{update}',
  'dropdown' => false,
  'mergeHeader' => false,
  'order' => DynaGrid::ORDER_FIX_RIGHT,
  'buttons' => [
  'view' => function($url, $model) {
  return Html::a('<span class="fa fa-key"></span>', $url);
  }
  //                    'delete' => function ($url, $model) {
  //                        $options = ['class' => 'permission-record', 'data-name' => $model->name, 'data-val' => $model->name];
  //                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:void(0)', $options);
  //                    },
  ]
  ],
  ]; */
?>

<?php

$attribute = [
    [
        'attribute' => 'description',
        'value' => function($model) {
            if ($model->checkNotVendor()) {
                if ($model->name == Yii::$app->getModule('user-management')->commonPermissionName) {
                    return Html::a(
                                    $model->description, ['view', 'id' => $model->name], ['data-pjax' => 0, 'class' => 'label label-primary']
                    );
                } else {
                    return Html::a($model->description, ['view', 'id' => $model->name], ['data-pjax' => 0]);
                }
            } else
                return $model->description;
        },
        'format' => 'raw',
    ],
    'name',
    [
        'attribute' => 'group_code',
        'filter' => ArrayHelper::map(AuthItemGroup::find()->asArray()->all(), 'code', 'name'),
        'value' => function(Permission $model) {
            // return $model->group_code ? $model->group->name : '';
            return $model->group_code ? Yii::$app->general->getforeignkey($model->group,'name') : '';
        },
    ],
];

$grid_option = [
    'id' => 'permission-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) {
            $class = $model->checkNotVendor() ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Assign Permission', 'class' => $class];
            return Html::a('<i class="fa fa-key"></i>', ['/user-management/permission/view', 'id' => $model->name], $options);
        },
        'update' => function($url, $model) {
            $class = $model->checkNotVendor() ? '' : 'link-disable';
//                    $url=  str_replace('edit', 'update', $url);
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Update', 'class' => $class];
            return Html::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        'delete' => ['option' => 'group_code,name,/user-management/permission/delete,checkNotVendor()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?> 

<?php

//                $script = "
//            $('#permission-grid-pjax').on('click','.permission-record',function(e){
//            //$('.delete-property').on('click',function(){
//                   // var id = $(this).attr('value');
//                    var id= $(this).attr('data-val');
//                    var name = $(this).attr('data-name');
//                    bootbox.confirm({
//                        message: 'Are you sure you want to delete this permission \"'+name+'\"?',
//                        buttons: {
//                            'cancel': {
//                                            label: 'Cancel',
//                                            className: 'btn btn-default btn-raised'
//                              },
//                            'confirm': {
//                                            label: 'Ok',
//                                            className: 'btn btn-default btn-raised'
//                             }
//                        },
//                        callback: function(result) {
//                            if (result) {
//                              $('#loader').show();
//                                 $.ajax({
//                                        type: 'post',
//                                        url: '" . Yii::$app->request->baseUrl . "/index.php?r=user-management/permission/delete&id=' + id,
//                                        //data: 'id='+id,
//                                        success: function(data) {
//
//                                            var obj1 = $.parseJSON(data);
//                                            if (obj1.status == 'success')
//                                            {
//                                                $.pjax.reload({container: '#permission-grid-pjax'});
//                                                bootbox.alert(obj1.msg);
//                                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
//                                            }
//                                            else if (obj1.status == 'error'){
//                                                bootbox.alert(obj1.msg);
//                                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
//                                            }
//                                        },
//                                        error:function(data){
//                                                    //alert('Your data has not been submitted..Please try again');
//                                                }
//                            });
//                            }
//                        }
//                    });
//                });";
//                $this->registerJs($script, View::POS_END, 'delete-manager');
?>
