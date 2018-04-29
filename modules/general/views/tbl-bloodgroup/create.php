<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblBloodgroup */
$this->title = Yii::t('app', 'Create Tbl Bloodgroup');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bloodgroups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
