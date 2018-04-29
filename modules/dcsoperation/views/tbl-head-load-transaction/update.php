<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblHeadLoadTransaction */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Head Load Transaction',
]) . $model->head_load_transaction_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Head Load Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->head_load_transaction_code, 'url' => ['view', 'id' => $model->head_load_transaction_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-head-load-transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
