<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Eipl App Feedback Items'));
$this->params['menu'][] = Yii::$app->controls->add('Eipl App Feedback Items');
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

