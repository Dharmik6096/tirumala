<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplication */

$this->title = 'Create Tbl Scheme Application';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-application-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
