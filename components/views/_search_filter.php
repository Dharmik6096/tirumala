<?php

use app\components\SearchFilter;
?>
<?php
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
$filter_model = new SearchFilter();
$filter_data = $filter_model->getRecord($model_class);
if (!empty($filter_data)) {
    $aciton = isset($filter_data['action']) ? $filter_data['action'] : ['index'];
    $method = isset($filter_data['method']) ? $filter_data['method'] : 'get';
    $filters = $filter_data['filter'];
    if (!empty($filters)) {
        $f_cnt = 0;
        $form = \yii\widgets\ActiveForm::begin([
                    'action' => $aciton,
                    'method' => $method,
        ]);
        ?>
        <div class="grid-search">
            <?php
            if (in_array('f_union_code', $filters)) {
                if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
                    $f_cnt++
                    ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
                </div>
            <?php } ?>          
            <?php
            if (in_array('f_plant_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-f_union_code', 'f_plant_code'); ?>
                </div>
            <?php } ?>
            <?php
            if (in_array('f_bmc_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_bmc($model, $form, $field_class . '-f_plant_code', 'f_bmc_code'); ?>
                </div>
            <?php } ?>
            <?php
            if (in_array('f_route_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->bmc_route($model, $form, $field_class . '-f_bmc_code', 'f_route_code'); ?>
                </div>
            <?php } ?>


            <?php
            if (in_array('f_dcs_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->route_society($model, $form, $field_class . '-f_route_code', 'f_dcs_code'); ?>         
                </div>
            <?php } ?>


            <?php if ($f_cnt > 0) { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->search(); ?>
                </div>
            <?php } ?>
            <?php \yii\widgets\ActiveForm::end(); ?>

            </div>
        <?php }
    }
    ?>