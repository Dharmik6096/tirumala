<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACAlibration */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Macalibration',
]) . $model->BMCCode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Macalibrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->BMCCode, 'url' => ['view', 'BMCCode' => $model->BMCCode, 'dtdate' => $model->dtdate, 'MilkType' => $model->MilkType, 'PPCode' => $model->PPCode, 'shift' => $model->shift]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-macalibration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
