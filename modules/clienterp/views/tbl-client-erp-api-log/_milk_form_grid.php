<?php

use app\modules\globalmaster\models\TblAnimalType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php
$attribute = [
    ['attribute' => 'entry_type', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('entry_type', $searchModel, 'entry_type'),
        'value' => function ($model) {
            return $model->entry_type;
        },],
    ['attribute' => 'grn_no', 'filter' => false],
    ['attribute' => 'challan_no', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
    }, 'vAlign' => 'middle', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
    }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'chamber_no'],
    ['attribute' => 'chamber_quantity', 'filter' => Html::activeTextInput($searchModel, 'chamber_quantity', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_chamber_quantity', $operator, ['class' => 'form-control'])],
    ['attribute' => 'fat', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle'],
    ['attribute' => 'density', 'visible' => false],
    ['attribute' => 'protein', 'visible' => false],
    ['attribute' => 'lactose', 'visible' => false],
    ['attribute' => 'freezing_point', 'visible' => false],
    ['attribute' => 'mbrt', 'visible' => false],
    ['attribute' => 'acidity', 'visible' => false],
    ['attribute' => 'status', 'value' => function($model){
        return $model->status == 2 ? 'SUCCESS' : 'ERROR';
    }, 'filter' => array(2=>'SUCCESS',3=>'ERROR')],
];



$grid_option = [
    'id' => 'milk-vehicle-entry-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = ($model->status == '2') ? 'disabled' : '';
            $options = ['title' => Yii::t('app', 'Repush'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-share-square-o"></i>', ['/clienterp/tbl-client-erp-api-log/repush', 'id' => $model->milk_vehicle_entry_transaction_code], $options);
        },
        'log_view' => function ($url, $model) {
            $id = $model->source_org_code.'_'.$model->source_org_type.'_'.$model->trip_code;
            $options = ['data-val' => $model->milk_vehicle_entry_transaction_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Logs'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/clienterp/tbl-client-erp-api-log/view', 'id' => $id, 'erp_process_name' => 2], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
