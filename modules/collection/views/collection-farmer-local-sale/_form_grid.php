<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2

?>
<?php

$attribute = [
    ['attribute' => 'farmerid', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }],
    ['attribute' => 'vlccid', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
    ['attribute' => 'bmcid',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    [
        'attribute' => 'dtdate',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'date'),
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dtdate);
}],
    ['attribute' => 'milktype',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => false],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
];
$grid_option = [
    'id' => 'collection-farmer-local-sale',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
