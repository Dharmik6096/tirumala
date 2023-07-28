<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblMccBillHead */

$this->title = $model->mcc_bill_head_code;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Mcc Bill Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mcc-bill-head-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mcc_bill_head_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mcc_bill_head_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mcc_bill_head_code',
            'bill_head_name',
            'union_code',
            'is_disburse_allowed',
            'bill_head_type',
            'general_formula_code',
            'general_formula',
            'general_formula_comma',
            'default_bill_head_code',
            'is_default',
            'is_active',
            'sequence_no',
            'bill_head_for',
            'has_slab',
            'is_hold',
            'payment_cycle_type',
            'calculation_based_on',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
