<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'BMC Collection'));
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-plus"></i> Add ' . Yii::t('app', 'Create Allow')), Url::to("create-allow"), ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']);

$this->params['menu'][] = Yii::$app->controls->custombutton('Update BMC Collection Allow', 'update-bmc-collection-allow', '', 'btn btn-danger btn-block', '<i class="fa fa-pencil"></i>');
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete BMC Collection Allow', 'delete-bmc-collection-allow', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'pouring_bmc_collection', 'PORTAL') == 1) {
    $this->params['menu'][] = Yii::$app->controls->import('bmc-mapped-collection-allow-bulk', $this);
} else {
    $this->params['menu'][] = Yii::$app->controls->import('bmc-collection-allow-bulk', $this);
}
?>
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
