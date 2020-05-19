<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcess */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Staff Salary Process',
]) . $model->salary_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Staff Salary Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->salary_code, 'url' => ['view', 'id' => $model->salary_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-staff-salary-process-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
