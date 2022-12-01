<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeCriteria */

$this->title = 'Update Tbl Scheme Criteria: ' . $model->scheme_criteria_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->scheme_criteria_id, 'url' => ['view', 'id' => $model->scheme_criteria_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-criteria-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
