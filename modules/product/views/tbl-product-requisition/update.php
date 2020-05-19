<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductRequisition */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Product Requisition',
]) . $model->product_requisition_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Requisitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_requisition_code, 'url' => ['view', 'id' => $model->product_requisition_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-product-requisition-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
