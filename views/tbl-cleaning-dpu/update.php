<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblCleaningDpu */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Cleaning Dpu',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Cleaning Dpus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-cleaning-dpu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
