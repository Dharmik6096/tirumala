<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bank'));
$this->params['menu'][]=Yii::$app->controls->add('Bank');
$this->params['menu'][]=Yii::$app->controls->import('bank', $this);
?>
<div class="tbl-banks-index">
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
</div>