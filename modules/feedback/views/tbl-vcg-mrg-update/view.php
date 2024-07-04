<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblVCGMRGUpdate */

$this->title = $model->VCG_MRG_Update_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vcgmrg Updates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmrgupdate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->VCG_MRG_Update_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->VCG_MRG_Update_id], [
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
            'VCG_MRG_Update_id',
            'VCG_M_id',
            'MRG_M_id',
            'month',
            'description',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'flg_sentbox_entry',
            'originating_type',
        ],
    ]) ?>

</div>
