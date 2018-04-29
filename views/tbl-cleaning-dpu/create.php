<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblCleaningDpu */

$this->title = Yii::t('app', 'Create Tbl Cleaning Dpu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Cleaning Dpus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-cleaning-dpu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
