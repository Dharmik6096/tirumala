<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblLoanProductSaleDetails */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Loan Product Sale Details',
]) . $model->sale_detail_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Loan Product Sale Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sale_detail_code, 'url' => ['view', 'id' => $model->sale_detail_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-loan-product-sale-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
