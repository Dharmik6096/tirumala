<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMeetingAgenda */

$this->title = Yii::t('app', 'Create Tbl Meeting Agenda');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Meeting Agendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-meeting-agenda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
