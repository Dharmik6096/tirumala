<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->dcs_miscellaneous_code;
?>
<div class="tbl-dcs-subcenter-misc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->dcs_miscellaneous_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->dcs_miscellaneous_code], [
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
            'dcs_miscellaneous_code',
            'created_at',
            'deleted_at',
            'description',
            'is_active',
            'is_delete',
            'updated_at',
            'created_by',
            'dcs_code',
            'deleted_by',
            'miscellaneous_code',
            'subcenter_code',
            'updated_by',
        ],
    ]) ?>

</div>
