<?php

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Master Transfer Data Updates'));
$this->params['menu'][] = Yii::$app->controls->add('Master Transfer Data Updates');
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>

        <div class="panel-body hide-grid-export">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>