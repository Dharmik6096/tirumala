<?php
$this->title = Yii::$app->label->title('list', 'Member Payment Restrict');
$this->params['menu'][] = Yii::$app->controls->add('Member Payment Restrict');
$this->params['menu'][] = Yii::$app->controls->import('member-payment-restrict', $this);
?>
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