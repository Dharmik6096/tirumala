<?php

use app\modules\usermanagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
 * @var webvimark\modules\UserManagement\models\rbacDB\search\AuthItemGroupSearch $searchModel
 */
$this->title = UserManagementModule::t('back', 'Permission groups');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$attribute = [
    [
        'attribute' => 'name',
        'value' => function($model) {
            return Html::a($model->name, ['view', 'id' => $model->code], ['data-pjax' => 0]);
        },
                'format' => 'raw',
            ],
            'code',
        ];

        $grid_option = [
            'id' => 'permission-group-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'actions' => [
                'view' => true,
                'update' => true,
                'delete' => ['option' => 'code,name,/user-management/auth-item-group/delete'],
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?> 

        <?php

//        $script = "
//            $('#permission-group-list').on('click','.permission-group-record',function(e){
//                    var id= $(this).attr('data-val');
//                    var name = $(this).attr('data-name');
//                    bootbox.confirm({
//                        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to delete this permision group \"'+name+'\"?</span></div></div>',
//                        buttons: {
//                            'cancel': {
//                                            label: 'Cancel',
//                                            className: 'btn btn-danger'
//                              },
//                            'confirm': {
//                                            label: 'Ok',
//                                            className: 'btn btn-primary'
//                             }
//                        },
//                        callback: function(result) {
//                            if (result) {
//                              $('#loader').show();
//                                 $.ajax({
//                                        type: 'post',
//                                        url: '" . Yii::$app->request->baseUrl . "/index.php?r=user-management/auth-item-group/delete&id=' + id,
//                                        //data: 'id='+id,
//                                        success: function(data) {
//
//                                            var obj1 = $.parseJSON(data);
//                                            if (obj1.status == 'success')
//                                            {
//                                                $.pjax.reload({container: '#permission-group-list'});
//                                                bootbox.alert(obj1.msg);
//                                            }
//                                            else if (obj1.status == 'error'){
//                                                bootbox.alert(obj1.msg);
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
//        $this->registerJs($script, View::POS_END, 'delete-manager');
        ?>