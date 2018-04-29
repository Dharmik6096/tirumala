<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="grid-search">
<?php
if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions')))>1)
    echo $this->render('_search', ['model' => $searchModel]); 
?>
</div>

<?php

$attribute = [    
    ['attribute' => 'formula_description',],
    ['attribute' => 'union_code','value'=>'unionCode.union_name','width'=>'150px','filter'=>false],
    [
        'attribute' => 'wef_date','width'=>'200px',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'milkTypeCode.animal_type_name', 
        'filter' => Html::activeTextInput($searchModel, 'milk_type_code', ['class' => 'form-control ']),'width'=>'200px'],
    ['attribute' => 'rateType.rate_type','filter' => Html::activeTextInput($searchModel, 'rate_type_code', ['class' => 'form-control ']),'width'=>'200px'],
    
];

$grid_option = [
    'id' => 'formula-list',
    'attributes' => $attribute,
    'active_column' => true,
//    'actions' => [
//        'delete' => ['option' => 'formula,formula_code,formula-master/delete'],
//    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
