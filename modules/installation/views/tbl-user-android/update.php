<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\TblUserAndroid */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl User Android',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl User Androids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->user_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-user-android-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
