<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC Shift Lock'));
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid_other', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
