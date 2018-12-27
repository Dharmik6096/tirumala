<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblDcsGeneralConfig */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dcs General Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dcs-general-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->code], [
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
            'code',
            'allow_multiple_voters',
            'backup_path',
            'backup_per_shift',
            'created_at',
            'created_by',
            'election_alert_day',
            'election_term',
            'is_backup_user_choice',
            'is_backup_on_closing',
            'is_backup_disbursement',
            'max_share_buy',
            'min_share_req',
            'nos_of_reminders',
            'purchase_rate_with_tax',
            'sale_rate_with_tax',
            'share_issued',
            'share_unit_cost',
            'updated_at',
            'updated_by',
            'union_code',
            'milk_dispatch_in',
            'headload_km',
            'milk_dispatch_quantity_mode',
            'milk_receipt_quantity_mode',
            'product_sale_in_cash',
            'share_amount_editable',
            'product_billing',
            'billing_zero_amount_auto',
        ],
    ]) ?>

</div>
