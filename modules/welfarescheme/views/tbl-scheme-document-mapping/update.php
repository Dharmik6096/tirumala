<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeDocumentMapping */

$this->title = 'Update Tbl Scheme Document Mapping: ' . $model->mapping_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Document Mappings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mapping_id, 'url' => ['view', 'id' => $model->mapping_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-document-mapping-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
