<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Blog'));
$this->params['menu'][] = Yii::$app->controls->add('Blog');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render($flag, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>