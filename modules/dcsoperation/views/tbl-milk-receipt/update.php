<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMilkReceipt */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Receipt',
]) . $model->milk_receipt_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->milk_receipt_code, 'url' => ['view', 'id' => $model->milk_receipt_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-receipt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
