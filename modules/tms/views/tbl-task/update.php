<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tms\models\TblTask */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Task',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->task_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
