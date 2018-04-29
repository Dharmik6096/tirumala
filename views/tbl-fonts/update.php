<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblFonts */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Fonts',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Fonts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-fonts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
