<?php

use app\components\SearchFilter;
?>
<?php
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
$filter_model = new SearchFilter();
$filter_data = $filter_model->getRecord($model_class);
?>

<div class="grid-search search-filter">
    <?php
    if (!empty($filter_data)) {
        $aciton = isset($filter_data['action']) ? $filter_data['action'] : ['index'];
        $method = isset($filter_data['method']) ? $filter_data['method'] : 'get';
        $filters = $filter_data['filter'];
        $count = count($filters);
        if (!empty($filters) && Yii::$app->controller->action->id == $aciton[0] && !in_array(Yii::$app->controller->module->id, ['report', 'jasperreports'])) {
            $f_cnt = 0;
            $form = \yii\widgets\ActiveForm::begin([
                        'action' => $aciton,
                        'method' => $method,
            ]);
            ?>
            <?php
            foreach ($filters as $key => $value) {
                $value_array = explode(':', $value);
                $value = $value_array[0];
                if (in_array($value, array('f_union_code'))) {
                    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
                        $f_cnt++
                        ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
                    </div>
                <?php } ?>          
                <?php
                if (in_array($value, array('f_plant_code'))) {
                    $f_cnt++
                    ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-f_union_code', 'f_plant_code'); ?>
                    </div>
                <?php } ?>
                <?php
                if (in_array($value, array('f_mcc_code'))) {
                    $f_cnt++
                    ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-f_plant_code', 'f_mcc_code'); ?>
                    </div>
                <?php } ?>
                <?php
                if (in_array($value, array('f_bmc_code'))) {
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
                if (in_array($value, array('f_dcs_code'))) {
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
                if (in_array($value, array('min_date'))) {
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
                if (in_array($value, array('shift', 'Shift', 'shift_id', 'shift_code'))) {
                    $f_cnt++;
                    ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, $value); ?>
                    </div>
                <?php } ?>

                <?php
                if (in_array($value, array('unit'))) {
                    $f_cnt++
                    ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->controls->unit_conversion($model, [1, 2], 1, 'Select capacity unit'); ?>
                    </div>
                <?php } ?>

                <?php
                if (in_array($value, array('transporter_code'))) {
                    $f_cnt++;
                    if (isset($value_array[1]) && $value_array[1] == 'f_union_code') {
                        ?>
                        <div class="col-sm-2">
                        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, $field_class . '-f_union_code', 'form-group col-sm-2 padding-right-5 padding-left-0'); ?>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', false, false, 'transporter_code'); ?>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php
                if (in_array($value, array('vehicle_code'))) {
                    $f_cnt++;
                    if (isset($value_array[1]) && $value_array[1] == 'transporter_code') {
                        ?>
                        <div class="col-sm-2">
                        <?= Yii::$app->dropdown->vehicletransporter($model, $form, $field_class . '-transporter_code', 'vehicle_code'); ?>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-2">
                        <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code'); ?>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php
                if (in_array($value, array('transporter_payment_head_code'))) {
                    $f_cnt++;
                    ?>
                    <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('transporter_payment_head_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', false, false, 'transporter_payment_head_code'); ?>
                    </div>
                <?php } ?>

            <?php } ?>

                <?php if ($f_cnt > 0) { ?>
                <div class="col-sm-2">
                <?= Yii::$app->controls->search(); ?>
                </div>
            <?php } ?>
            <?php \yii\widgets\ActiveForm::end(); ?>

            <?php
        }
    }
    ?>
</div>