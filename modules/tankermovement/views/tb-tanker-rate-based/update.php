<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblTankerRateBased */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Purchase Rate Based',
]) . $model->rate_detail_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Baseds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rate_detail_id, 'url' => ['view', 'id' => $model->rate_detail_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-tanker-rate-based-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
