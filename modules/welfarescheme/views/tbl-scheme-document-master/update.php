<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeDocumentMaster */

$this->title = 'Update Tbl Scheme Document Master: ' . $model->doc_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Document Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_id, 'url' => ['view', 'id' => $model->doc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-document-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
