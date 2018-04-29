<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblVehicleWiseRouteMapping */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Vehicle Wise Route Mapping',
]) . $model->vehicle_wise_route_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vehicle Wise Route Mappings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vehicle_wise_route_code, 'url' => ['view', 'id' => $model->vehicle_wise_route_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-vehicle-wise-route-mapping-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
