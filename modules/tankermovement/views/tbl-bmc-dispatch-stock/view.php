<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchStock */

$this->title = $model->bmc_dispatch_stock_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Dispatch Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-dispatch-stock-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->bmc_dispatch_stock_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->bmc_dispatch_stock_code], [
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
            'bmc_dispatch_stock_code',
            'transaction_date',
            'to_date',
            'to_shift_code',
            'qty_diff_type_code',
            'milk_quality_type_code',
            'milk_type_code',
            'bmc_silos_info_code',
            'opening_bal',
            'closing_bal',
            'purchase_qty',
            'qty_diff',
            'extra_qty',
            'balance_qty',
            'fat',
            'snf',
            'water',
            'type',
            'remarks',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_type',
            'originating_org_code',
            'originating_org_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
