<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatchTransaction */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Product Dispatch Transaction',
]) . $model->dispatch_transaction_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Dispatch Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dispatch_transaction_code, 'url' => ['view', 'id' => $model->dispatch_transaction_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-product-dispatch-transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
