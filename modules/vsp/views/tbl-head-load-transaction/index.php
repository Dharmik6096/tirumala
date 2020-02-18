<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Head Load'));
?>
<div class="panel panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
        <div class="pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="false">
            <?= Yii::$app->controls->add('Head Load'); ?>
        </div>
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

