<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'SAP Files Process'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <?=
    $this->render('_form_grid', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ])
    ?>
</div>