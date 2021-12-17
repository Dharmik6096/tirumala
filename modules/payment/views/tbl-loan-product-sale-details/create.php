<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblLoanProductSaleDetails */

$this->title = Yii::t('app', 'Create Tbl Loan Product Sale Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Loan Product Sale Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-loan-product-sale-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
