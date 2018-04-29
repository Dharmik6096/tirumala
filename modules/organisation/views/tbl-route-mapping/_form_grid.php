<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
$request = Yii::$app->request->queryParams;
$cunit=!empty($request['TblRouteMappingSearch']['unit'])?$request['TblRouteMappingSearch']['unit']:'';
?>
<div class="grid-search">
    <?php
    
    echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
    'route_code',
    //'morning_start_time',
    //'morning_end_time',
    //'route_length_kms',
    'route_name',
//    'local_name',
    ['attribute' => 'local_name','filter'=>false],
    ['attribute' => 'morning_start_time',
        'value' => 'morning_start_time',
        'filter' => false],
    ['attribute' => 'morning_end_time',
        'value' => 'morning_end_time',
        'filter' => false],
    ['attribute' => 'evening_start_time',
        'value' => 'evening_start_time',
        'filter' => false, 'visible' => false],
    ['attribute' => 'evening_end_time',
        'value' => 'evening_end_time',
        'filter' => false, 'visible' => false],
    'route_type',
    /*['attribute' => 'from_dest', 'value' => function($model) {
            return $model->getDestinationName($model->from_type, $model->from_dest);
        }, 'visible' => true, 'filter' => false],*/
    ['attribute' => 'to_dest', 'value' => function($model) {
            return $model->getDestinationName($model->to_type, $model->to_dest);
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'vehicle_type_code', 'value' => 'vehicleType.vehicle_type_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'capacity', 'value' => function($model) use ($cunit){
            return $model->calcCapacity($cunit);
    }, 'visible' => true, 'filter' => false],
    [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false,'filter'=>false],
        
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
    'value' => function($model) {
        $detail = Yii::$app->general->getDefaultContactDetail($model->route_code,'routeMapping');
        isset($detail->firstname) ? $detail = $detail->firstname.' '.$detail->lastname.' '.$detail->surname : $detail = '';
        return $detail;
    }   
    ],
    ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
    'value' => function($model) {
        $detail = Yii::$app->general->getDefaultContactDetail($model->route_code,'routeMapping');
        isset($detail->local_firstname) ? $detail = $detail->local_firstname.' '.$detail->local_lastname.' '.$detail->local_surname : $detail = '';
        return $detail;
    }   
    ],
    ['label' => 'Email', 'visible' => false, 'filter' => false,
    'value' => function($model) {
        $detail = Yii::$app->general->getDefaultContactDetail($model->route_code,'routeMapping');
        isset($detail->email) ? $detail = $detail->email : $detail = '';
        return $detail;
    }   
    ],
    ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
    'value' => function($model) {
        $detail = Yii::$app->general->getDefaultContactDetail($model->route_code,'routeMapping');
        isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
        return $detail;
    }   
    ],
    ['label' => 'Department', 'visible' => false, 'filter' => false,
    'value' => function($model) {
        $detail = Yii::$app->general->getDefaultContactDetail($model->route_code,'routeMapping');
        isset($detail->department) ? $detail = $detail->department : $detail = '';
        return $detail;
    }   
    ],
];

$grid_option = [
    'id' => 'route-mapping-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'mapping' => function ($url, $model) {
            $options = ['data-name' => $model->route_name, 'data-val' => $model->route_code, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Route Source'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-route-mapping/map-route-source', 'id' => $model->route_code], $options);
        },
        'delete' => ['option' => 'route_name,route_code,tbl-route-mapping/delete'],
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->route_name, 'data-val' => $model->route_code, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Contact Details'];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/organisation/tbl-route-mapping/contact-details', 'id' => $model->route_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>