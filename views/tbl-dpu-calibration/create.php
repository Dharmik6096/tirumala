<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblDpuCalibration */

$this->title = Yii::t('app', 'Create Tbl Dpu Calibration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dpu Calibrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dpu-calibration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
