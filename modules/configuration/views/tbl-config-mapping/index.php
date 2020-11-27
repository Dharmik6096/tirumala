<?php

use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Control Mapping'));
$this->params['menu'][] = Yii::$app->controls->add('Control Mapping', ['/configuration/tbl-config-mapping/create', 'id' => $searchModel->union_code]); //GhostHtml::a('' . Yii::t('app', 'Add Control Mapping'), ['/configuration/tbl-config-mapping/create', 'id' => $searchModel->union_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-plus"></i>' . Yii::t('app', 'Add Payment Configurations'), ['/configuration/tbl-config/payment-config-create', 'id' => $searchModel->union_code], ['class' => 'btn btn-danger btn-block']);
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