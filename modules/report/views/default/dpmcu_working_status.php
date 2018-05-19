<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '214 - DPMCU Working Status'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search_dpu', ['model' => $model, 'vondorFilter' => true, 'minMaxFilter' => false]); ?>

            <?php
            $attribute = [
                ['attribute' => 'vendor_code', 'filter' => false],
                ['attribute' => 'union_code', 'label' => Yii::t('app', 'Union Code'), 'filter' => false],
                ['attribute' => 'U_Short_Name', 'label' => Yii::t('app', 'U Short Name'), 'filter' => false],
                ['attribute' => 'DCS_Installed', 'filter' => false,'label'=>Yii::t('app', 'DCS Installed')],
                ['attribute' => 'InActiveD_DCS', 'filter' => false,'label'=>Yii::t('app', 'DCS Collection Stopped')],
                ['attribute' => 'Data_Received_Dcs', 'filter' => false,'label'=>Yii::t('app', 'DCS Data Received')],
                ['attribute' => 'Network_Available_Data_Not_Received', 'filter' => false],
                ['attribute' => 'DCS_Under_Maintanance', 'filter' => false,'label'=>Yii::t('app', 'DCS Under Maintanance')],
                ['attribute' => 'No_Network_Non_Functioning',
                    'label'=>'No Network/Not Used',
                    'filter' => false],
            ];
            $grid_option = [
                'id' => 'dpmcu-working-status',
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
    if('" . $model->union_code . "'=='' || '" . $model->union_code . "'=='0'){
        $('#tblmilkcollectionsearch-union_code option:first').after($('<option/>', { 'value': '0','selected':'selected', text: '" . Yii::t('app', 'All') . "'}));  
    }else{
        $('#tblmilkcollectionsearch-union_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
    }
    
    if('" . $model->vendor . "'=='' || '" . $model->vendor . "'=='0'){
        $('#tblmilkcollectionsearch-vendor option:first').after($('<option/>', { 'value': '0','selected':'selected', text: '" . Yii::t('app', 'All') . "'}));  
    }else{
        $('#tblmilkcollectionsearch-vendor option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));  
    }
    
";
$this->registerJs($script, View::POS_READY, 'dep-drop-dcs');
?>