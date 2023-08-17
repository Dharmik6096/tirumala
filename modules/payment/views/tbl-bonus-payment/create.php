<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblBonusPayment */

$this->title = Yii::t('app', 'Create Tbl Bonus Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bonus Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bonus-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
