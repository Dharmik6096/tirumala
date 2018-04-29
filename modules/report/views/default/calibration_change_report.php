<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Calibration Change Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model,'vondorFilter'=>true]); ?>

            <?php
            $attribute = [
                ['attribute' => 'union_code', 'filter' => false],
                ['attribute' => 'union_name', 'filter' => false],
                //['attribute' => 'dtdate', 'filter' => false],
                ['attribute' => 'qty', 'filter' => false],
                ['attribute' => 'avg_fat', 'filter' => false],
                ['attribute' => 'avg_snf', 'filter' => false],
                ['attribute' => 'kg_fat', 'filter' => false],
                ['attribute' => 'kg_snf', 'filter' => false],
                ['attribute' => 'avg_rate', 'filter' => false],
                ['attribute' => 'total_amount', 'filter' => false],
            ];
            $grid_option = [
                'id' => 'calibration-change-report',
                'attributes' => $attribute,
                'active_column' => false,
                    //'actions' => []
            ];
            

            Yii::$app->grid->bind($dataProvider, $model, $grid_option);
            ?>
        </div>

    </div>
</div>

<?php
$script = "
 $('#tblmilkcollectionsearch-dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#tblmilkcollectionsearch-dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->dcs_code . "'=='0'){
        $('#tblmilkcollectionsearch-dcs_code').val(0);      
    }    
});
";
$this->registerJs($script, View::POS_READY, 'dep-drop-dcs');
?>