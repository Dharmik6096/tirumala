<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblDpuIncentiveMaster */

$this->title = $model->incentive_master_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dpu Incentive Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dpu-incentive-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->incentive_master_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->incentive_master_code], [
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
            'incentive_master_code',
            'dcs_code',
            'm_cutoff_time',
            'e_cutoff_time',
            'm_start_time',
            'e_start_time',
            'm_lock_time',
            'e_lock_time',
            'inc_rate',
            'inc_deduction',
            'union_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
