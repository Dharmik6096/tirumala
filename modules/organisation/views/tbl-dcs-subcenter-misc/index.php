<?php

use yii\helpers\Html;
use yii\helpers\Url;

Url::remember();
$back = (Yii::$app->request->get('type') == 'subcenter') ? 'sub-center' : Yii::$app->request->get('type');
$this->title = Yii::t('app', Yii::$app->label->title('list', Yii::$app->request->get('name') . ' Miscellaneous'));
$this->params['menu'][]=Yii::$app->controls->add('Society Miscellaneous', ['create', 'id' => Yii::$app->request->get('id'), 'name' => Yii::$app->request->get('name'), 'type' => Yii::$app->request->get('type')]);
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Html::a(Yii::t('app', '<i class="fa fa-arrow-left"></i>'), ['tbl-' . $back . '/index'], ['class' => 'btn btn-primary', 'data-bs-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Back To Society List']); ?>
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