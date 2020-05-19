<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblSampleBottleTesting */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Sample Bottle Testing',
]) . $model->sample_bottle_testing_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Sample Bottle Testings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sample_bottle_testing_code, 'url' => ['view', 'id' => $model->sample_bottle_testing_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-sample-bottle-testing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
