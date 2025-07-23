<?php
$this->title = Yii::$app->label->title('create', 'Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?><?= ($title != '') ? ' (' . $title . ')' . $header_title : $title . $header_title; ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'dcs_list' => $dcs_list,
            'selected' => $selected,
            'field_name' => $field_name,
            'filters' => $filters,
            'filter_data' => $filter_data,
            'union_code' => $union_code,
            'field_code' => $field_code,
            'model_name' => $model_name,
            'top_section' => $top_section,
            'fields' => $fields,
            'is_union' => $is_union,
            'payment' => $payment,
            'select_from_all' => $select_from_all,
            'shift_type' => $shift_type,
            'ratechart' => $ratechart,
            'main_field_name' => $main_field_name,
            'options' => $options,
            'preload' => $preload,
            'title' => $title,
            'customer_type_wise_entry' => $customer_type_wise_entry,
            'customer_type_field_name' => $customer_type_field_name,
            'customer_type_list' => $customer_type_list,
            'selected_customer_type' => $selected_customer_type,
            'selectedCodes' => $selectedCodes,
            'selectedTypes' => $selectedTypes,
            'hideCustomerType' => $hideCustomerType,
            'check_wef_date' => $check_wef_date,
            'mccList' => $mccList,
            'selectedMccCode' => $selectedMccCode,
            'selectedBmcCode' => $selectedBmcCode,
            'login_type' => $login_type,
            'periodic_applicability' => $periodic_applicability,
            'is_bulk_notification' => $is_bulk_notification,
            'load_data_on_apply_to_checkbox' => $load_data_on_apply_to_checkbox,
            'check_applicability_with_field_name' => $check_applicability_with_field_name
        ])
        ?>
        <div class="row">
            <div class="form-grid">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'fields' => $fields,
                    'actions' => $actions,
                    'script' => $script,
                ])
                ?>
            </div>
        </div>
    </div>
</div>