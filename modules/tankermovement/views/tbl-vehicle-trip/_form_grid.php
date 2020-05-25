<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [

    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'transporter_code',
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
            ['attribute' => 'bmc_code',
                'label' => Yii::t('app', 'BMC Code'),
                'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_name',
                'label' => Yii::t('app', 'BMC Name'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            [
                'attribute' => 'transaction_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }],
            ['attribute' => 'trip_code'],
            ['attribute' => 'parsing_no',
                'label' => Yii::t('app', 'Vehicle No.'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
                }],
            ['attribute' => 'driver_name', 'label' => Yii::t('app', 'Driver Name'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_name');
                }],
            ['attribute' => 'driver_contact_no', 'label' => Yii::t('app', 'Driver Contact No.'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_contact_no');
                }],
            ['attribute' => 'challan_no'],
            ['attribute' => 'bmc_detail'],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'total_qty'],
            ['attribute' => 'rejected_count'],
            ['attribute' => 'grn_no'],
            ['attribute' => 'trip_mode'],
            ['attribute' => 'trip_status'],
        ];

        $grid_option = [
            'id' => 'vehicle-trip-list',
            'attributes' => $attribute,
            'active_column' => FALSE,
            'actions' => [
                'view' => TRUE,
                'generate-challan' => function ($url, $model) {
                    $disable = ($model->trip_status == 'open') ? FALSE : TRUE;
                    if ($disable) {
                        return GhostHtml::a('<i class="fa fa-cog"></i>', ['/tankermovement/tbl-vehicle-trip/generate-challan'], ['class' => 'disabled']);
                    } else {
                        $options = [ 'title' => Yii::t('app', 'Generate Challan'), 'data-toggle' => 'tooltip', 'data-placement' => 'top',
                            'data-original-title' => Yii::t('app', 'Generate Challan')
                        ];
                        return GhostHtml::a('<i class="fa fa-cog"></i>', ['/tankermovement/tbl-vehicle-trip/generate-challan',
                                    'id' => $model->vehicle_trip_code]
                                        , $options);
                    }
                },
                        'print-challan' => function ($url, $model) {
                    $disable = (in_array($model->trip_status, ['tankerfull', 'closed'])) ? FALSE : TRUE;
                    if ($disable) {
                        return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/tankermovement/tbl-vehicle-trip/print-challan'], ['class' => 'disabled']);
                    } else {

                        $options = ['target' => '_blank', 'title' => Yii::t('app', 'Print Challan'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'Print Challan')];
                        return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/tankermovement/tbl-vehicle-trip/print-challan', 'id' => $model->trip_code], $options);
                    }
                },
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
