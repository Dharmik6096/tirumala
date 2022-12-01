<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeDocumentMaster */

$this->title = 'Create Tbl Scheme Document Master';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Document Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-document-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
