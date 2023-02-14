<?php
$this->title = Yii::$app->label->title('list', 'Product Sale Lock');
$this->params['menu'][] = Yii::$app->controls->add('Product Sale Lock');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
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