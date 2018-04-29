<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblQualification */

$this->title = $model->qualification_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Qualifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-qualification-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->qualification_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->qualification_code], [
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
            'qualification_code',
            'created_at',
            'created_by',
            'deleted_at',
            'deleted_by',
            'is_active',
            'is_delete',
            'qualification_name',
            'sequences_no',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
