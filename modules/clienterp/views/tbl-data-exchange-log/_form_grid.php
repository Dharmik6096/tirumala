
<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>
<?php

$attribute = [
        ['attribute' => 'process_name', 'filter' => false],
        ['attribute' => 'update_key', 'label' => 'Application No', 'value' => function($model) {
            $updateKeyParts = explode('-', $model->update_key);
            return !empty($updateKeyParts[0]) ? $updateKeyParts[0] : '';
        }, 'filter' => true],
        ['attribute' => 'process_code', 'value' => function($model) {
            if (strtolower($model->process_name) == 'member provisional') {
                return Yii::$app->general->getforeignkey($model->memberProvisionalCode, 'member_name');
            } else {
                return Yii::$app->general->getforeignkey($model->memberProvisionalFamilyDetailCode, 'family_member_name');
            }
        }, 'filter' => false, 'label' => 'Name'],
        ['attribute' => 'resp_param_1'],
        ['attribute' => 'resp_param_2'],
        ['attribute' => 'resp_param_3'],
        ['attribute' => 'resp_param_4'],
        ['attribute' => 'resp_param_5'],
        ['attribute' => 'resp_param_6'],
        ['attribute' => 'data_post_status', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('send_status', $searchModel, 'data_post_status')],
        [
        'attribute' => 'picked_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime);
        }],
        [
        'attribute' => 'response_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }],
        ['attribute' => 'resp_desc'],
        ['attribute' => 'resp_status'],
];
$grid_option = [
    'id' => 'data-exchange-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) {
            if (strtolower($model->process_name) == 'member provisional') {
                $label = 'Provisional Member View';
                $url = '/dcsoperation/tbl-member-provisional/view';
            } else {
                $label = 'Provisional Member Family Detail View';
                $url = '/clienterp/tbl-data-exchange-log/view-family';
            }
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $label];
            return Html::a('<i class="fa fa-eye"></i>', [$url, 'id' => $model->process_code], $options);
        },
        'view_history' => function($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Data Exchange Log History View'];
            return Html::a('<i class="fa fa-history"></i>', ['/clienterp/tbl-data-exchange-log/view-history', 'id' => $model->process_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
