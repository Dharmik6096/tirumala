<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACAlibration */

$this->title = Yii::t('app', 'Create Tbl Macalibration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Macalibrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-macalibration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
