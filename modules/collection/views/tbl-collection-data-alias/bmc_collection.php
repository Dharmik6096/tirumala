<?php
$this->title = Yii::t('app', 'BMC Collection Approval');
$is_dcs_editable = (Yii::$app->general->getUnionConfiguration($searchModel->union_code, 'bmc_coll_dcs_editable', 'PORTAL') == 1) ? TRUE : FALSE;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'showType' => $showType]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'id' => $id, 'url' => $url, 'showType' => $showType, 'is_dcs_editable' => $is_dcs_editable, 'is_concate' => true]);
        ?>
    </div>
</div>
