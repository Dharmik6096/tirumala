<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Vehicle Cleaning'));
$this->params['menu'][] = Yii::$app->controls->add('Vehicle Cleaning Detail');
?>
<div class="tbl-vehicle-trip-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'config_list' => $config_list
            ])
            ?>
        </div>
    </div>
</div>