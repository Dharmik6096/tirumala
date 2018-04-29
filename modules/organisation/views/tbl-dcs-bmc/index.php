<?php

use yii\helpers\Url;
use yii\helpers\Html;

Url::remember();
$this->title = Yii::t('app', Yii::$app->label->title('list', 'BMC'));
$this->params['menu'][]=Yii::$app->controls->add('BMC', ['create']);
$this->params['menu'][] = Yii::$app->controls->import('bmc', $this);
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>