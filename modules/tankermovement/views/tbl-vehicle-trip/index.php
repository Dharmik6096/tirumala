<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Vehicle Trip'));

$this->params['menu'][] = Yii::$app->controls->add('Vehicle Trip');
// $this->params['menu'][] = Yii::$app->controls->add('Vehicle Trip For Party', 'create', );
$this->params['menu'][] = Yii::$app->controls->add('Vehicle Trip With Party', ['create', 'type' => 'party'], true);
$this->params['menu'][] = Yii::$app->controls->add('Change Tanker', ['replace-tanker'], true);
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
            ])
            ?>
        </div>
    </div>
</div>