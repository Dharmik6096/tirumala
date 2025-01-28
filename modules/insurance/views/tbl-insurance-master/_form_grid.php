<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [    
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => false],
     ['attribute' => 'insurance_description'],
    ['attribute' => 'insurance_start_date', 
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],'value' => function($model) {
        return Yii::$app->controls->view_date($model->insurance_start_date);
    }],
    ['attribute' => 'insurance_end_date', 
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],'value' => function($model) {
        return Yii::$app->controls->view_date($model->insurance_end_date);
    }],
    ['attribute' => 'member_min_age'],
    ['attribute' => 'member_max_age'],
    ['attribute' => 'status', 'filter' => true],
];

$grid_option = [
    'id' => 'insurance-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => function ($url, $model) {
            $class = $model->status == 'DRAFT' ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->insurance_master_code];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
