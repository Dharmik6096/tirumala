<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productSaleCode, ['bmcCode'], 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'customer_type', 'label' => Yii::t('app', 'Type'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->productSaleCode, ['customerType'], 'customer_desc');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return !empty($model->productSaleCode) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type) : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'sale_date_time', 'label' => Yii::t('app', 'Sale Date'), 'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->productSaleCode, 'sale_date_time'));
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'sale_type', 'value' => function($model) {
            if (!empty($model->productSaleCode)) {
                return isset($model->productSaleCode->sale_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->productSaleCode->sale_mode] : '';
            }
            return '';
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'rate', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'qty', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'amount', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'discount', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode, 'discount');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'amount_due', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode, 'amount_due');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'no_of_installment', 'label' => Yii::t('app', 'No. of Installment'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode, 'no_of_installment');
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'installment_amount', 'label' => Yii::t('app', 'Installment Amount'), 'value' => function($model) {
            $noOfInst = Yii::$app->general->getforeignkey($model->productSaleCode, 'no_of_installment');
            $amt = Yii::$app->general->getforeignkey($model->productSaleCode, 'amount_due');
            return !empty($noOfInst) && $noOfInst != 'N/A' ? round($amt / $noOfInst, 2) : 0;
        }, 'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
];
$grid_option = [
    'id' => 'bill-head-detail-list-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>