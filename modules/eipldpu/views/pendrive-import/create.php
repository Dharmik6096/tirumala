<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = 'EIPL Files Process';
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-download"></i>' . Yii::t('app', 'Import Zip File'), ['/eipldpu/pendrive-import/add-zip'], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa  fa-repeat"></i>' . Yii::t('app', 'Process Bulk File'), ['/bkgprocess/scheduler/process-bulk-eipl-files'], ['class' => 'btn btn-danger btn-block', 'target' => '_blank']);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</div>