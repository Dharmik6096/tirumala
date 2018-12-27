<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\configuration\models\TblDcsGeneralConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Dcs General Configs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dcs-general-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Dcs General Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'allow_multiple_voters',
            'backup_path',
            'backup_per_shift',
            'created_at',
            // 'created_by',
            // 'election_alert_day',
            // 'election_term',
            // 'is_backup_user_choice',
            // 'is_backup_on_closing',
            // 'is_backup_disbursement',
            // 'max_share_buy',
            // 'min_share_req',
            // 'nos_of_reminders',
            // 'purchase_rate_with_tax',
            // 'sale_rate_with_tax',
            // 'share_issued',
            // 'share_unit_cost',
            // 'updated_at',
            // 'updated_by',
            // 'union_code',
            // 'milk_dispatch_in',
            // 'headload_km',
            // 'milk_dispatch_quantity_mode',
            // 'milk_receipt_quantity_mode',
            // 'product_sale_in_cash',
            // 'share_amount_editable',
            // 'product_billing',
            // 'billing_zero_amount_auto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
