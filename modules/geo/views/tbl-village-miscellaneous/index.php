<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('app', Yii::$app->label->title('list', Yii::$app->getRequest()->getQueryParam('name') . ' Miscellaneous'));
$this->params['menu'][]=Yii::$app->controls->add('Village Miscellaneous', ['create', 'id' => Yii::$app->request->get('id'), 'name' => Yii::$app->request->get('name')]);
Url::remember();
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Html::a(Yii::t('app', '<i class="fa fa-arrow-left"></i>'), ['tbl-villages/index'], ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+n', 'data-bs-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Back To Village List']); ?>
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>