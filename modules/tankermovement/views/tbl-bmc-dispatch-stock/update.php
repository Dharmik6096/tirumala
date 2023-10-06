<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchStock */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Bmc Dispatch Stock',
]) . $model->bmc_dispatch_stock_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Dispatch Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bmc_dispatch_stock_code, 'url' => ['view', 'id' => $model->bmc_dispatch_stock_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-bmc-dispatch-stock-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
