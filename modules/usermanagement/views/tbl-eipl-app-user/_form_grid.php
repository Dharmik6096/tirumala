<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;

$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$attribute = [
        ['attribute' => 'user_code'],
        ['attribute' => 'name'],
        ['attribute' => 'email'],
        ['attribute' => 'mobile_no'],
        ['attribute' => 'department', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->departmentCode, 'department');
        }],
];

$grid_option = [
    'id' => 'app-user-grid',
    'attributes' => $attribute,
    'active_column' => TRUE,
    'actions' => [
        'update' => true,
        'delete_user' => function ($url, $model) {
            $disable = $model->is_active == 1 ? '' : 'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Deactivate', 'data-val' => $model->eipl_app_user_code, 'data-name' => $model->name, 'class' => 'deact-user ' . $disable];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/usermanagement/tbl-eipl-app-user/deactivate-user'], $options);
        },
        'org-map' => function ($url, $model) {
            $disable = '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Organization', 'class' => $disable];
            return Html::a('<i class="fa fa-link"></i>', ['/usermanagement/tbl-eipl-app-user/organization-map', 'id' => $model->eipl_app_user_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-user',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
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
                        url: '" . Url::to(['deactivate-user']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#app-user-grid'});
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
$this->registerJs($script, View::POS_END, 'app-index-index');
