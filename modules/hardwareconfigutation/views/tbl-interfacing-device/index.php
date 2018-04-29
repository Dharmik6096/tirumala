<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'interfacing device'));
?>
<div class="panel panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
        <div class="pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
            <?= Yii::$app->controls->add('interfacing device'); ?>
            <?php Yii::$app->controls->import('interfacing-device',$this) ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>