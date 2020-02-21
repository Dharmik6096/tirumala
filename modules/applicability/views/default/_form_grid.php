<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use yii\web\View;
?>

<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$column = [];
foreach ($fields as $key => $f) {
    if (in_array('grid', $f['view'])) {
        $value = empty($f['value']) ? $key : $f['value'];
        $attribute = ['attribute' => $key, 'value' => $value];
        if (!empty($f['type']) && $f['type'] == 'date') {
            $attribute['filterType'] = GridView::FILTER_DATE;
            $attribute['filterWidgetOptions'] = ['pluginOptions' =>
                    ['format' => 'dd-mm-yyyy', 'autoclose' => true]
            ];
        }
        if (!empty($f['type']) && $f['type'] == 'yes-no') {
            $attribute['filter'] = array('1' => 'Yes', '0' => 'No');
        }
        if (!empty($f['format'])) {
            $attribute['format'] = $f['format'];
        }
    }
    array_push($column, $attribute);
}


$grid_option = [
    'id' => 'applicability-grid',
    'attributes' => $column,
    'active_column' => FALSE
];

if ($actions != FALSE) {
    $grid_option['actions'] = $actions;
}

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);

if ($script != FALSE) {
    $this->registerJs($script, View::POS_END, 'app-script');
}
?>