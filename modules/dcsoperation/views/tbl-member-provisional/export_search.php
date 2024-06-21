<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;


$this->title = Yii::t('app', Yii::$app->label->title('list', 'Provisional Member Approval'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <div class="pt5 large-search">
                <?php
                $form = ActiveForm::begin([
                    'action' => ['export-provisional-member-bank-receipt'],
                    'method' => 'post',
                    'id' => 'export-provisional-member'
                ]);
                ?>
                <div class="col-sm-2" id="union">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberprovisionalsearch-union_code', 'plant_code', Yii::t('app', 'Plant')); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberprovisionalsearch-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC')); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberprovisionalsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC')); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmemberprovisionalsearch-bmc_code', 'dcs_code', Yii::t('app', 'Society Code')); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->date($model, $form, 'as_on_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, Yii::t('app', 'As On Date')); ?>
                </div>
                <div class="col-sm-2">
                    <div class="form-group mt-21">
                        <label class="control-label"></label>
                        <?php //Yii::$app->controls->search(); ?>
                        <?php echo Html::button(Yii::t('app', 'Generate'), ['class' => 'btn btn-primary ', 'id' => 'generate']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
$(document).ready(function(){
    $('#generate').click(function(){
        $('#export-provisional-member').submit();
    })
});";

$this->registerJs($script, View::POS_END, 'export-provisional-member');
?>