<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'transporter_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->transporter, 'transporter_name');
        }, 'filter' => false],
        ['attribute' => 'vehicle_code', 'label' => Yii::t('app', 'Vehicle No.'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }, 'filter' => false],
        ['attribute' => 'trip_code'],
        ['attribute' => 'transaction_datetime', 'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->transaction_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => false],
        ['attribute' => 'status'],
        ['attribute' => 'remarks'],
];
foreach ($config_list as $config) {
    $attribute[] = [
        'attribute' => 'config_code', 'label' => Yii::t('app', $config->config_name),
        'value' => function ($model) use ($config) {
            $model->config_code = $config->config_code;
            $configResult = $model->configResult;
            return !empty($configResult) ? (!empty($configResult->configResultCode->config_result) ? $configResult->configResultCode->config_result : $configResult->config_result) : '';
        }
    ];
}

$grid_option = [
    'id' => 'vehicle-qa-inspection-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
//        'delete' => ['option' => 'vehicle_qa_inspection_code,vehicle_qa_inspection_code,tbl-vehicle-qa-inspection/delete'],
        'deactive' => function ($url, $model) {
            $class = $model->status != 'pending' ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Deactivate', 'class' => 'deact-qa ' . $class, 'data-val' => $model->vehicle_qa_inspection_code];
            return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/tankermovement/tbl-vehicle-qa-inspection/close-qa-inspection'], $options);
        },
    ],
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function() {
    $(document).on('click','.deact-qa',function(e){
    var id= $(this).attr('data-val');
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to close status ?</span></div></div>',
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
                            url: '" . Url::to(['close-qa-inspection']) . "',
                            data:{'id':id},
                            success: function(data) {
                                var obj1 = $.parseJSON(data);
                                if (obj1.status == 'success')
                                {
                                    $.pjax.reload({container: '#vehicle-qa-inspection-detail-list'});
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                }
                                else if (obj1.status == 'error'){
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
$this->registerJs($script, View::POS_END, 'qa-inspection-list');
