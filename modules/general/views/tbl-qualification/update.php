<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblQualification */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Tbl Qualification',
        ]) . $model->qualification_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Qualifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->qualification_code, 'url' => ['view', 'id' => $model->qualification_code]];
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
