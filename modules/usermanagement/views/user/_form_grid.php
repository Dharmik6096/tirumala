<?php

use app\modules\usermanagement\components\GhostHtml;
use app\modules\usermanagement\models\rbacDB\Role;
use app\modules\usermanagement\models\User;
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

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\modules\UserManagement\models\search\UserSearch $searchModel
 */
$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
$attribute = [
    [
        'attribute' => 'username',
        'label' => 'Username',
        'value' => function (User $model) {
            return Html::a(Yii::$app->general->getUserName($model->username), ['view', 'id' => $model->id], ['data-pjax' => 0]);
        },
        'format' => 'raw',
    ],
    'user_code',
    'name',
    [
        'attribute' => 'user_type_id',
        'label' => 'User Type',
        'value' => function (User $model) {
            return isset($model->userType) ? $model->userType->user_type : '';
        },
        'format' => 'raw',
    ],
    //                [
    //                    'attribute' => 'user_identity',
    //                    'label' => 'User Org.',
    //                    'value' => function(User $model) {
    //                        return $model->getUserOrganizations($userID);
    //                    },
    //                            'format' => 'raw',
    //                ],              
    [
        'attribute' => 'email',
        'format' => 'raw',
        'visible' => true,
    ],
    [
        'attribute' => 'mobile_no',
        'value' => 'mobile_no',
        'visible' => TRUE,
        'filter' => true,
    ],
    ['attribute' => 'allow_app_login',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('allow_app_login', $searchModel, 'allow_app_login'),
        'value' => function (User $model) {
            return isset($model->allow_app_login) ? Yii::$app->dropdown->getRecords('allow_app_login')['data'][$model->allow_app_login] : '';
        },],
    ['attribute' => 'login_type',
        'filter' => FALSE,
        'value' => function (User $model) {
            return isset($model->login_type) ? (!empty(Yii::$app->dropdown->getRecords('user_login_type')['data'][$model->login_type]) ? Yii::$app->dropdown->getRecords('user_login_type')['data'][$model->login_type] : '') : '';
        },],
    [
        'attribute' => 'department',
        'value' => function (User $model) {
            return Yii::$app->general->getforeignkey($model->departmentCode, 'department');
        },
    ],
    [
        'attribute' => 'wef_date',
        'value' => function (User $model) {
            return Yii::$app->controls->view_date($model->wef_date);
        },
    ],
    [
        'attribute' => 'designation_code',
        'value' => function (User $model) {
            return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
        },
    ],
    [
        'attribute' => 'primary_parent',
        'value' => function (User $model) {
            return Yii::$app->general->getforeignkey($model->primaryParent, 'name');
        },
    ],
    [
        'attribute' => 'secondary_parent',
        'value' => function (User $model) {
            return Yii::$app->general->getforeignkey($model->secondaryParent, 'name');
        },
    ],
    [
        'attribute' => 'created_at',
        'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->created_at, 'php:d-m-Y H:i:s');
        },
        'visible' => FALSE,
        'filter' => FALSE
    ],
    [
        'attribute' => 'date_of_joining',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->date_of_joining);
        },
        'visible' => FALSE,
        'filter' => FALSE
    ],
    [
        'attribute' => 'created_by',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        },
        'visible' => FALSE,
        'filter' => FALSE
    ],
    'employee_id',
        /* [
          'class' => 'webvimark\components\StatusColumn',
          'attribute' => 'status',
          'optionsArray' => [
          [User::STATUS_ACTIVE, UserManagementModule::t('back', 'ACTIVE'), 'success'],
          [User::STATUS_INACTIVE, UserManagementModule::t('back', 'INACTIVE'), 'warning'],
          [User::STATUS_BANNED, UserManagementModule::t('back', 'BANNED'), 'danger'],
          ],
          'contentOptions' => ['style' => '',
          'width' => '100px',],
          ], */
];

$grid_option = [
    'id' => 'user-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'role' => function ($url, $model) {
            $disable = ($model->checkNotSelf()) ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Role', 'class' => $disable];
            $link = $model->portal_type == 'desktop' ? '/setting/tbl-user-profile-mapping/role-assign' : '/user-management/user-permission/set';
            return Html::a('<i class="fa fa-key"></i>', [$link, 'id' => $model->id], $options);
        },
        'org-map' => function ($url, $model) {
            $disable = ($model->checkNotSelf()) ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Organization', 'class' => $disable];
            return Html::a('<i class="fa fa-link"></i>', ['/user-management/user/organization-map', 'id' => $model->id], $options);
        },
        'user-recovery' => function ($url, $model) {
            $date = Yii::$app->controls->view_date($model->wef_date);
            if ($model->is_active == 1) {
                $disable = ($model->checkNotSelf() && (empty($date))) ? '' : 'link-disable';
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Deactivate', 'class' => 'deactive-user ' . $disable, 'data-val' => $model->id];
                return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/user-management/user/deactivate-user', 'id' => $model->id], $options);
            } else {
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Activate', 'class' => 'react-user', 'data-val' => $model->id, 'data-name' => $model->name];
                return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/user-management/user/activate-user', 'id' => $model->id], $options);
            }
        },
        'reset-password' => function ($url, $model) {
            $disable = ''; //($model->checkNotSelf()) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Reset Password', 'class' => $disable];
            return Html::a('<i class="fa fa-user"></i>', ['/user-management/user/password-reset', 'id' => $model->id], $options);
        },
        'user-latlong-map' => function ($url, $model) {
            $disable = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Organization Lat Long Map', 'class' => $disable];
            return Html::a('<i class="fa fa-plus"></i>', ['/organisation/tbl-organization-latlong/map-route-source', 'id' => $model->id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id='deactive_user'></div>

<?php
$script = "$(document).ready(function(){
    $(document).on('click','.deactive-user',function(e){
    var id= $(this).attr('data-val');
         AddRecoveryData(id);
    });
    function AddRecoveryData(id){
        if(id != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/user-management/user/deactive-user']) . "',
                data: {'id' : id},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#deactive_user').html(data);
                   $('#UserModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
    

    $(document).on('click','.react-user',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Activate \"'+name+'\"?</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['activate-user']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#user-grid'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        }
            });
            }
        }
    });
    });  
});";
$this->registerJs($script, View::POS_END, 'delete-manager-user');
?>