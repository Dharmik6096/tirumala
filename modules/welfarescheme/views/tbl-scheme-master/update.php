<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeMaster */

$this->title = 'Update Tbl Scheme Master: ' . $model->scheme_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->scheme_id, 'url' => ['view', 'id' => $model->scheme_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
