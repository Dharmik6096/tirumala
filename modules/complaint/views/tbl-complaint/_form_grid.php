<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$complaint_type = array('None' => 'None','Network' => 'Network', 'Modem' => 'Modem', 'Eco' => 'Eko', 'Wing scale' => 'Wing scale');
$status = array('Created' => 'Created', 'Processing' => 'Processing', 'Resolved' => 'Resolved');
?>

<div class="grid-search">
    <?php // $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    'complaint_code',
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'visible' => true, 'filter' => false],
    ['attribute' => 'contact_person'],
    ['attribute' => 'date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->date);
    }],
    ['attribute' => 'complaint_type', 'filter' => $complaint_type],
    ['attribute' => 'affects_data', 'filter' => array('1'=>'Yes','0'=>'No'), 'visible' => false,
        'value' => function($model) {
            return $model->affects_data == 1 ? 'Yes' : 'No';
    }],
    ['attribute' => 'attachment', 'visible' => false, 'filter' => false],
    ['attribute' => 'status', 'filter' => $status],
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $url = Url::to(['tbl-complaint-activity/create', 'id' => $model->complaint_code]);
            $status = $model->getComplaintStatus($model->complaint_code);
            $class=(!isset($status))?'':'link-disable';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit','class'=>''.$class,'data-val'=>$model->complaint_code,'data-name'=>''];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>