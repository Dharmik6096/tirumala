<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMeetingAgenda */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Meeting Agenda',
]) . $model->meeting_agenda_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Meeting Agendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->meeting_agenda_code, 'url' => ['view', 'id' => $model->meeting_agenda_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-meeting-agenda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
