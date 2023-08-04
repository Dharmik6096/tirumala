<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\TblAttachment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Attachment',
]) . $model->attachment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Attachments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->attachment_code, 'url' => ['view', 'id' => $model->attachment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-attachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
