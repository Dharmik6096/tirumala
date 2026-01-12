<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE
    ],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE
    ],
        ['attribute' => 'transporter_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('transporter_type', $searchModel, 'transporter_type'),
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('transporter_type', $model, 'transporter_type');
        }
    ],
        ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }
    ],
        ['attribute' => 'route_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'ref_code');
        }, 'label' => Yii::t('app', 'Route Code')],
        ['attribute' => 'route_name', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }
    ],
        ['attribute' => 'transporter_name'],
        ['attribute' => 'parsing_no'],
        ['attribute' => 'bill_no'],
        [
        'label' => 'Period',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date) . ' To ' . Yii::$app->controls->view_date($model->to_date);
        }],
        ['attribute' => 'billing_method', 'visible' => FALSE],
        ['attribute' => 'no_of_days'],
        ['attribute' => 'total_kms'],
        ['attribute' => 'avg_rate'],
        ['attribute' => 'total_qty'],
        ['attribute' => 'rec_kg_fat'],
        ['attribute' => 'rec_kg_snf'],
        ['attribute' => 'qty_amount'],
        ['attribute' => 'fixed_rent'],
        ['attribute' => 'vehicle_average'],
        ['attribute' => 'fuel_consumption'],
        ['attribute' => 'fuel_rate'],
        ['attribute' => 'total_amount'],
        ['attribute' => 'fixed_amount'],
        ['attribute' => 'total_addition'],
        ['attribute' => 'total_deduction'],
        ['attribute' => 'net_amount'],
        ['attribute' => 'adjust_amount'],
        ['attribute' => 'final_amount'],
        ['attribute' => 'tds_amount'],
        ['attribute' => 'adjust_remark'],
        [
        'attribute' => 'payment_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->payment_date);
        }],
        ['attribute' => 'status'],
];


$grid_option = [
    'id' => 'transporter-payment-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'lock-bill' => function ($url, $model) {
            $icon = ($model->status == 'processed') ? '<i class="fa fa-unlock"></i>' : '<i class="fa fa-lock"></i>';
            $class = ($model->status == 'processed') ? '' : 'disabled';
            $url = Url::to(['lock-bill']);
            $name = $model->transporter_name  . '(' . Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date) . ')';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Lock Bill'), 'class' => 'lock-bill ' . $class, 'data-val' => $model->transporter_payment_code, 'data-name' => $name, 'data-url' => $url];
            return GhostHtml::a_alert($icon, ['/payment/tbl-transporter-payment/lock-bill'], $options);
        },
//                'print-bill' => function ($url, $model) {
//            $options = ['target' => '_blank', 'title' => Yii::t('app', 'View Bill'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'View Bill')];
//            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/payment/tbl-transporter-payment/view-bill', 'union_code' => $model->union_code, 'transporter_code' => $model->transporter_code, 'from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_type' => $model->transporter_type, 'route_code' => $model->route_code], $options);
//        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.lock-bill',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    var url= $(this).attr('data-url');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to lock bill for \"'+name+'\"?</span></div></div>',
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
                        url: url,
                        data:{'id':id},
                        success: function(data) {   
                                var obj1 = $.parseJSON(data);
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-success\'><i class=\'fa fa-check\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                $.pjax.reload({container: '#transporter-payment-grid'});                          
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
$this->registerJs($script, View::POS_END, 'tpt-payment-index');
