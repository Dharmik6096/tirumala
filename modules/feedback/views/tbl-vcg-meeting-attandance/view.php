<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblVCGMeetingAttandance */

$this->title = $model->VCG_M_attandance_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vcg Meeting Attandances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-attandance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->VCG_M_attandance_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->VCG_M_attandance_id], [
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
            'VCG_M_attandance_id',
            'VCG_M_Id',
            'mcc_plant_code',
            'bmc_code',
            'member_code',
            'is_present',
            'attachment_code',
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
