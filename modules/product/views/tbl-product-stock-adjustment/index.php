<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\product\models\TblProductStockAdjustmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Product Stock Adjustments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-product-stock-adjustment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Product Stock Adjustment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product_stock_adjustment_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            // 'dcs_code',
            // 'product_code',
            // 'sap_batch_no',
            // 'adjustment_type',
            // 'invoice_no',
            // 'transaction_date',
            // 'unit',
            // 'stock',
            // 'qty',
            // 'reason',
            // 'remarks',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'originating_type',
            // 'x_col1',
            // 'x_col2',
            // 'x_col3',
            // 'x_col4',
            // 'x_col5',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
