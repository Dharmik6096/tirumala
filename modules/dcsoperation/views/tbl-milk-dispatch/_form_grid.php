<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'challan_no','value'=>'challan_no',],
    ['attribute' => 'milk_dispatch_code','value'=>'milk_dispatch_code',],
    [
        'attribute' => 'from_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift','value'=>'from_shift',],
    [
        'attribute' => 'to_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
     ['attribute' => 'to_shift','value'=>'to_shift',],
    ['attribute' => 'destination_code','value'=>'destination_code',],
    ['attribute' => 'acidity','value'=>'acidity','visible' => false,'filter'=>false],
    ['attribute' => 'avg_clr','value'=>'avg_clr','visible' => false,'filter'=>false],
    ['attribute' => 'avg_fat','value'=>'avg_fat','visible' => false,'filter'=>false],
    ['attribute' => 'avg_snf','value'=>'avg_snf','visible' => false,'filter'=>false],
    ['attribute' => 'chamber_no','value'=>'chamber_no','visible' => false,'filter'=>false],
    ['attribute' => 'density','value'=>'density','visible' => false,'filter'=>false],
    ['attribute' => 'dip_stick_reading_closing','value'=>'dip_stick_reading_closing','visible' => false,'filter'=>false],
    ['attribute' => 'dip_stick_reading_opening','value'=>'dip_stick_reading_opening','visible' => false,'filter'=>false],
    ['attribute' => 'dispatch_qty','value'=>'dispatch_qty','visible' => false,'filter'=>false],
    ['attribute' => 'head_load_kms','value'=>'head_load_kms','visible' => false,'filter'=>false],
    ['attribute' => 'lactose','value'=>'lactose','visible' => false,'filter'=>false],
    ['attribute' => 'nos_of_can','value'=>'nos_of_can','visible' => false,'filter'=>false],
    ['attribute' => 'protein','value'=>'protein','visible' => false,'filter'=>false],
    ['attribute' => 'route_no','value'=>'route_no','visible' => false,'filter'=>false],
    ['attribute' => 'seal_no','value'=>'seal_no','visible' => false,'filter'=>false],
    ['attribute' => 'temp','value'=>'temp','visible' => false,'filter'=>false],
    [
        'attribute' => 'to_bmc_plant','visible' => false,'filter'=>false,
//        'filter' => Html::activeDropDownList($searchModel, 'to_bmc_plant', $searchModel->bmcArray(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return $model->getBmcPlant(); }
],
                [
        'attribute' => 'non_default_dispatch','visible' => false,'filter'=>false,
//        'filter' => Html::activeDropDownList($searchModel, 'non_default_dispatch', [1=>'True',0=>'False'],['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->non_default_dispatch==1)?'True':'False';}
],
    ['attribute' => 'vehicle_in_time','value'=>'vehicle_in_time','visible' => false,'filter'=>false,],
    ['attribute' => 'vehicle_no','value'=>'vehicle_no','visible' => false,'filter'=>false],
    ['attribute' => 'vehicle_out_time','value'=>'vehicle_out_time','visible' => false,'filter'=>false,],
    ['attribute' => 'water','value'=>'water','visible' => false,'filter'=>false],
    ['attribute' => 'milk_quality_type','value'=>'milkQualityType.milk_quality_type_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'milk_type','value'=>'milkType.animal_type_name','visible' => false,'filter'=>false],
                
    
];

$grid_option = [
    'id' => 'milk-dispatch-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>