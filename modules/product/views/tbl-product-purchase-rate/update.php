<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductPurchaseRate */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Product Purchase Rate',
]) . $model->product_purchase_rate_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Purchase Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_purchase_rate_code, 'url' => ['view', 'id' => $model->product_purchase_rate_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-product-purchase-rate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
