<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\DpuStationDetail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dpu Station Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpu-station-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'station_code',
            'flag_key',
            'flag_value',
            'other_value',
            'vendor_code',
            'company_code',
            'created_at',
            'created_by',
            'transferred_datetime',
            'transferred_by',
            'download_datetime',
            'download_by',
            'ref_code',
            'dpu_header',
        ],
    ]) ?>

</div>
