<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;
use yii\helpers\Html;
//use yii\jui\DatePicker;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;
use app\modules\dcsoperation\models\TblMember;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;
use softark\duallistbox\DualListbox;

class Controls extends Component {

    private $active_class = 'form-group col-sm-3';

    public function submit() {
        
    }

    public function add($name, $action = 'create') {
        if (is_array($action))
            $url_path = $action;
        else
            $url_path[] = $action;
        $url = Url::to($url_path);
        return GhostHtml::a(Yii::t('app', '<i class="fa fa-plus"></i> Add ' . Yii::t('app', ucfirst($name))), $url, ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']);
    }

    public function save($value, $model, $class = '') {
        echo Html::button(Yii::t('app', $value), ['class' => 'btn btn-primary apply-shortcut ' . $class, 'shortcut_key' => !empty($model->isNewRecord) ? 'ctrl+alt+s' : 'ctrl+alt+u', 'button' => 'save']);
    }

    public function update($id, $action = 'update') {
        //var_dump($action);exit;
        return GhostHtml::a(Yii::t('app', '<i class="fa fa-pencil"></i> Edit'), Url::to([$action, 'id' => $id]), ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+e']);
    }

    public function update_combo($id, $action = 'update', $params) {
        //var_dump($params);exit;
        echo GhostHtml::a(Yii::t('app', 'Edit'), Url::to([$action . '?' . $params]), ['class' => 'apply-shortcut', 'shortcut_key' => 'ctrl+alt+e']);
    }

    public function reset() {
        echo Html::resetButton(Yii::t('app', 'reset'), ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+r']);
    }

    public function cancel($model = '', $action = 'index', $params = null, $backArrow = false) {
        // if (!$model->isNewRecord) {
        $label = 'cancel';
        $class = 'btn btn-danger apply-shortcut';
        if (Yii::$app->controller->action->id == 'view' || Yii::$app->controller->action->id == 'rate-chart' || $backArrow) {
            $class = 'btn btn-primary apply-shortcut';
            $label = '<i class="fa fa-arrow-left"></i>';
            $tooltip = 'Back To List';
        }
        echo Html::a($label, Url::previous(), ['class' => $class, 'shortcut_key' => 'ctrl+alt+c']);
        // }
    }

    public function custombutton($name, $action = 'create', $sideButton = false, $class = '', $icon = '', $target = '_self') {
        if (is_array($action))
            $url_path = $action;
        else
            $url_path[] = $action;
        $url = Url::to(array_values($url_path));
        $sideclass = $sideButton ? 'btn-block' : '';
        $class = empty($class) ? '' : $class . ' ';
        //$url=str_replace('1%5B', '%5B', $url);
        return GhostHtml::a($icon . Yii::t('app', ucfirst($name)), $url, ['target' => $target, 'class' => 'btn btn-danger apply-shortcut ' . $class . $sideclass, 'shortcut_key' => 'ctrl+alt+c']);
    }

    public function search() {
        echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default']);
    }

    public function active($model, $form, $class = 'form-group col-sm-3') {
        echo $form->field($model, 'is_active', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox();
    }

    public function import($flag, $view, $text = '', $fields = [], $appendId = '', $urlPermission = '') {
        if ($flag == 'member_limited')
            $model = new TblMember();
        else
            $model = '';
        // $url = str_replace('index', 'create', Yii::$app->request->url);
        $baseurl = Yii::$app->request->baseUrl . '/';
        $checkUrl = str_replace($baseurl, '', Yii::$app->request->url);
        $checkUrl = Yii::$app->general->base64url_decode($checkUrl);
        $url = !empty($urlPermission) ? $urlPermission : str_replace('index', 'create', $checkUrl);
        $btnText = !empty($text) ? $text : 'Import Data';
        if (User::canRoute($url)) {
            $check = \app\modules\import\importData::getLabels($flag);
            echo $view->render('@app/modules/import/views/default/index', ['type' => $flag, 'model' => $model, 'appendId' => $appendId]);
            if (empty($check['mapping'])) {
                return Html::a(Yii::t('app', '<i class="fa fa-download"></i> ' . $btnText), 'javascript:void(0)', ['class' => 'btn btn-danger btn-block apply-shortcut import-file' . $appendId, 'data-map-flag' => 0, 'id' => 'import-file' . $appendId, 'shortcut_key' => 'ctrl+alt+i']);
            } else {
                $mappField = explode(',', $check['mapping_fields']);
                $code = '<a href="" class="btn btn-danger btn-block dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i> Import Data <span class="caret"></span></a>
                <ul class="dropdown-menu"><li>' . Html::a(Yii::t('app', 'Master Import'), 'javascript:void(0)', ['data-map-flag' => 0, 'class' => 'import-file']) . '</li>
                    <li>' . Html::a(Yii::t('app', 'Mapping Import'), 'javascript:void(0)', ['data-map-flag' => 1, 'class' => 'import-file']) . '</li></ul>';

                return $code;
            }
        }
    }

    public function local($model, $form, $field = 'local_name') {
        echo $form->field($model, $field)->textInput(['maxlength' => true, 'class' => 'form-control local-control']);
    }

    public function local_textarea($model, $form, $field = 'local_address') {
        echo $form->field($model, $field)->textArea(['maxlength' => true, 'class' => 'form-control local-control']);
    }

    public function date($model, $form, $name = 'date', $class = 'form-group col-sm-2', $maxdate = true, $mindate = false, $disabled = false, $label = true, $id = false, $max_val = '', $monthYearOnly = false) {
        $maxdate_value = '';
        $mindate_value = '';
        $options = ['class' => 'form-control'];
        if ($id !== false) {
            $options['id'] = $id;
        }
        if ($maxdate)
            $maxdate_value = !empty($max_val) ? Yii::$app->controls->view_date($max_val) : date('d-m-Y');

        if ($mindate)
            $mindate_value = Yii::$app->controls->view_date($mindate);

        if (isset($model->{$name}))
            $model->{$name} = Yii::$app->formatter->asDate($model->{$name}, 'php:d-m-Y');

        $control = $form->field($model, $name)->widget(DatePicker::className(), [
            'model' => $model,
            'attribute' => $name,
            'value' => date('Y-m-d'),
//            'convertFormat'=>TRUE,
            'pluginOptions' => [
                'format' => $monthYearOnly ? 'mm-yyyy' : 'dd-mm-yyyy',
                'startView' => $monthYearOnly ? 'months' : '',
                'minViewMode' => $monthYearOnly ? 'months' : '',
                'todayHighlight' => true,
                'autoclose' => true,
                'endDate' => $maxdate_value,
                'startDate' => $mindate_value
            ],
            'disabled' => $disabled,
            'options' => $options
        ]);
        if (!$label) {
            $control = $control->label(false);
        }
        return $control;
    }

    public function valid_date($model, $form, $name = 'date', $class = 'form-group col-sm-2', $maxdate = '', $mindate = '', $disabled = false) {
        $maxdate_value = '';
        $mindate_value = '';
        $mindate == '' ? $mindate_value = date('d-m-Y') : $mindate_value = $mindate;
        if ($maxdate)
            $maxdate_value = Yii::$app->controls->view_date($mindate);
        if (isset($model->{$name}))
            $model->{$name} = Yii::$app->formatter->asDate($model->{$name}, 'php:d-m-Y');
        return $form->field($model, $name)->widget(DatePicker::className(), [
                    'model' => $model,
                    'attribute' => $name,
                    'value' => date('Y-m-d'),
//            'convertFormat'=>TRUE,
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'endDate' => $maxdate_value,
                        'startDate' => $mindate_value
                    ],
                    'disabled' => $disabled,
                    'options' => ['class' => 'form-control']
        ]);
    }

    public function search_date($model, $name) {
        return DatePicker::widget([
                    'model' => $model,
                    'attribute' => $name,
                    'dateFormat' => 'dd-MM-yyyy',
                    'options' => ['class' => 'form-control', 'readonly' => false]
        ]);
    }

    public function view_date($value, $format = 'php:d-m-Y') {
        return ($value == NULL || $value == '') ? '' : Yii::$app->formatter->asDatetime($value . Yii::$app->getTimeZone(), $format);
    }

    public function daterange($name, $value = '') {
        return DateRangePicker::widget([
                    'name' => $name,
                    'value' => $value,
                    'convertFormat' => true,
                    'autoUpdateOnInit' => false,
                    'initRangeExpr' => false,
                    'options' => ['placeholder' => 'Select date range', 'class' => 'form-control'],
                    'pluginOptions' => [
                        'startDate' => "moment().startOf('day').subtract(1,'days')",
                        'endDate' => "moment()",
                        'autoUpdateInput' => true,
                        'locale' => [
                            'format' => 'd-m-Y',
                            'separator' => ' to ',
                        ],
                        /* 'ranges'=>[
                          "Today" => ["moment().startOf('day')", "moment()"],
                          "Yesterday" => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                          "Last 7 Days"=> ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                          "Last 30 Days"=> ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                          "This Month" => ["moment().startOf('month')", "moment().endOf('month')"],
                          "Last Month" => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                          ],
                          'ranges'=>[
                          'Today'=> ["moment().startOf('day')", "moment()"],
                          'Tomorrow' => ["moment().startOf('day').add(1, 'days')","moment().startOf('day').add(1, 'days')"],
                          'Next 7 Days' => ["moment()","moment().startOf('day').add(6, 'days')"],
                          'Next 30 Days' => ["moment()","moment().startOf('day').add(29, 'days')"],
                          'This Month' => ["moment().startOf('month')", "moment().endOf('month')"],
                          'Next Month' => ["moment().add(1, 'month').startOf('month')", "moment().add(1, 'month').endOf('month')"],

                          ], */
                        'opens' => 'left'
                    ],
                    'pluginEvents' => [
                        'hide.daterangepicker' => "function(ev, picker) {
                                    //var p=$(this);
                                //var st=$('input[name=\"daterangepicker_start\"]').val();
                                //var end=$('input[name=\"daterangepicker_end\"]').val();
                                //p.val(st+' to '+end);
                                }"
                    ],
        ]);
    }

    public function min_max_date($minName, $maxName, $minValue, $maxValue) {
        $layout = "
            {input1}
            {separator}
            {input2}
            <span class=\"input-group-addon kv-date-remove\">
                <i class=\"glyphicon glyphicon-remove\"></i>
            </span>";

        return DatePicker::widget([
                    'name' => $minName,
                    'value' => $minValue,
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => $maxName,
                    'value2' => $maxValue,
                    'layout' => $layout,
                    'options' => ['placeholder' => 'From Date'],
                    'options2' => ['placeholder' => 'To Date'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
        ]);
    }

    public function active_min_max_date($form, $model, $minName, $maxName, $id1 = false, $id2 = false) {
        $layout = "
            {input1}
            {separator}
            {input2}
            <span class=\"input-group-addon kv-date-remove\">
                <i class=\"glyphicon glyphicon-remove\"></i>
            </span>";
        $options = ['placeholder' => 'From date'];
        $options2 = ['placeholder' => 'To date'];
        if ($id1 !== false) {
            $options['id'] = $id1;
        }
        if ($id2 !== false) {
            $options2['id'] = $id2;
        }
        return DatePicker::widget([
                    'model' => $model,
                    'attribute' => $minName,
                    'attribute2' => $maxName,
                    'options' => $options,
                    'options2' => $options2,
                    'type' => DatePicker::TYPE_RANGE,
                    'form' => $form,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
        ]);
    }

    public function unit_conversion($model, $units = [], $default = '', $prompt = 'Select Unit') {
        $unit_list = \app\modules\globalmaster\models\TblUnits::find(['unit_code' => $units])->all();
        $unit_list = \yii\helpers\ArrayHelper::map($unit_list, 'unit_code', 'unit_name');
        echo '<div class="form-group">' . Html::activeDropDownList($model, 'unit', $unit_list, ['class' => 'form-control', 'prompt' => $prompt]) . '</div>';
    }

    public function view_attachment($model, $attachment, $path) {
        $path = Yii::$app->params[$path] . $attachment;
        return Html::a($model->attachment, Url::to($path), ['target' => '_blank', 'data-pjax' => "0"]);
    }

    public function dualList($form, $model, $attribute, $items) {
        $options = [
            'multiple' => true,
            'size' => 20,
        ];
        // echo Html::activeListBox($model, $attribute, $items, $options);
        return $form->field($model, $attribute)->widget(DualListbox::className(), [
                    'items' => $items,
                    'options' => $options,
                    'clientOptions' => [
                        'moveOnSelect' => false,
                        'selectedListLabel' => 'Selected Items',
                        'nonSelectedListLabel' => 'Available Items',
                    ],
        ]);
    }

    public function view_datetime($value, $formate = 'php:d-m-Y H:i') {
        return ($value == NULL || $value == '') ? '' : Yii::$app->formatter->asDatetime($value . Yii::$app->getTimeZone(), $formate);
    }

    public function view_time($value, $formate = 'php:H:i') {
        return ($value == NULL || $value == '') ? '' : Yii::$app->formatter->asDatetime($value . Yii::$app->getTimeZone(), $formate);
    }

    public function save_datetime($value, $formate = 'php:Y-m-d H:i:s') {
        return ($value == NULL || $value == '') ? NULL : Yii::$app->formatter->asDatetime($value . Yii::$app->getTimeZone(), $formate);
    }

    public function view_month($value) {
        return ($value == NULL || $value == '') ? '' : Yii::$app->formatter->asDatetime($value . Yii::$app->getTimeZone(), 'php:m-Y');
    }

    public function weekday_list($model, $form) {
        return $form->field($model, 'week_days')->checkboxList(
                        ['Sun' => 'S', 'Mon' => 'M', 'Tue' => 'T', 'Wed' => 'W', 'Thu' => 'T', 'Fri' => 'F', 'Sat' => 'S'], [
                    'id' => 'day-list',
                    'class' => 'days-container',
                    'item' =>
                    function ($index, $label, $name, $checked, $value) {
                        return Html::checkbox($name, $checked, [
                                    'value' => $value,
                                    'id' => $value,
                                ]) . '<label for=' . $value . '>' . $label . '</label>';
                    },]);
    }

    function openInGoogleMaps($address, $latlong) {
        if (!empty($address) && !empty($latlong)) {
            return "<a href='https://www.google.com/maps/search/?api=1&query=" . urlencode($latlong) . "' target='_blank'>" . $address . "</a>";
        }
    }

    public function calculateTimeDifference($inTime, $outTime, $array = false) {
        $hours = $minutes = 00;
        $in_time = Yii::$app->controls->view_time($inTime);
        $out_time = Yii::$app->controls->view_time($outTime);
        if (!empty($in_time) && !empty($out_time) && ($in_time < $out_time)) {
            $timeDifference = strtotime($out_time) - strtotime($in_time);
            $hours = floor($timeDifference / 3600);
            $minutes = floor(($timeDifference % 3600) / 60);
        }
        return $array ? ['hours' => $hours, 'minutes' => $minutes] : "$hours Hours $minutes Minutes";
    }

}
