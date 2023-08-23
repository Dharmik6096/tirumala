<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblApprovalStages */

$this->title = $model->approval_stages_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Approval Stages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-approval-stages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->approval_stages_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->approval_stages_code], [
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
            'approval_stages_code',
            'union_code',
            'process_name',
            'approval_mode',
            'remarks',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
        ],
    ]) ?>

</div>
