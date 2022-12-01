<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationDisbursement */

$this->title = 'Update Tbl Scheme Application Disbursement: ' . $model->disburse_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Application Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->disburse_id, 'url' => ['view', 'id' => $model->disburse_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-application-disbursement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
