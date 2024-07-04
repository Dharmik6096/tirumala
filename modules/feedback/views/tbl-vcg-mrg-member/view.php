<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblVCGMRGMember */

$this->title = $model->VCG_MRG_member_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Vcgmrg Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmrgmember-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->VCG_MRG_member_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->VCG_MRG_member_id], [
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
            'VCG_MRG_member_id',
            'mcc_plant_code',
            'bmc_code',
            'route_code',
            'dcs_code',
            'member_code',
            'member_tr_code',
            'wef_date',
            'end_date',
            'status',
            'type',
            'attachment_sign_key',
            'attachment_photo_key',
            'remark',
            'approved_at',
            'approved_by',
            'transaction_date',
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
