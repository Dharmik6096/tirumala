<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Union'));
$this->params['menu'][]=Yii::$app->controls->add('Union');
$this->params['menu'][]=Yii::$app->controls->import('union', $this);
?>
<div class="tbl-unions-index">
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