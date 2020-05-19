<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcMilkDispatch */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Bmc Milk Dispatch',
]) . $model->bmc_milk_dispatch_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Milk Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bmc_milk_dispatch_code, 'url' => ['view', 'id' => $model->bmc_milk_dispatch_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-bmc-milk-dispatch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
