<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductStockAdjustmentTransaction */

$this->title = Yii::t('app', 'Create Tbl Product Stock Adjustment Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Stock Adjustment Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-product-stock-adjustment-transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
