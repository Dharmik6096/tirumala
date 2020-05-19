<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatch */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Product Dispatch',
]) . $model->challan_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->challan_no, 'url' => ['view', 'id' => $model->challan_no]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-product-dispatch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
