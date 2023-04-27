<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductStockAdjustment */

$this->title = $model->product_stock_adjustment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Stock Adjustments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-product-stock-adjustment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->product_stock_adjustment_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->product_stock_adjustment_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product_stock_adjustment_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'dcs_code',
            'product_code',
            'sap_batch_no',
            'adjustment_type',
            'invoice_no',
            'transaction_date',
            'unit',
            'stock',
            'qty',
            'reason',
            'remarks',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
