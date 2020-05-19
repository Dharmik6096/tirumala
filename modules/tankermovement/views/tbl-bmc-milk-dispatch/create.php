<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcMilkDispatch */

$this->title = Yii::t('app', 'Create Tbl Bmc Milk Dispatch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Milk Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-milk-dispatch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
