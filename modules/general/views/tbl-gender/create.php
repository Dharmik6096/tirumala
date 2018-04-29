<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblGender */

$this->title = Yii::t('app', 'Create Tbl Gender');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Genders'), 'url' => ['index']];
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
