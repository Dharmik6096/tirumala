<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\TblDocumentMapping */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Document Mapping',
]) . $model->mapping_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Document Mappings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mapping_id, 'url' => ['view', 'id' => $model->mapping_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-document-mapping-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
