<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationDisbursement */

$this->title = $model->disburse_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Application Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-application-disbursement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->disburse_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->disburse_id], [
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
            'disburse_id',
            'application_id',
            'disburse_date',
            'disburse_value',
            'disburse_by',
            'payment_mode',
            'bank_name',
            'branch_name',
            'party_name',
            'party_relation',
            'payment_ref_id',
            'payment_detail',
            'remarks',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_type',
            'originating_org_code',
            'originating_org_type',
        ],
    ]) ?>

</div>
