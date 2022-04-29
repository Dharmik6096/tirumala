<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblWeighBridgeData */

$this->title = $model->uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Weigh Bridge Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-weigh-bridge-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->uuid], [
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
            'uuid',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'date',
            'time',
            'vehicle_type_code',
            'vehicle_no',
            'type',
            'location_code',
            'location_detail',
            'material_type_code',
            'gross_weight',
            'gross_weight_time',
            'tare_weight',
            'tare_weight_time',
            'weight',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_type',
            'originating_org_code',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
