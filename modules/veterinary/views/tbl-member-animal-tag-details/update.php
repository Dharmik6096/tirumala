<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMemberAnimalTagDetails */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Member Animal Tag Details',
]) . $model->member_animal_tag_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Member Animal Tag Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->member_animal_tag_id, 'url' => ['view', 'id' => $model->member_animal_tag_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-member-animal-tag-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
