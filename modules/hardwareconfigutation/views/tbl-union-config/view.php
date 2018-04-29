<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hardwareconfigutation\models\TblUnionConfig */

$this->title = $model->union_config_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Union Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-union-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->union_config_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->union_config_code], [
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
            'union_config_code',
            'perc_disp_recp_milk',
            'min_member_age',
            'manual_days_collection',
            'audit_response_time:datetime',
            'auto_audit_resolution',
            'range_end',
            'is_delete',
            'is_active',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'deleted_by',
            'deleted_at',
            'flg_sentbox_entry',
            'sync_status',
            'sync_timestamp',
        ],
    ]) ?>

</div>
