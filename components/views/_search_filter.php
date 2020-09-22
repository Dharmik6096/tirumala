<?php

use app\components\SearchFilter;
use yii\web\View;
?>
<?php
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
$filter_model = new SearchFilter();
$filter_data = $filter_model->getRecord($model_class);
$title = isset($this->title) ? $this->title : '';
$f_cnt = 0;
if (!empty($filter_data)) {
    ?>
    <div class="modal modal-default fade" id="search_filter" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo Yii::t('app', 'Search') . ' ' . $title; ?></h4>
                </div>
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
                                    'options' => [
                                        'class' => 'popup-form',
                                        'id' => 'import-form',
                                    ]
                        ]);
                        ?>
                        <div class="row margin_0">

                            <div class="modal-body">
                                <?php
                                foreach ($filters as $key => $value) {
                                    $value_array = explode(':', $value);
                                    $value = $value_array[0];
                                    if (in_array($value, array('f_union_code'))) {
                                        if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
                                            $f_cnt++
                                            ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
                                        </div>
                                    <?php } ?>          
                                    <?php
                                    if (in_array($value, array('f_plant_code'))) {
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-f_union_code', 'f_plant_code'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('f_mcc_code'))) {
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-f_plant_code', 'f_mcc_code'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('f_bmc_code'))) {
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-f_mcc_code', 'f_bmc_code'); ?>
                                        </div>
                                    <?php } ?>         
                                    <?php
//                                if ($count > 5 && $f_cnt > 3) {
//                                    echo '<div class="clearfix"></div>';
//                                    $count = 0;
//                                }
                                    ?>

                                    <?php
                                    if (in_array($value, array('f_dcs_code'))) {
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->bmc_society($model, $form, $field_class . '-f_bmc_code', 'f_dcs_code'); ?>         
                                        </div>
                                    <?php } ?>

                                    <?php
//                                if ($count > 5 && $f_cnt > 3) {
//                                    echo '<div class="clearfix"></div>';
//                                    $count = 0;
//                                }
                                    ?>

                                    <?php
                                    if (in_array($value, array('min_date'))) {
                                        $request = Yii::$app->request->queryParams;
                                        $min_date = empty($request["min_date"]) ? '' : $request["min_date"];
                                        $max_date = empty($request["max_date"]) ? '' : $request["max_date"];
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3 height_65">
                                            <div class="form-group">
                                                <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if (in_array($value, array('from_date', 'to_date'))) {
                                        $request = Yii::$app->request->queryParams;
                                        $model->$value = !empty($model->$value) ? $model->$value : date('d-m-Y');
                                        $f_cnt++;
                                        //if(in_array($value,array('from_date'))){
                                            ?>
                                            <!-- <div class="clearfix"></div> -->
                                            <?php
                                        // }
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('shift', 'Shift', 'shift_id', 'shift_code', 'from_shift', 'to_shift'))) {
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3 shift">
                                            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, $value); ?>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if (in_array($value, array('unit'))) {
                                        $f_cnt++
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->controls->unit_conversion($model, [1, 2], 1, 'Select capacity unit'); ?>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if (in_array($value, array('transporter_code'))) {
                                        $f_cnt++;
                                        if (isset($value_array[1]) && $value_array[1] == 'f_union_code') {
                                            ?>
                                            <div class="col-sm-3">
                                                <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, $field_class . '-f_union_code', 'form-group col-sm-2 padding-right-5 padding-left-0'); ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-sm-3">
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
                                            <div class="col-sm-3">
                                                <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, $field_class . '-transporter_code', 'form-group col-sm-3'); ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-sm-3">
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
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->dropdown('transporter_payment_head_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', false, false, 'transporter_payment_head_code'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('customer_code'))) {
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->merge_dcs_customer($model, $form, $field_class . '-f_bmc_code', 'customer_code', FALSE); ?>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if (in_array($value, array('route_code'))) {
                                        $depend_str = $field_class . '-f_plant_code' . ',' . $field_class . '-f_mcc_code' . ',' . $field_class . '-f_bmc_code';
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->all_routes($model, $form, $depend_str, 'route_code', FALSE); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('staff_member_code'))) {
                                        $depend_str = $field_class . '-f_union_code';
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, $depend_str, 'form-group', Yii::t('app', 'Staff Member')); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('config_for'))) {
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->configFor($model, $form, 'config_for', FALSE, false, ['VLC', 'PORTAL']); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('process_name'))) {
                                        $depend_str = $field_class . '-config_for';
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-3">
                                            <?= Yii::$app->dropdown->processName($model, $form, $depend_str, 'process_name', FALSE); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('member_code'))) {
                                        $depend_str = $field_class . '-f_dcs_code';
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-6">
                                            <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, $depend_str, 'form-group', FALSE); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('route'))) {
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-6">
                                            <?= Yii::$app->dropdown->dropdown('route_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', FALSE); ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if (in_array($value, array('bank_code'))) {
                                        $f_cnt++;
                                        ?>
                                        <div class="col-sm-6">
                                            <?= Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-2 padding-right-5'); ?> 

                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="modal-footer mt10 col-sm-12">
                                    <?php if ($f_cnt > 0) { ?>
                                        <?= Yii::$app->controls->search(); ?>
                                    <?php } ?>
                                    <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                </div>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>

                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-search search-filter searchBtn text-right">
        <div class="btn-group btn btn-default modal_toggle"><i class="glyphicon glyphicon-search"></i></div>
    </div>
    <?php
}
$script = '
                                            $(".modal_toggle").on("click", function(){
                                            $("#search_filter").modal("toggle");
                                            });
                                            var checkSearch = "' . $f_cnt . '";
                                            if (checkSearch > 0) {
                                                
                                            } else {
                                                $(".searchBtn") . hide();
                                            }
                                            ';

$this->registerJs($script, View::POS_END, 'search-filter-popup');
?>
