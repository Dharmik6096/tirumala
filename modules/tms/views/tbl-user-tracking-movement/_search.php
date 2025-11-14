<?php

use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblUserTrackingMovementSearch */
/* @var $form yii\widgets\ActiveForm */

$disable = '';
if ($allUser) {
    $model->tracking_datetime = date('Y-m-d');
    $disable = 'disabled no_pointer_disabled';
}
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ($allUser) ? ['index'] : ['index-other'],
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblusertrackingmovementsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblusertrackingmovementsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->UserList($model, $form, 'tblusertrackingmovementsearch-mcc_plant_code', 'user_code', $model->getAttributeLabel('user_code'), FALSE, FALSE, '/tms/tbl-user-tracking-movement/user-list'); ?>
    </div>
    <div class="col-sm-2 <?= $disable ?>">
        <?= Yii::$app->controls->date($model, $form, 'tracking_datetime', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false); ?>
    </div>
    <div class="col-sm-2 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
if ($allUser) {
    $script = "
    $('#tblusertrackingmovementsearch-user_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        $('#tblusertrackingmovementsearch-user_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));
        if('" . $model->user_code . "' == '0') {
            $('#tblusertrackingmovementsearch-user_code').val(0);
        }
    });
    $('#tblusertrackingmovementsearch-tracking_datetime').prop('readonly', true).on('keydown paste', function(e) {
        e.preventDefault();
    });
";
    $this->registerJs($script, View::POS_READY, 'dep-drop-user-tracking');
}
?>