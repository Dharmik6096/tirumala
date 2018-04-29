<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMilkReceipt */

$this->title = Yii::t('app', 'Create Tbl Milk Receipt');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-receipt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
