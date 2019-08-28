<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'interfacing_device_code', 'vAlign' => 'middle',],
    ['attribute' => 'baud_rate', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'baud_rate', $searchModel->getBaurdRate(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->baud_rate)?$model->getBaurdRate()[$model->baud_rate]:''; },],
    ['attribute' => 'bit_rate', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'bit_rate', $searchModel->getBitRate(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->bit_rate)?$model->getBitRate()[$model->bit_rate]:''; },],
    ['attribute' => 'device_name', 'vAlign' => 'middle',],
    ['attribute' => 'device_type', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'device_type', $searchModel->getDeviceType(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->device_type)?$model->getDeviceType()[$model->device_type]:''; },],
    ['attribute' => 'discard_char', 'vAlign' => 'middle',],
    ['attribute' => 'incoming_data_type', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'incoming_data_type', $searchModel->getIncomingType(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->incoming_data_type)?$model->getIncomingType()[$model->incoming_data_type]:''; },],
    ['attribute' => 'device_manufacturer_id','value'=>'deviceManufacturer.manufacturer', 'vAlign' => 'middle',],
    ['attribute' => 'is_snf', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'is_snf', [1=>'Yes',0=>'No'],['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->is_snf==1)?'Yes':'No';},'visible'=>false,'filter'=>false,'value' => function($model) {return ($model->is_snf==1)?'Yes':'No';}],
    ['attribute' => 'length', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'parity', 'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'parity', $searchModel->getParity(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->parity)?$model->getParity()[$model->parity]:''; },'visible'=>false,'filter'=>false],
    ['attribute' => 'reading_type', 'vAlign' => 'middle',
        'value' => function($model) {return ($model->reading_type)?$model->getReadingType()[$model->reading_type]:''; },
        'visible'=>false,
        'filter'=>false],
    ['attribute' => 'reg_expression', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'split_char', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'start_char', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'stop_bit', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'tare', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
];

$grid_option = [
    'id' => 'interfacing-device-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'device_name,interfacing_device_code,tbl-interfacing-device/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>