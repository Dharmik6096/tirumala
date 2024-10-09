<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'translate_table_name', 'filter' => false, 'visible' => true],
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
$script = '
    $(".kv-panel-before").hide();
';
$this->registerJs($script, View::POS_END, 'approved-attachment-details-list');


// http://localhost/VRS_V5_ISSUE_3/bWlzcmVwb3J0cy9yZXBvcnRzL21lbWJlci1tYXN0ZXI?ReportsModel%5Bunion_code%5D=001&ReportsModel%5Bplant_code%5D=&ReportsModel%5Bmcc_code%5D=&ReportsModel%5Boutput_type%5D=DOWNLOAD&html=html