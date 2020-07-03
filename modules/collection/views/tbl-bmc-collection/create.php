<?php
$this->title = Yii::$app->label->title('create', 'BMC Collection');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body theme_border_left theme_border_right theme_border_bottom">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ])
        ?>
    </div>
</div>
