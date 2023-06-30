<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE],
    ['attribute' => 'from_type', 'value' => function($model) {
            return strtoupper($model->from_type);
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('place_type', $searchModel, 'from_type'),
    ],
    ['attribute' => 'from_dest', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->from_type);
            $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
        }, 'filter' => false],
    ['attribute' => 'to_type', 'value' => function($model) {
            return strtoupper($model->to_type);
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('place_type', $searchModel, 'to_type'),
    ],
    ['attribute' => 'to_dest', 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->to_type);
            $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
        }, 'filter' => false],
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->wef_date);
}],
    ['attribute' => 'total_kms']
];

$grid_option = [
    'id' => 'location-wise-km-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'detail-view' => function ($url, $model) {
            $options = [ 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'view',];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/transporter/tbl-location-wise-km-detail/view', 'from_type' => $model->from_type, 'from_dest' => $model->from_dest, 'to_type' => $model->to_type, 'to_dest' => $model->to_dest], $options);
        },
                'update' => true,
            ]
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
