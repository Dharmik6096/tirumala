<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblMilkVehicleEntry */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Vehicle Entry',
]) . $model->milk_vehicle_entry_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Vehicle Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->milk_vehicle_entry_code, 'url' => ['view', 'id' => $model->milk_vehicle_entry_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-vehicle-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
