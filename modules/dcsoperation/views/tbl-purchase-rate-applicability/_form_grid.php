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
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    [
        'attribute' => 'wef_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name'],            
    ['attribute' => 'shift_code','value'=>'shiftCode.shift'],
    ['attribute' => 'union_code','value'=>'purchaseRateCode.unionCode.union_name'],

];

$grid_option = [
    'id' => 'purchase-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>