<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>
<?php

$attribute = [
        ['attribute' => 'eipl_code'],
        ['attribute' => 'mobile_no'],
        ['attribute' => 'master_type'],
        ['attribute' => 'master_code'],
        ['attribute' => 'login_type'],
        ['attribute' => 'module_type'],
        ['attribute' => 'module_code'],
        ['attribute' => 'otp_code'],
        ['attribute' => 'imei_no'],
        ['attribute' => 'device_id', 'visible' => FALSE],
        ['attribute' => 'access_token'],
        ['attribute' => 'version_no'],
        ['attribute' => 'device_detail'],
];

$grid_option = [
    'id' => 'eipl-app-login-list',
    'attributes' => $attribute,
    'active_column' => True,
    'actions' => [
        'active' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Active', 'class' => 'in-active ' . $class, 'data-app_login_id' => $model->app_login_id];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/webservice/eipl/tbl-eipl-app-login/in-active-app'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.in-active',function(e){
    var app_login_id= $(this).attr('data-app_login_id');

    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Deactivate App ?</span></div></div>',
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
                        url: '" . Url::to(['/webservice/eipl/tbl-eipl-app-login/in-active-app']) . "',
                        data:{'app_login_id':app_login_id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#eipl-app-login-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
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
    });
   
   
});";
$this->registerJs($script, View::POS_END, 'in-active-app');
?>
