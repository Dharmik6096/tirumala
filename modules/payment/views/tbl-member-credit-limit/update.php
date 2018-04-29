<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblMemberCreditLimit */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Member Credit Limit',
]) . $model->member_credit_limit_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Member Credit Limits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->member_credit_limit_code, 'url' => ['view', 'id' => $model->member_credit_limit_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-member-credit-limit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
