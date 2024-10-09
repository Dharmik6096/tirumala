<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Master Hierarchy'));
$this->params['menu'][] = Yii::$app->controls->import('master-hierarchy', $this);
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>

        <div class="panel-body hide-grid-export">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
