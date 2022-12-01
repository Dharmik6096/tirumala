<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeDocumentMapping */

$this->title = 'Create Tbl Scheme Document Mapping';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Document Mappings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-document-mapping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
