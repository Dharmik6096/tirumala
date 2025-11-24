<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use app\modules\usermanagement\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\detail\DetailView;
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
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'user_code',
                            'label' => 'User Code',
                            'value' => $model->user_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'status',
                            'value' => User::getStatusValue($model->status),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],

                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'username',
                            'label' => 'Username',
                            'value' => Yii::$app->general->getUserName($model->username),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mobile_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'email',
                            'value' => $model->email,
                            'format' => 'email',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'allow_app_login',
                            'value' => isset($model->allow_app_login) ? Yii::$app->dropdown->getRecords('allow_app_login')['data'][$model->allow_app_login] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'login_type',
                            'value' => isset($model->login_type) ? (!empty(Yii::$app->dropdown->getRecords('dcs_union_login_type')['data'][$model->login_type]) ? Yii::$app->dropdown->getRecords('dcs_union_login_type')['data'][$model->login_type] : '') : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'department',
                            'value' => Yii::$app->general->getforeignkey($model->departmentCode, 'department'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'wef_date',
                            'value' => Yii::$app->controls->view_date($model->wef_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'designation_code',
                            'value' => Yii::$app->general->getforeignkey($model->designationCode, 'designation_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'employee_id',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                        
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'primary_parent',
                            'value' => Yii::$app->general->getforeignkey($model->primaryParent, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'secondary_parent',
                            'value' => Yii::$app->general->getforeignkey($model->secondaryParent, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'created_at',
                            'value' => Yii::$app->controls->view_datetime($model->created_at, 'php:d-m-Y H:i'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' => Yii::$app->controls->view_datetime($model->updated_at, 'php:d-m-Y H:i'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'user_type_id',
                            'label' => 'User Type',
                            'value' => isset($model->userType) ? $model->userType->user_type : '',
                            'format' => 'raw',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                // [
                //     'columns' => [
                //         [
                //             'attribute' => 'bind_to_ip',
                //             'visible' => User::hasPermission('bindUserToIp'),
                //             'valueColOptions' => ['style' => 'width:30%']
                //         ],
                //         [
                //             'attribute' => 'registration_ip',
                //             'value' => Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target" => "_blank"]),
                //             'format' => 'raw',
                //             'visible' => User::hasPermission('viewRegistrationIp'),
                //             'valueColOptions' => ['style' => 'width:30%']
                //         ]
                //     ],
                // ],
                [
                    'columns' => [
                        [
                            'label' => yii::t('app', 'Roles'),
                            'value' => implode('<br/>', (array) ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description')),
                            'visible' => User::hasPermission('viewUserRoles'),
                            'format' => 'raw',
                            'valueColOptions' => ['style' => 'width:30%']
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
                            'valueColOptions' => [
                                'style' => User::hasPermission('viewUserRoles') ? 'width:30%' : 'width:80%'
                            ],
                        ]
                    ],
                ],
            ];

            // View file rendering the widget
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="true">
            <div class="form-group">
                <?= GhostHtml::a(yii::t('app', 'edit'), ['update', 'id' => $model->id], ['class' => 'btn-login btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+e']) ?>
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