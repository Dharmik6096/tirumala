<?php
use yii\helpers\Url;
Url::remember();
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Processed Collection Files'));
if (Yii::$app->general->checkAccess('/bipl/bipl-ftp-collection/add-ftp-data')) {
    $this->params['menu'][] = Yii::$app->controls->custombutton('Process BIPL Files','/bipl/bipl-ftp-collection/add-ftp-data',true);
}
?>
<div class="tbl-files-index">
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