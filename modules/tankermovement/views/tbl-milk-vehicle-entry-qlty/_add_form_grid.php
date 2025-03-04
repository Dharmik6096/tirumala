<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'label' => (Yii::t('app', 'Plant Code')), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'plant_code');
        }, 'filter' => false],
    ['attribute' => 'plant_code', 'label' => (Yii::t('app', 'Plant')), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'chamber_no', 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'status_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_datetime);
        }, 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
];

$grid_option = [
    'id' => 'milk-vehicle-entry-qlty-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'close' => function ($url, $model) {
            if ($model->status == 'done') {
                $name = $model->chamber_no;
                $tripCode = $model->trip_code;
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Reset', 'class' => 'reset-qlty', 'data-val' => $model->milk_vehicle_entry_qlty_code, 'data-name' => $name, 'trip-code' => $tripCode];
                return GhostHtml::a_alert('<i class="fa fa-refresh"></i>', ['/tankermovement/tbl-milk-vehicle-entry-qlty/reset-qlty'], $options);
            }
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.reset-qlty',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    var tripCode = $(this).attr('trip-code');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Reset Quality Detail For '+tripCode+' - '+name+'?</span></div></div>',
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
                        url: '" . Url::to(['reset-qlty']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success') {
                                $.pjax.reload({container: '#milk-vehicle-entry-qlty-grid'});
                                $.pjax.reload({container: '#milk-vehicle-entry-qlty-form'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            } else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
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
$this->registerJs($script, View::POS_END, 'bmc-tanker-dispatch-deactive-grid-index');
?>