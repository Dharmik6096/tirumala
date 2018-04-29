<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblBloodgroup */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Tbl Bloodgroup',
        ]) . $model->blood_group_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bloodgroups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->blood_group_code, 'url' => ['view', 'id' => $model->blood_group_code]];
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
