<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>
<?php

$attribute = [
    ['attribute' => 'bmc_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['attribute' => 'fat_offset', 'filter' => false],
    ['attribute' => 'snf_offset', 'filter' => false],
    ['attribute' => 'water_offset', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
    [
        'attribute' => 'date_time_of_calibration',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_calibration);
        }],
    ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'filter' => false],
];
$grid_option = [
    'id' => 'calibration-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
