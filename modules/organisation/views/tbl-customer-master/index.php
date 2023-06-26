<?php

use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Customer Master'));
$this->params['menu'][] = Yii::$app->controls->add('Customer Master');
$this->params['menu'][] = Yii::$app->controls->import('customer-master', $this);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-close"></i>' . Yii::t('app', 'Customer Deactivation'), ['/organisation/tbl-customer-deactive/index'], ['class' => 'btn btn-danger btn-block']);
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
