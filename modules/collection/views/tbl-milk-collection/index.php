<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collections'));
$this->params['menu'][] = Yii::$app->controls->add('Milk Collection');
$this->params['menu'][] = Yii::$app->controls->custombutton('Update Milk Collection', 'update-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil-alt"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Milk Collection', 'delete-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'qlty_wise_collection', 'PORTAL') == 1) {
    $this->params['menu'][] = Yii::$app->controls->import('milk-collection-qlty-bulk', $this);
} else {
    $this->params['menu'][] = Yii::$app->controls->import('milk-collection-bulk', $this);
}
$this->params['menu'][] = Yii::$app->controls->custombutton('Online Farmer', 'online-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-map-marker-alt"></i>');
$url_path = [];
$url_path[] = 'online-collection';
$url = Url::to(array_values($url_path));
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
            <?= GhostHtml::a('<i class="fa fa-map-marker-alt"></i>', $url, ['class' => 'headerIcon btn btn-danger apply-shortcut btn-block', 'shortcut_key' => 'ctrl+alt+c']); ?>           
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
