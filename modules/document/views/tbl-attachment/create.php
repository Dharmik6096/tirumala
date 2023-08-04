<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\document\models\TblAttachment */

$this->title = Yii::t('app', 'Create Tbl Attachment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Attachments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-attachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
