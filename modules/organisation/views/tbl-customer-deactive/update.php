<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCustomerDeactive */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Customer Deactive',
]) . $model->customer_deactive_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Customer Deactives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->customer_deactive_code, 'url' => ['view', 'id' => $model->customer_deactive_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-customer-deactive-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
