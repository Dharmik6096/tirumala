<?php

use yii\helpers\Html;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'permission group'));
$this->params['menu'][]=Yii::$app->controls->add('permission group');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ])
        ?>
    </div>
</div>