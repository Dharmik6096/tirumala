<?php
$this->title = Yii::$app->label->title('create', 'Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?><?= !empty($title) ? ' (' . $title . ')' . $header_title : ''; ?></div>
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