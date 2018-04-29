<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblMemberCreditLimit */

$this->title = Yii::t('app', 'Create Tbl Member Credit Limit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Member Credit Limits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-member-credit-limit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
