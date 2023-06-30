<?php

use yii\helpers\Html;
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
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_type', 'label' => Yii::t('app', 'Type'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_code', 'value' => 'customer_code', 'filter' => FALSE],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'customer_code_ex');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'ref_code');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Customer Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerCode, 'customer_name');
        }, 'filter' => FALSE],
    [
        'attribute' => 'from_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->from_date);
},
    ],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'customer-deactivation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-detai' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/organisation/tbl-customer-deactive/view', 'id' => $model->customer_code], $options);
        },
                'active' => function ($url, $model) {
            $class = !empty($model->to_date) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Active', 'class' => 'customer-active ' . $class, 'data-customer_deactive_code' => $model->customer_deactive_code];
            return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/organisation/tbl-customer-deactive/activate-customer'], $options);
        },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div id="CustomerActive"></div>
        <?php
        $script = " $(document).ready(function(){
        $(document).on('click','.customer-active',function(e){
//            $('#pageloader').show();
//            $('#loadercontent').show();
            var customer_deactive_code= $(this).attr('data-customer_deactive_code');
            $.ajax({
                type: 'get',
                url: '" . Url::to(['/organisation/tbl-customer-deactive/activate-customer']) . "',
                data:{'customer_deactive_code':customer_deactive_code},
                success: function(data) {     
                    $('#CustomerActive').html(data);
                    $('#CustomerActiveModal').modal('toggle');    
//                    $('#loadercontent').hide();
//                    $('#pageloader').hide();
                },    
                error: function(data) {    
//                    $('#loadercontent').hide();
//                    $('#pageloader').hide();
                }
            });
        });
    });";
        $this->registerJs($script, View::POS_END, 'customer-active');
        ?>