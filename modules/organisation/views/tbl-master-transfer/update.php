<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblMasterTransfer */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Master Transfer',
]) . $model->master_transfer_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Master Transfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->master_transfer_code, 'url' => ['view', 'id' => $model->master_transfer_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-master-transfer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
