<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblGender */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Tbl Gender',
        ]) . $model->gender_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Genders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gender_code, 'url' => ['view', 'id' => $model->gender_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</div>
