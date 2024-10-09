<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

?>
<?php

$attribute = [
        ['attribute' => 'member_code', 'filter' => true],
        ['attribute' => 'allotted_share', 'filter' => true],
        ['attribute' => 'proposed_share', 'filter' => true],
        ['attribute' => 'total_share', 'filter' => true],
        ['attribute' => 'share_amount', 'filter' => true],
        ['attribute' => 'folio_no', 'filter' => true],
        ['attribute' => 'till_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->till_date);
        }],
];

$grid_option = [
    'id' => 'member-share-deposit-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>