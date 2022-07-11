<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblGateEntry */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Gate Entry',
]) . $model->gate_entry_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Gate Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gate_entry_code, 'url' => ['view', 'id' => $model->gate_entry_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-gate-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
