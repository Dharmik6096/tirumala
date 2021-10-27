<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collections'));
$this->params['menu'][] = Yii::$app->controls->custombutton('Delete Milk Collection', 'delete-collection', '', 'btn btn-danger btn-block', '<i class="fa fa-trash"></i>');
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
            <?php // GhostHtml::a('<i class="fa fa-map-marker"></i>', $url, ['class' => 'headerIcon btn btn-danger apply-shortcut btn-block', 'shortcut_key' => 'ctrl+alt+c']); ?>           
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
