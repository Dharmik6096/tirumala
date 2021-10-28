<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collections'));
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-plus"></i> Add ' . Yii::t('app', 'Create Allow')), Url::to("create-allow"), ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']);
$this->params['menu'][] = Yii::$app->controls->custombutton('Update Milk Collection Allow', 'update-collection-allow', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Milk Collection Allow', 'delete-collection-allow', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'qlty_wise_collection', 'PORTAL') == 1) {
    $this->params['menu'][] = Yii::$app->controls->import('milk-collection-qlty-allow-bulk', $this);
} else {
    $this->params['menu'][] = Yii::$app->controls->import('milk-collection-allow-bulk', $this);
}
?>
<div class="tbl-banks-index-allow">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid_allow', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
