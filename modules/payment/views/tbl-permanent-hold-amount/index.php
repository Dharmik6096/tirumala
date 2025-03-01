<?php
use yii\web\View;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Permanent hold payment release'));
$this->params['menu'][] = Yii::$app->controls->custombutton('<i class="fa fa-money-bill"></i> ' . Yii::t('app', 'Release Hold Amount (Member)'), ['/payment/tbl-permanent-hold-amount/release-payment'], true);
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

