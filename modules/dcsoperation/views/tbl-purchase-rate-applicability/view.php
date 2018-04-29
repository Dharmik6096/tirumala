<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRateApplicability */

$this->title = $model->rate_app_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Applicabitities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-purchase-rate-applicability-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->rate_app_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->rate_app_code], [
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
            'rate_app_code',
            'created_at',
            'created_by',
            'deleted_at',
            'deleted_by',
            'flg_sentbox_entry',
            'is_active:boolean',
            'is_delete:boolean',
            'sync_status',
            'sync_timestamp',
            'updated_at',
            'updated_by',
            'wef_date',
            'dcs_code',
            'purchase_rate_code',
            'shift_code',
            'union_code',
        ],
    ]) ?>

</div>
