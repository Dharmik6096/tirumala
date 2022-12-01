<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeCriteria */

$this->title = 'Create Tbl Scheme Criteria';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-criteria-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
