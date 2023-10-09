<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblTankerRateAuto */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Autos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-tanker-rate-auto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->Id], [
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
            'Id',
            'wef_date',
            'animal_type',
            'created_at',
            'deleted_at',
            'fat',
            'flg_sentbox_entry',
            'is_delete',
            'ratetype',
            'rtpl',
            'snf',
            'updated_at',
            'milk_quality_type_code',
            'created_by',
            'deleted_by',
            'purchase_rate_id',
            'updated_by',
        ],
    ]) ?>

</div>
