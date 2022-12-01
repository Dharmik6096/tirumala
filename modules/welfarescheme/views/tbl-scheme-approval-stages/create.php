<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApprovalStages */

$this->title = 'Create Tbl Scheme Approval Stages';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Approval Stages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-approval-stages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
