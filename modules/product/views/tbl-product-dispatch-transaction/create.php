<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatchTransaction */

$this->title = Yii::t('app', 'Create Tbl Product Dispatch Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Dispatch Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-product-dispatch-transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
