
<?php 
use kartik\grid\GridView;
use yii\helpers\Html;
//$dataProvide = $model->vehicleDetail($model);
$models = new app\modules\payment\models\TblVehiclePayment();
$dataProvide = $models->vehicleDetail($model);
$models->attributes = $model->attributes;

?>

<?php
$attributes = [
    ['class' => 'kartik\grid\SerialColumn'],
    ['class' => 'yii\grid\CheckboxColumn', 
//        'name' => '['.$models->transporter_code.']checkbox',
        'checkboxOptions' => function ($data,$key, $index) use ($form,$models) {
                return ['value' => $data['vehicle_code'],'checked' => true];
            },],
    [
        'attribute' => 'vehicle_code',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']vehicle_code', ['value' => $data['vehicle_code']]).$data['vehicle_code'];
        },
    ],
    [
        'attribute' => 'transporter_name',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']union_code', ['value' => $models['union_code']]).Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']from_date', ['value' => $models['from_date']]).Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']to_date', ['value' => $models['to_date']]).Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']transporter_code', ['value' => $data['transporter_code']]).$data['transporter_name'];
        },
    ],
    [
        'attribute' => 'bmc_name',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']bmc_code', ['value' => $data['bmc_code']]).$data['bmc_name'];
        },
    ],
    [
        'attribute' => 'coll_qty',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']coll_qty', ['value' => $data['coll_qty']]).$data['coll_qty'];
        },
    ],
    [
        'attribute' => 'coll_kg_fat',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']coll_kg_fat', ['value' => $data['coll_kg_fat']]).$data['coll_kg_fat'];
        },
    ],
    [
        'attribute' => 'coll_kg_snf',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']coll_kg_snf', ['value' => $data['coll_kg_snf']]).$data['coll_kg_snf'];
        },
    ],
    [
        'attribute' => 'disp_qty',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']disp_qty', ['value' => $data['disp_qty']]).$data['disp_qty'];
        },
    ],
    [
        'attribute' => 'disp_kg_fat',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']disp_kg_fat', ['value' => $data['disp_kg_fat']]).$data['disp_kg_fat'];
        },
    ],
    [
        'attribute' => 'disp_kg_snf',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']disp_kg_snf', ['value' => $data['disp_kg_snf']]).$data['disp_kg_snf'];
        },
    ],
    [
        'attribute' => 'no_of_days',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => 'raw',
        'value' => function ($data,$key, $index) use ($form,$models) {
            return Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']no_of_days', ['value' => $data['no_of_days']]).$data['no_of_days'];
        },
    ],
                            
                            
                            
    ['attribute' => 'total_amount',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => Yii::$app->general->CurrencyFormat(),
        'value' => function ($data,$key, $index) use ($form,$models) {
            echo Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']total_amount', ['value' => $data['total_amount']]);
            return $data['total_amount'];
        },
    ],
    ['attribute' => 'final_amount',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => Yii::$app->general->CurrencyFormat(),
        'value' => function ($data,$key, $index) use ($form,$models) {
            echo Html::activeHiddenInput($models, '['.$data['transporter_code'].']['.$index.']final_amount', ['value' => $data['final_amount']]);
            return $data['total_amount'];
        },
    ],
];

echo GridView::widget([
    'id' => 'vehicle-summary-grid-'.$index,
    'dataProvider' => $dataProvide,
    'layout' => '{items}{pager}',
    'columns' => $attributes, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
]);
?>