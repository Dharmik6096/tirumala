<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'transporter_type', 'value' => function($model) {
            return isset($model->transporter_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->transporter_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('transporter_type', $searchModel, 'transporter_type'),],
    ['attribute' => 'bill_no'],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name');
        }, 'filter' => FALSE],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        },],
    [
        'label' => 'Period',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->from_date) . ' To ' . Yii::$app->controls->view_date($model->to_date);
}],
    ['attribute' => 'no_of_days'],
    ['attribute' => 'billing_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billingTypeCode, 'billing_type') . ($model->billing_type_code == 2 ? ($model->is_day_wise == 1 ? '(Day Wise)' : '(Month Wise)') : '');
        }
    ],
    ['attribute' => 'total_kms'],
    ['attribute' => 'total_vts_kms'],
    ['attribute' => 'total_least_kms'],
    ['attribute' => 'total_qty'],
    ['attribute' => 'total_rejected_qty'],
    ['attribute' => 'qty_amount'],
    ['attribute' => 'total_amount'],
    ['attribute' => 'total_deduction'],
    ['attribute' => 'total_rejected_amount'],
    ['attribute' => 'total_penalty_amount'],
    ['attribute' => 'total_addition'],
    ['attribute' => 'net_amount'],
    ['attribute' => 'adjust_amount'],
    ['attribute' => 'final_amount'],
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
        'lock-bill' => function ($url, $model) {
            $icon = ($model->status == 'processed') ? '<i class="fa fa-unlock"></i>' : '<i class="fa fa-lock"></i>';
            $class = ($model->status == 'processed') ? '' : 'disabled';
            $url = Url::to(['lock-bill']);
            $name = Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name') . '(' . Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date) . ')';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'Lock Bill'), 'class' => 'lock-bill ' . $class, 'data-val' => $model->transporter_payment_code, 'data-name' => $name, 'data-url' => $url];
            return GhostHtml::a_alert($icon, ['/payment/tbl-transporter-payment/lock-bill'], $options);
        },
                'print-bill' => function ($url, $model) {
            $options = ['target' => '_blank', 'title' => Yii::t('app', 'View Bill'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View Bill')];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/payment/tbl-transporter-payment/view-bill', 'union_code' => $model->union_code, 'transporter_code' => $model->transporter_code, 'from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_type' => $model->transporter_type, 'route_code' => $model->route_code], $options);
        },
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
        