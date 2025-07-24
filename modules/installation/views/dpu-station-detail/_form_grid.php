<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;

$attribute = [


    ['attribute' => 'company_code', 'label' => Yii::t('app', 'Union'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }],
    ['attribute' => 'ref_code', 'label' => 'Dcs Name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
    ['attribute' => 'station_code'],
    ['attribute' => 'vendor_code'],
    ['attribute' => 'flag_value', 'value' => function($model) {
            return $model->flag_value == 1 ? 'Not Transfer' : 'Transfer';
        }],
    ['attribute' => 'flag_key'],
    [ 'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_datetime($model->created_at);
}],
    ['attribute' => 'transferred_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_datetime($model->transferred_datetime);
}],
    ['attribute' => 'transferred_by'],
];

$grid_option = [
    'id' => 'dpu-station-detail-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'force-sent' => function ($url, $model) {
            $name = $model->flag_value;
            $ref = Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            $options = [ 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Force Sent', 'class' => 'force-sent', 'data-val' => $model->id, 'data-name' => $name,'data-ref'=>$ref];
            return GhostHtml::a_alert('<i class="fa fa-upload"></i>', ['/installation/dpu-station-detail/force-sent', 'id' => $model->id], $options);
        },
            ]
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
        <?php

        $script = "
$(document).ready(function(){
    $(document).on('click','.force-sent',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    var ref = $(this).attr('data-ref');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Force Sent \"'+ref+'\"?</span></div></div>',
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
                        url: '" . Url::to(['force-sent']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#dpu-station-detail-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record  Updated.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not Updated.', timeout: 8000, style: 'errorbar'});
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
        $this->registerJs($script, View::POS_END, 'dpu-station-detail-index');

        