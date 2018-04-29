<?php
$this->title = Yii::$app->label->title('create', 'Acknowledgement');

//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bipl Change Acknowledgements'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'create',
        ])
        ?>
    </div>
</div>