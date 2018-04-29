<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\organisation\models\TblDcsBmcSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?><?php

$attribute = [
    ['attribute' => 'from_km','value'=>'from_km', 'vAlign' => 'middle',],
    ['attribute' => 'from_qty','value'=>'from_qty', 'vAlign' => 'middle',],
    ['attribute' => 'to_km','value'=>'to_km', 'vAlign' => 'middle',],
    ['attribute' => 'to_qty','value'=>'to_qty', 'vAlign' => 'middle',],
    ['attribute' => 'value','value'=>'value', 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'sub-center-bmc-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-record' => function ($url, $dcsmodel) {
                $options = ['data-name' => isset($dcsmodel->subCenterCode)?$dcsmodel->subCenterCode->sub_center_name:'', 'data-val' => $dcsmodel->subcenter_code,'title'=>'View'];
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/organisation/tbl-dcs-bmc/view','id'=>$dcsmodel->bmc_code], $options);
        },
        'edit' => function ($url, $dcsmodel) {

                $options = ['data-name' => isset($dcsmodel->subCenterCode)?$dcsmodel->subCenterCode->sub_center_name:'', 'data-val' => $dcsmodel->subcenter_code,'title'=>'Update'];
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/organisation/tbl-dcs-bmc/update','id'=>$dcsmodel->bmc_code, 'dcs' => $dcsmodel->dcs_code,'dcsname'=> isset($dcsmodel->dcsCode)?$dcsmodel->dcsCode->dcs_name:'','subcenter'=> $dcsmodel->subcenter_code,'subname'=> isset($dcsmodel->subCenterCode)?$dcsmodel->subCenterCode->sub_center_name:''], $options);
        },
        'delete' => ['option' => 'capacity,bmc_code,organisation/tbl-dcs-bmc/delete'],

    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
