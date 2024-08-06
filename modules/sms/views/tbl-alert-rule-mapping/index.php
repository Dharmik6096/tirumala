<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Alert Rule Master'));
$this->params['menu'][] = Yii::$app->controls->add('Alert Rule Mapping');
?>
<div class="tbl-alert-rule-mapping-index">
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