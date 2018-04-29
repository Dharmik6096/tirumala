<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\InstallationIdentity */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Installation Identities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installation-identity-view">

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
            'originating_org_id',
            'originating_org_type',
            'organization_code',
            'organization_type',
            'installed',
            'db_path',
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
