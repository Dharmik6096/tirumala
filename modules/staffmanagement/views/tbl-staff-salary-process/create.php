<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcess */

$this->title = Yii::t('app', 'Create Tbl Staff Salary Process');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Staff Salary Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-staff-salary-process-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
