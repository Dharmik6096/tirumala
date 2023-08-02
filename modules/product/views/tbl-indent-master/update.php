<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblIndentMaster */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Indent Master',
]) . $model->indent_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Indent Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->indent_code, 'url' => ['view', 'id' => $model->indent_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-indent-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
