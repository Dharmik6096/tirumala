<?php

use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'plant_code', 'label' => 'Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
    ['attribute' => 'chamber_no', 'label' => Yii::t('app', 'Com No.'), 'filter' => false],
    ['attribute' => 'arrival_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->arrival_datetime);
        }, 'filter' => false],
    ['attribute' => 'lot_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->lot_datetime);
        }, 'filter' => false],
    ['attribute' => 'lot_no', 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'status_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_datetime);
        }, 'filter' => false],
    ['attribute' => 'acidity', 'filter' => false],
    ['attribute' => 'mbrt', 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    ['attribute' => 'clr', 'filter' => false],
    ['attribute' => 'water', 'filter' => false],
    ['attribute' => 'density', 'filter' => false],
    ['attribute' => 'protein', 'filter' => false],
    ['attribute' => 'lactose', 'filter' => false],
    ['attribute' => 'freezing_point', 'filter' => false],
    ['attribute' => 'temp', 'filter' => false],
    ['attribute' => 'tested_by', 'filter' => false],
    ['attribute' => 'verified_by', 'filter' => false],
    ['attribute' => 'sample_datetime', 'label' => Yii::t('app', 'Sample Datetime'), 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->sample_datetime);
        }, 'filter' => false],
    ['attribute' => 'record_status', 'value' => function($model) {
            return isset($model->record_status) ? Yii::$app->dropdown->getRecords('record_status')['data'][$model->record_status] : '';
        }, 'filter' => false],
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