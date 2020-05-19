<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchConsolidated */

$this->title = Yii::t('app', 'Create Tbl Bmc Dispatch Consolidated');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Dispatch Consolidateds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-dispatch-consolidated-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
