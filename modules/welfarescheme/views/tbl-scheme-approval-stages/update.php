<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApprovalStages */

$this->title = 'Update Tbl Scheme Approval Stages: ' . $model->stage_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Approval Stages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->stage_id, 'url' => ['view', 'id' => $model->stage_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-approval-stages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
