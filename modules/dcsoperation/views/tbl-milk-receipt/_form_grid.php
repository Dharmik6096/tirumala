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
    ['attribute' => 'milk_receipt_code','value'=>'milk_receipt_code'],
    ['attribute' => 'challan_no','value'=>'challan_no'],
    [
        'attribute' => 'from_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'from_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift','value'=>'from_shift'],
    [
        'attribute' => 'to_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'to_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    ['attribute' => 'to_shift','value'=>'to_shift',],
    ['attribute' => 'amount','format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'nos_of_can','value'=>'nos_of_can','visible' => false,'filter'=>false],
    ['attribute' => 'rate','format' => Yii::$app->general->CurrencyFormat(),'visible' => false,'filter'=>false],
    ['attribute' => 'receipt_acidity','value'=>'receipt_acidity','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_clr','value'=>'receipt_clr','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_density','value'=>'receipt_density','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_fat','value'=>'receipt_fat','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_freezing_point','value'=>'receipt_freezing_point','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_lactose','value'=>'receipt_lactose','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_local_type','value'=>'receipt_local_type','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_org_code','value'=>'receipt_org_code','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_protein','value'=>'receipt_protein','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_qty','value'=>'receipt_qty','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_snf','value'=>'receipt_snf','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_temp','value'=>'receipt_temp','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_water','value'=>'receipt_water','visible' => false,'filter'=>false],
    ['attribute' => 'milk_quality_type_code','value'=>'milkQualityTypeCode.milk_quality_type_name','visible' => false,'filter'=>false],
    ['attribute' => 'milk_type','value'=>'milkType.animal_type_name','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_org_chilling_center','value'=>'receiptOrgChillingCenter.name','visible' => false,'filter'=>false],
    ['attribute' => 'receipt_org_id','value'=>'receiptOrg.dcs_name','visible' => false,'filter'=>false],
];

$grid_option = [
    'id' => 'milk-receipt-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>