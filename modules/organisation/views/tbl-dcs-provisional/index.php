<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Society'));
if (Yii::$app->general->checkAccess('/organisation/tbl-dcs/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Provisional Society');
//    if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'no_of_auto_member_create', 'PORTAL') > 0) {
//        $this->params['menu'][] = Yii::$app->controls->import('dcs-config', $this);
//    } else {
//        $this->params['menu'][] = Yii::$app->controls->import('dcs', $this);
//    }
//    $this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-times"></i>' . Yii::t('app', 'Society Deactivation'), ['/organisation/tbl-dcs-deactive/index'], ['class' => 'btn btn-danger btn-block']);
}
?>
<div class="tbl-dcs-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>

        <div class="panel-body hide-grid-export">
            <?=
            $this->render('_form_grid', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
