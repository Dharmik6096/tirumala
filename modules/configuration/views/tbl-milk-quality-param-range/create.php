<?php
$this->title = Yii::$app->label->title('create', 'Milk Quality Param Range');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="clearfix"></div>
        <?php
        $queryParams = Yii::$app->request->get('TblMilkQualityParamRangeSearch', []);
        $canRenderForm = ($queryParams['process_name'] ?? '') == 'BMC_MILK_DISPATCH' ? !empty($queryParams['union_code']) && !empty($queryParams['plant_code']) && !empty($queryParams['mcc_plant_code']) && !empty($queryParams['bmc_code']) : !empty($queryParams['union_code']) && !empty($queryParams['plant_code']);
        if ($canRenderForm) {
            ?>
            <?=
            $this->render('_form', [
                'model' => $model,
                'animalDetail' => $animalDetail,
                'type' => 'create',
                'existingRecords' => $existingRecords,
            ])
            ?>
        <?php } ?>
    </div>
</div>
