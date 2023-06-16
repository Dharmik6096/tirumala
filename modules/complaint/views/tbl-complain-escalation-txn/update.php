<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainEscalationTxn */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Complain Escalation Txn',
]) . $model->complain_escalation_txn_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Escalation Txns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->complain_escalation_txn_code, 'url' => ['view', 'id' => $model->complain_escalation_txn_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-complain-escalation-txn-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
