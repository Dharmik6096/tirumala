<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'family_member_name', 'value' => 'family_member_name', 'filter' => false],
        ['attribute' => 'local_family_member_name', 'value' => 'local_family_member_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'relationship_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationship, 'relationship');
        }, 'filter' => false],
        ['attribute' => 'dob', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->dob);
        }, 'filter' => false],
        ['attribute' => 'gender_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->genderCode, 'gender');
        }, 'filter' => false],
        ['attribute' => 'is_nominee', 'value' => function($model) {
            return ($model->is_nominee == 1) ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'member-family-details-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $options = ['class' => 'edit_family_detail', 'title' => Yii::t('app', 'Edit Family Details'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'data-member_provisional_family_detail_code' => $model->member_provisional_family_detail_code, 'data-val' => $model->member_provisional_family_detail_code, 'class' => 'edit-family-detail'];
            return GhostHtml::a_alert('<i class="fa fa-pencil-alt"></i>', ['/dcsoperation/tbl-member-provisional/get-family-data', 'member_provisional_family_detail_code' => $model->member_provisional_family_detail_code], $options);
        },
        'delete' => ['option' => 'member_provisional_family_detail_code,member_provisional_family_detail_code,/dcsoperation/tbl-member-provisional/delete-family'],
    ]
];

Yii::$app->grid->bind($memberFamilyDataProvider, $memberFamilySearchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.edit-family-detail',function(e){
        var id= $(this).attr('data-val');
        if(id != ''){         
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/dcsoperation/tbl-member-provisional/get-family-data']) . "',
                data: {'id' : id},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#collapse').trigger('click');
                    $.each(data.modelData, function(index, value) {
                        $('#tblmemberprovisionalfamilydetails-'+index).val(value);
                    });
                    var isNomineeValue = data.modelData.is_nominee; 
                    if (isNomineeValue == 1) {
                        $('#tblmemberprovisionalfamilydetails-is_nominee').prop('checked', true);
                    } else {
                        $('#tblmemberprovisionalfamilydetails-is_nominee').prop('checked', false);
                    }
                    $('#tblmemberprovisionalfamilydetails-relationship_code').trigger('change');
                    $('#tblmemberprovisionalfamilydetails-relationship_code').trigger('select2:select');
                    $('#tblmemberprovisionalfamilydetails-relationship_code').trigger('change');
                    $('#tblmemberprovisionalfamilydetails-gender_code').trigger('change');
                    $('#tblmemberprovisionalfamilydetails-gender_code').trigger('select2:select');
                    $('#tblmemberprovisionalfamilydetails-gender_code').trigger('change');
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    $(window).scrollTop(0);

                },
            });
        }

    });
});";
$this->registerJs($script, View::POS_END, 'family-index');
