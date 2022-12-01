<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationDocuments */

$this->title = 'Update Tbl Scheme Application Documents: ' . $model->app_doc_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Application Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app_doc_id, 'url' => ['view', 'id' => $model->app_doc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-application-documents-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
