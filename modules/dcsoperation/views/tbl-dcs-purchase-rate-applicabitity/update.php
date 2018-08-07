<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Purchase Rate Applicabitity',
]) . $model->rate_app_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Applicabitities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rate_app_code, 'url' => ['view', 'id' => $model->rate_app_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-purchase-rate-applicabitity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
