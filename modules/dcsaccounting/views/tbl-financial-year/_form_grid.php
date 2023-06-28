<?php
use yii\helpers\Html;
use app\components\GeneralFunctions;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]);   ?>
</div>
 <?php
$attribute = [
    ['attribute' => 'id', 'vAlign' => 'middle'],
    ['attribute' => 'code', 'vAlign' => 'middle'],
    [
        'attribute' => 'starting_date',
        'vAlign' => 'middle',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
      //  'filter' => Yii::$app->controls->search_date($searchModel,'starting_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->starting_date);
}],
      [
        'attribute' => 'ending_date',
        'vAlign' => 'middle',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->ending_date);
}],   
   
];

$grid_option = [
    'id' => 'financial-year-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => false,
        'edit'=> function ($url, $model)  {
                $disable = ($model->checkEdit()==0)?'':'disabled';
                $options = ['class'=>$disable];
                
                return GhostHtml::a('<span title="Edit"><i class="fa fa-pencil-alt"></i></span>', ['/dcsaccounting/tbl-financial-year/update','id'=>$model->id], $options);
                
        },
        'delete' => ['option' => 'code,id,/dcsaccounting/tbl-financial-year/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
