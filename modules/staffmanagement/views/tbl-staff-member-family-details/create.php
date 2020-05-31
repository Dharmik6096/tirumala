<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

//Url::remember();
$url = Url::to(['/staffmanagement/tbl-staff-member-family-details/create', 'id' => $id]);
$this->title = Yii::$app->label->title('create', 'Staff Family Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'action' => $url,
                    'validateOnBlur' => false,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <?=
            $this->render('_form', [
                'model' => $model,
                'form' => $form,
            ])
            ?>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="row">
            <div class="form-grid">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'isaction' => $isaction
                ])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "

     $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-val');
        editFamilyDetail(id);
    });
    
    function editFamilyDetail(staff_family_details_code){
            if(staff_family_details_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/staffmanagement/tbl-staff-member-family-details/update-family']) . "',
                    data: {'staff_family_details_code' : staff_family_details_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblstaffmemberfamilydetails-'+index).val(value);
                        });
                        if(data.modelData.is_nominee==1){
                           $('#tblstaffmemberfamilydetails-is_nominee').prop('checked', true);
                        }else{
                            $('#tblstaffmemberfamilydetails-is_nominee').prop('checked', false);
                        }
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }
    };
    
    calculateAge();
    $('#tblstaffmemberfamilydetails-birth_date').change(function(){
        calculateAge();
    });
    function calculateAge(){
        var b_date = $('#tblstaffmemberfamilydetails-birth_date').val();
        var age = '';
        if(b_date != ''){
            b_date = b_date.split('-');
            var b_year =b_date[2]; 
            var b_month =b_date[1]; 
            var b_day =b_date[0]; 
            var today_date = new Date();
            var today_year = today_date.getYear();
            var today_month = today_date.getMonth();
            var today_day = today_date.getDate();
            age = (today_year + 1900) - b_year;
            if ( today_month < (b_month - 1)) {
                age--;
            }
            if (((b_month - 1) == today_month) && (today_day < b_day)) {
                age--; 
            } 
            if (age > 1900) { 
                age -= 1900; 
            } 
        }
        $('#tblstaffmemberfamilydetails-age').val(age);
    }
    
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>