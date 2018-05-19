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
    $count = count($filters);
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
            if (in_array('f_mcc_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-f_plant_code', 'f_mcc_code'); ?>
                </div>
            <?php } ?>
            <?php
            if (in_array('f_bmc_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-f_mcc_code', 'f_bmc_code'); ?>
                </div>
            <?php } ?>         
            <?php
            if ($count > 5 && $f_cnt > 3) {
                echo '<div class="clearfix"></div>';
                $count = 0;
            }
            ?>

            <?php
            if (in_array('f_dcs_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, $field_class . '-f_bmc_code', 'f_dcs_code'); ?>         
                </div>
            <?php } ?>

            <?php
            if ($count > 5 && $f_cnt > 3) {
                echo '<div class="clearfix"></div>';
                $count = 0;
            }
            ?>

            <?php
            if (in_array('min_date', $filters)) {
                $request = Yii::$app->request->queryParams;
                $min_date = empty($request["min_date"]) ? '' : $request["min_date"];
                $max_date = empty($request["max_date"]) ? '' : $request["max_date"];
                $f_cnt++
                ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
                    </div>
                </div>
            <?php } ?>

            <?php
            if (in_array('shift', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
                </div>
            <?php } ?>

            <?php
            if (in_array('Shift', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'Shift'); ?>
                </div>
            <?php } ?>

            <?php
            if (in_array('shift_id', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift_id'); ?>
                </div>
            <?php } ?>

            <?php
            if (in_array('shift_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift_code'); ?>
                </div>
            <?php } ?>

            <?php
            if (in_array('unit', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->unit_conversion($model, [1, 2], 1, 'Select capacity unit'); ?>
                </div>
            <?php } ?>

            <?php
            if (in_array('transporter_code', $filters)) {
                $f_cnt++
                ?>
                <div class="col-sm-2">
                    <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, $field_class . '-f_union_code', 'form-group col-sm-2 padding-right-5 padding-left-0'); ?>
                </div>
            <?php } ?>

            <?php if ($f_cnt > 0) { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->search(); ?>
                </div>
            <?php } ?>
            <?php \yii\widgets\ActiveForm::end(); ?>

        </div>
        <?php
    }
}
?>