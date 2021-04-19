<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\DpuStationDetail */

$this->title = Yii::t('app', 'Create Dpu Station Detail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dpu Station Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpu-station-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
