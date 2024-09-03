<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Local Milk Rate'));
$this->params['menu'][] = Yii::$app->controls->add('Local Milk Rate');
$this->params['menu'][] = Yii::$app->controls->import('local_milk_rate_bulk', $this);
$this->params['menu'][] = Yii::$app->controls->import('localmilkrateapplicability-bulk', $this, Yii::t('app', 'Applicability Import'), [], 'local_milk_rate_applicability');
?>
<div class="tbl-banks-index">
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