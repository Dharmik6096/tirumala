<?php

use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Receipt'));

$this->params['menu'][] = Yii::$app->controls->add('Milk Receipt');
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-pencil-alt"></i>' . Yii::t('app', 'Edit Trip Detail'), ['/tankermovement/tbl-milk-vehicle-entry/edit-trip-detail'], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = Yii::$app->controls->import('milk-receipt-trip-update', $this, 'Update Trip Code');
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