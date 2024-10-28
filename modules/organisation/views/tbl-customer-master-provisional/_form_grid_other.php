<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'process_name', 'filter' => false, 'visible' => true],
    ['attribute' => 'total_count', 'filter' => false, 'visible' => true],
    ['attribute' => 'approved_count', 'filter' => false, 'visible' => true],
];


$grid_option = [
    'id' => 'approved-attachment-details-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
    'report' => function ($url,$model) use ($searchModel) {
        $options = [
            'title' => Yii::t('app', 'Download Report'), 
            'class' => ($model['approved_count'] > 0) ? '' : 'disabled',
        ];
        return GhostHtml::a('<i class="fa fa-file-o"></i>', [
            '/misreports/reports/approved-attachment-details','ReportsModel' => [
                'from_date' => $searchModel->from_date,
                'to_date' => $searchModel->to_date,
                'report_type' => $model['table_name'],
                'output_type' => 'DOWNLOAD',
            ],
            'html' => 'html'
        ], $options);
    }
]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);