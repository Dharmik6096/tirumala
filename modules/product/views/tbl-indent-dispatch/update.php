<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblIndentDispatch */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Indent Dispatch',
]) . $model->indent_dispatch_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Indent Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->indent_dispatch_code, 'url' => ['view', 'id' => $model->indent_dispatch_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-indent-dispatch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
