<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchConsolidated */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Bmc Dispatch Consolidated',
]) . $model->bmc_dispatch_consolidated_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Dispatch Consolidateds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bmc_dispatch_consolidated_code, 'url' => ['view', 'id' => $model->bmc_dispatch_consolidated_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-bmc-dispatch-consolidated-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
