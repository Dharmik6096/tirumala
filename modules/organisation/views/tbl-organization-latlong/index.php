<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Organization Latlongs'));
if (Yii::$app->general->checkAccess('/organisation/tbl-organization-latlong/update')) {
    $this->params['menu'][] = Yii::$app->controls->add('Organization Latlong');
    $this->params['menu'][] = Yii::$app->controls->import('organization-latlong', $this);
    $this->params['menu'][] = Yii::$app->controls->import('mapping-import', $this, Yii::t('app', 'Mapping Import'), [], 'TblOrganizationLatlongApplicability');
}
?>
<div class="tbl-plant-index">
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
</div>
