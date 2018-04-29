<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Change Logs'));
$this->params['menu'][] = Yii::$app->controls->add('Change Log');
use yii\widgets\ListView;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'itemView' => '_view',
        ]);
        ?>
    </div>
</div>
