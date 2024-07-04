<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblVCGMeetingMaster */

$this->title = $model->VCG_M_Id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vcg Meeting Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->VCG_M_Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->VCG_M_Id], [
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
            'VCG_M_Id',
            'VCG_M_code',
            'VCG_date',
            'from_time',
            'to_time',
            'mcc_plant_code',
            'bmc_code',
            'route_code',
            'dcs_code',
            'attandance_count',
            'attachment_code',
            'route_supervisor_code',
            'pib_office_code',
            'status',
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
