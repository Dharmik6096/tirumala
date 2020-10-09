<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */
$this->title = Yii::$app->label->title('view', Yii::$app->general->getUserName($model->username));
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Yii::$app->controls->cancel($model); ?><?= $this->title ?></div>
    <div class="panel-body">
        <div class="table-responsive">
            <?=
            DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered detail-view'],
                'attributes' => [
//                        'id',
                    [
                        'attribute' => 'status',
                        'value' => User::getStatusValue($model->status),
                    ],
                    [
                        'attribute' => 'user_code',
                        'label' => 'User Code',
                        'value' => $model->user_code,
                    ],
                    'name',
                    [
                        'attribute' => 'username',
                        'label' => 'Username',
                        'value' => Yii::$app->general->getUserName($model->username),
                    ],
                    [
                        'attribute' => 'email',
                        'value' => $model->email,
                        'format' => 'email',
                        'visible' => User::hasPermission('viewUserEmail'),
                    ],
                    [
                        'label' => UserManagementModule::t('back', 'Roles'),
                        'value' => implode('<br/>', (array) ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description')),
                        'visible' => User::hasPermission('viewUserRoles'),
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Organazations',
                        'value' => call_user_func(function ($data) {
                                    $org = User::getUserOrganizations($data->user_code);
                                    if (!empty($org))
                                        return implode('<br/>', (array) User::getUserOrganizations($data->user_code));
                                    else
                                        return '';
                                }, $model),
                        'format' => 'raw',
                    ],
//                        [
//                            'attribute' => 'bind_to_ip',
//                            'visible' => User::hasPermission('bindUserToIp'),
//                        ],
//                        array(
//                            'attribute' => 'registration_ip',
//                            'value' => Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target" => "_blank"]),
//                            'format' => 'raw',
//                            'visible' => User::hasPermission('viewRegistrationIp'),
//                        ),
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ])
            ?>
        </div>
        <div class="col-sm-12 shortcut-main padding_top_20" shortcut="true" display_shortcut="false" hilight_shortcut="true">
            <div class="form-group">
                <?= GhostHtml::a(UserManagementModule::t('back', 'edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+e']) ?>
                <?php
                /* echo GhostHtml::a(UserManagementModule::t('back', 'delete'), 'javascript:void(0)', [
                  'class' => 'btn btn-default user-record apply-shortcut',
                  'shortcut_key' => 'ctrl+alt+d',
                  'data' => [
                  //'confirm' => UserManagementModule::t('back', 'Are you sure you want to delete this user?'),
                  //'method' => 'post',
                  'name'=>$model->username,
                  'val'=>$model->id,
                  ],
                  ]) */
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
            $('.user-record').on('click',function(e){
                    var id= $(this).attr('data-val');
                    var name = $(this).attr('data-name');
                    bootbox.confirm({
                        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Are you sure you want to delete this user \"'+name+'\"?</span></div></div>',
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
                                        type: 'post',
                                        url: '" . Yii::$app->request->baseUrl . "/index.php?r=user-management/user/delete&id=' + id,
                                        //data: 'id='+id,
                                        success: function(data) {

                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == 'success')
                                            {
                                                $.pjax.reload({container: '#user-grid-list'});
                                                bootbox.alert(obj1.msg);
                                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                                            }
                                            else if (obj1.status == 'error'){
                                                bootbox.alert(obj1.msg);
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
$this->registerJs($script, View::POS_END, 'delete-manager');
?>
