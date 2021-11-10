<?php

use yii\helpers\Html;
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
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'dcs_code', 'value' => 'dcs_code', 'filter' => FALSE],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => FALSE],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => FALSE],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => FALSE],
    [
        'attribute' => 'from_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->from_date);
},
//                'label' => Yii::t('app', 'From Date')
    ],
    ['attribute' => 'remarks'],
//    [
//        'attribute' => 'to_date', 'filter' => true,
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->to_date);
//        }, 'label' => Yii::t('app', 'To Date')],
];

$grid_option = [
    'id' => 'dcs-deactivation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-detai' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/organisation/tbl-dcs-deactive/view', 'id' => $model->dcs_code], $options);
        },
                'active' => function ($url, $model) {
            $class = !empty($model->to_date) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Active', 'class' => 'dcs-active ' . $class, 'data-dcs_deactive_code' => $model->dcs_deactive_code];
            return GhostHtml::a_alert('<i class="fa fa-check"></i>', ['/organisation/tbl-dcs-deactive/activate-dcs'], $options);
        },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div id="DCSActive"></div>
        <?php
        $script = " $(document).ready(function(){
        $(document).on('click','.dcs-active',function(e){
//            $('#pageloader').show();
//            $('#loadercontent').show();
            var dcs_deactive_code= $(this).attr('data-dcs_deactive_code');
            $.ajax({
                type: 'get',
                url: '" . Url::to(['/organisation/tbl-dcs-deactive/activate-dcs']) . "',
                data:{'dcs_deactive_code':dcs_deactive_code},
                success: function(data) {     
                    $('#DCSActive').html(data);
                    $('#DCSActiveModal').modal('toggle');    
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
        $this->registerJs($script, View::POS_END, 'dcs-active');
        ?>