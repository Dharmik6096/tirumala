<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\payment\models\TblTransporterPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Transporter Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-transporter-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Transporter Payment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'transporter_payment_code',
            'transporter_code',
            'total_vehicle',
            'coll_qty',
            'coll_kg_fat',
            // 'coll_kg_snf',
            // 'disp_qty',
            // 'disp_kg_fat',
            // 'disp_kg_snf',
            // 'rec_qty',
            // 'rec_kg_fat',
            // 'rec_kg_snf',
            // 'cd_qty_diff',
            // 'cd_kg_fat_diff',
            // 'cd_kg_snf_diff',
            // 'rd_qty_diff',
            // 'rd_kg_fat_diff',
            // 'rd_kg_snf_diff',
            // 'no_of_days',
            // 'total_amount',
            // 'total_deduction',
            // 'final_amount',
            // 'adjust_amount',
            // 'net_amount',
            // 'remarks:ntext',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'delete_at',
            // 'delete_by',
            // 'is_active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
