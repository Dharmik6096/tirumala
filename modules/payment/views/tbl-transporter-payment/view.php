<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Payment Detail of ') . $model->routeCode->route_name . ' (' . $model->transporter_name . ')';
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <?=
            $this->render('_primary_vehicle_detail', [
                'model' => $model,
                'vehicleDetail' => $vehicleDetail,
                'headDetail' => $headDetail,
                'searchModel' => $searchModel,
                'searchModelHead' => $searchModelHead
            ])
            ?>
        </div>
    </div>
</div>


