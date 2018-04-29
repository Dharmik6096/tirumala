<?php 
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="grid-search large-search">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    //['attribute' => 'svc'],
    //['attribute' => 'usr'],
    //['attribute' => 'pswd'],
    ['attribute' => 'cp'],
    ['attribute' => 'imei'],
    ['attribute' => 'mcc'],
    ['attribute' => 'cp_code'],
    ['attribute' => 'census_code'],
    //['attribute' => 'vendor_id'],
    [
    'attribute' => 'date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
        'value' => function($model) {
        return Yii::$app->controls->view_date($model->date);
    }],
    ['attribute' => 'time','filter'=>false],
    ['attribute' => 'file_name'],
];

$grid_option = [
    'id' => 'acknowledgement-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        //'update' => true,
        //'delete' => ['option' => 'usr,id,bipl-change-acknowledgement/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
