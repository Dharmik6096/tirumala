<?php

use yii\helpers\Html;

if ($model->transporter_type == 0) {
    $this->title = Yii::t('app', 'Payment Detail of ') . $model->routeCode->route_name . ' (' . $model->transporter_name . ')';
    $file_to_render = '_primary_vehicle_detail';
} else {
    $this->title = Yii::t('app', 'Payment Detail of ') . $model->transporter_name . ' (' . $model->parsing_no . ')';
    $file_to_render = '_secondary_vehicle_detail';
}
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <?=
            $this->render($file_to_render, [
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


