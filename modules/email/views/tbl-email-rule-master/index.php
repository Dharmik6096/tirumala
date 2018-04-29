<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Email'));
$this->params['menu'][] = Yii::$app->controls->add('Email Rule Master');
//$this->params['menu'][]=Yii::$app->controls->add('Email Rule Master');
//$this->params['menu'][] = Yii::$app->controls->add('Email');
?>
<div class="tbl-email-rule-master">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>