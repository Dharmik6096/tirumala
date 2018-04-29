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

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\modules\UserManagement\models\search\UserSearch $searchModel
 */
$this->title = UserManagementModule::t('back', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php

$attribute = [
    [
        'attribute' => 'username',
        'label' => 'Username',
        'value' => function(User $model) {
            return Html::a(Yii::$app->general->getUserName($model->username), ['view', 'id' => $model->id], ['data-pjax' => 0]);
        },
        'format' => 'raw',
    ],
    'user_code',
    'name',
    [
        'attribute' => 'user_type_id',
        'label' => 'User Type',
        'value' => function(User $model) {
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
        'visible' => User::hasPermission('viewUserEmail'),
    ],
    [
        'attribute' => 'mobile_no',
        'value' => 'mobile_no',
        'visible' => false,
    ],
    /*[
        'class' => 'webvimark\components\StatusColumn',
        'attribute' => 'status',
        'optionsArray' => [
            [User::STATUS_ACTIVE, UserManagementModule::t('back', 'ACTIVE'), 'success'],
            [User::STATUS_INACTIVE, UserManagementModule::t('back', 'INACTIVE'), 'warning'],
            [User::STATUS_BANNED, UserManagementModule::t('back', 'BANNED'), 'danger'],
        ],
        'contentOptions' => ['style' => '',
            'width' => '100px',],
    ],*/
];

        $grid_option = [
            'id' => 'user-grid',
            'attributes' => $attribute,
            'active_column' => true,
            'actions' => [
                'update' => true,
                'delete_user' => function ($url, $model) {
                        $disable=($model->checkNotSelf() && $model->is_active==1)?'':'link-disable';
                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate','data-val'=>$model->id, 'data-name'=>$model->name, 'class'=>'user-record '.$disable];
                        return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/user-management/user/deactivate-user'], $options);  
                },
                'role' => function ($url, $model) {
                        $disable=($model->checkNotSelf())?'':'link-disable';
                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Role', 'class'=>$disable];
                        $link = $model->portal_type == 'desktop' ? '/setting/tbl-user-profile-mapping/role-assign' : '/user-management/user-permission/set';
                        return Html::a('<i class="fa fa-key"></i>', [$link, 'id' => $model->id], $options);
                },
                'org-map' => function ($url, $model) {
                        $disable=($model->checkNotSelf())?'':'link-disable';
                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Organization', 'class'=>$disable];
                        return Html::a('<i class="fa fa-link"></i>', ['/user-management/user/organization-map', 'id' => $model->id], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?> 

        <?php

        $script = "
            $('#user-grid').on('click','.user-record',function(e){
            //$('.delete-property').on('click',function(){
                   // var id = $(this).attr('value');
                    var id= $(this).attr('data-val');
                    var name = $(this).attr('data-name');
                    bootbox.confirm({
                        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate user \"'+name+'\"?</span></div></div>',
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
                                        url: '" . Url::to(['/user-management/user/delete']) . "?id=' + id,
                                        //data: 'id='+id,
                                        success: function(data) {

                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == 'success')
                                            {
                                                $.pjax.reload({container: '#user-grid'});
//                                                bootbox.alert(obj1.msg);
                                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                                            }
                                            else if (obj1.status == 'error'){
//                                                bootbox.alert(obj1.msg);
                                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                                            }
                                        },
                                        error:function(data){
                                                    //alert('Your data has not been submitted..Please try again');
                                                }
                            });
                            }
                        }
                    });
                });";
        $this->registerJs($script, View::POS_END, 'delete-manager-user');
        ?>