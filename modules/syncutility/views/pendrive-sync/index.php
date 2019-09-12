<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Pendrive Sync'));
$this->params['menu'][] = GhostHtml::a_alert(Yii::t('app', 'Import Data'), ['/syncutility/pendrive-sync/import'], ['class' => 'btn btn-danger btn-block apply-shortcut apply-shortcut excel-import', 'shortcut_key' => 'ctrl+alt+c']);
$this->params['menu'][] = Yii::$app->controls->custombutton(Yii::t('app', 'Export Data'), ['/syncutility/pendrive-sync/export'], TRUE);
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



<?= $this->render('_import_popup') ?>
<?php
$script = "
     $('.excel-import').on('click',function(e){                     
       $('#excelImport').modal('toggle');
            });
";
$this->registerJs($script, View::POS_END, 'import-popup');
?>