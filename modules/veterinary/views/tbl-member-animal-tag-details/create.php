<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMemberAnimalTagDetails */

$this->title = Yii::t('app', 'Create Tbl Member Animal Tag Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Member Animal Tag Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-member-animal-tag-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
