<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblSampleBottleTesting */

$this->title = $model->sample_bottle_testing_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Sample Bottle Testings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-sample-bottle-testing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sample_bottle_testing_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sample_bottle_testing_code], [
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
            'sample_bottle_testing_code',
            'trip_code',
            'bmc_milk_dispatch_code',
            'bmc_milk_dispatch_txn_code',
            'sample_bottle_testing_date',
            'transaction_date',
            'milk_quality_type_code',
            'milk_type_code',
            'fat',
            'snf',
            'protein',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_type',
            'originating_org_code',
            'originating_org_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
