<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'upload-sap-member',
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    echo Html::hiddenInput('operation', 'upload', ['id' => 'set_operation']);
    $attribute = [
            ['attribute' => 'application_no', 'label' => 'Application No', 'filter' => FALSE],
            ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'filter' => FALSE],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'filter' => FALSE],
            ['attribute' => 'registration_date', 'filter' => false],
            ['attribute' => 'member_name', 'filter' => FALSE],
            ['attribute' => 'gender_code', 'filter' => FALSE],
            ['attribute' => 'mobile_no', 'filter' => FALSE],
            ['attribute' => 'email', 'filter' => FALSE],
            ['attribute' => 'address', 'filter' => FALSE],
            ['attribute' => 'post_office', 'filter' => FALSE],
            ['attribute' => 'pincode', 'filter' => FALSE],
            ['attribute' => 'age', 'filter' => FALSE],
            ['attribute' => 'dob', 'value' => function($model) {
                return Yii::$app->general->decryptData($model['dob']) !== FALSE ? Yii::$app->general->decryptData($model['dob']) : $model['dob'];
            }, 'filter' => FALSE],
            ['attribute' => 'qualification_name', 'filter' => FALSE],
            ['attribute' => 'adhar_no', 'value' => function($model) {
                return Yii::$app->general->decryptData($model['adhar_no']) !== FALSE ? Yii::$app->general->decryptData($model['adhar_no']) : $model['adhar_no'];
            }, 'filter' => FALSE],
            ['attribute' => 'family_member_name', 'filter' => FALSE],
            ['attribute' => 'relationship', 'filter' => FALSE],
            ['attribute' => 'nominee_address', 'filter' => FALSE],
            ['attribute' => 'guardian_name', 'filter' => FALSE],
            ['attribute' => 'state', 'filter' => FALSE],
            ['attribute' => 'district', 'filter' => FALSE],
            ['attribute' => 'village', 'filter' => FALSE],
            ['attribute' => 'member_class', 'filter' => FALSE],
            ['attribute' => 'created_by', 'filter' => FALSE],
            ['attribute' => 'nominee_age', 'filter' => FALSE],
            ['attribute' => 'receipt_scan_copy', 'filter' => FALSE],
            ['attribute' => 'Desicow_H', 'filter' => FALSE],
            ['attribute' => 'Desicow_M', 'filter' => FALSE],
            ['attribute' => 'Desicow_D', 'filter' => FALSE],
            ['attribute' => 'Croscow_H', 'filter' => FALSE],
            ['attribute' => 'Croscow_M', 'filter' => FALSE],
            ['attribute' => 'Croscow_D', 'filter' => FALSE],
            ['attribute' => 'Buff_H', 'filter' => FALSE],
            ['attribute' => 'Buff_M', 'filter' => FALSE],
            ['attribute' => 'Buff_D', 'filter' => FALSE],
            ['attribute' => 'Total_H', 'filter' => FALSE],
            ['attribute' => 'Total_M', 'filter' => FALSE],
            ['attribute' => 'Total_D', 'filter' => FALSE],
            ['attribute' => 'daily_milk_total', 'filter' => FALSE],
            ['attribute' => 'home_consumption_milk', 'filter' => FALSE],
            ['attribute' => 'Markt_lpd', 'filter' => FALSE],
            ['attribute' => 'amount_deposit', 'filter' => FALSE],
            ['attribute' => 'deposit_date', 'filter' => false],
            ['attribute' => 'ifsc', 'filter' => FALSE],
            ['attribute' => 'bank_account_no', 'filter' => FALSE],
            ['attribute' => 'bank_name', 'filter' => FALSE],
            ['attribute' => 'branch_name', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'sap-upload-member-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<div class="col-sm-12 margin-top-10 form-group mb-2" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Upload File'), ['class' => 'btn btn-primary btn-login', 'id' => 'upload', 'value' => 'upload', 'name' => 'upload']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>
<div id='member_detail'></div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#upload").click(function() {
        $("#upload-sap-member").submit();
    });
      ';
$this->registerJs($script, View::POS_END, 'upload-sap-member');
?>