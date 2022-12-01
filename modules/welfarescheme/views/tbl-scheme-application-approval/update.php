<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationApproval */

$this->title = 'Update Tbl Scheme Application Approval: ' . $model->app_approval_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Application Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app_approval_id, 'url' => ['view', 'id' => $model->app_approval_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-application-approval-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
