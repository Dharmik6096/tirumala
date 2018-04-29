<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SystemConfiguration */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'System Configuration',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Configurations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="system-configuration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'unionArray'=>$unionArray
    ]) ?>

</div>
