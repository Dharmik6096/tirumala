<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkDispatch */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Dispatch',
]) . $model->milk_dispatch_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->milk_dispatch_code, 'url' => ['view', 'id' => $model->milk_dispatch_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-dispatch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
