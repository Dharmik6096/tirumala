<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainEscalationTxn */

$this->title = Yii::t('app', 'Create Tbl Complain Escalation Txn');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Escalation Txns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-complain-escalation-txn-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
