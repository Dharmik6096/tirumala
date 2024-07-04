<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblStatisticQuestionsMaster */

$this->title = $model->statistic_que_Id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Statistic Questions Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-statistic-questions-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->statistic_que_Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->statistic_que_Id], [
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
            'statistic_que_Id',
            'que_desc',
            'que_desc_local',
            'type',
            'source_type',
            'source',
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
