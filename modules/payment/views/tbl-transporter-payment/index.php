<?php

use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Transporter Payment'));
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add Transporter Payment(Primary)'), ['/payment/tbl-transporter-payment/create'], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add Transporter Payment(Secondary)'), ['/payment/tbl-transporter-payment/create-secondary'], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-money-bill"></i>' . Yii::t('app', 'Disburse Transporter Payment'), ['/payment/tbl-transporter-payment/disburse-payment'], ['class' => 'btn btn-danger btn-block']);
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