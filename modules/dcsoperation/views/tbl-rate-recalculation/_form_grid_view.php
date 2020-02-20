<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\organisation\models\TblDcsBmcSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?><?php

$attribute = [
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => false],
    ['attribute' => 'customer_code', 'filter' => false],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }, 'filter' => false],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'sub-center-bmc-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProviderGrid, $searchModelGrid, $grid_option);
?>
