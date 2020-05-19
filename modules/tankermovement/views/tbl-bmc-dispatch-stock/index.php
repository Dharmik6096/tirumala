<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tankermovement\models\TblBmcDispatchStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Bmc Dispatch Stocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-dispatch-stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Bmc Dispatch Stock'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bmc_dispatch_stock_code',
            'transaction_date',
            'to_date',
            'to_shift_code',
            'qty_diff_type_code',
            // 'milk_quality_type_code',
            // 'milk_type_code',
            // 'bmc_silos_info_code',
            // 'opening_bal',
            // 'closing_bal',
            // 'purchase_qty',
            // 'qty_diff',
            // 'extra_qty',
            // 'balance_qty',
            // 'fat',
            // 'snf',
            // 'water',
            // 'type',
            // 'remarks',
            // 'union_code',
            // 'plant_code',
            // 'mcc_plant_code',
            // 'bmc_code',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_type',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'x_col1',
            // 'x_col2',
            // 'x_col3',
            // 'x_col4',
            // 'x_col5',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
