<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductStockAdjustment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Product Stock Adjustment',
]) . $model->product_stock_adjustment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Stock Adjustments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_stock_adjustment_code, 'url' => ['view', 'id' => $model->product_stock_adjustment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-product-stock-adjustment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
