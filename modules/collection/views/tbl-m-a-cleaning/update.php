<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACleaning */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Macleaning',
]) . $model->BMCCode;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Macleanings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->BMCCode, 'url' => ['view', 'BMCCode' => $model->BMCCode, 'cleaningdatetime' => $model->cleaningdatetime, 'dtdate' => $model->dtdate, 'PPCode' => $model->PPCode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-macleaning-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
