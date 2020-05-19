<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\dynagrid\DynaGrid;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Url;

$disable = true;
?>



<div class="panel-body set_checkbox padding_top_0 tbl_border">
    <div class="panel-asd">

        <?php $form = ActiveForm::begin(['id' => 'tax-detail-form']); ?>
        <!--<div class="clearfix mt15"></div>-->
        <div class="panel-subheading mt15 hide_help_block">
            <div class="row">
                <?php

                function checkRoute() {
                    $baseUrl = Yii::$app->request->baseUrl . '/';
                    $checkUrl = explode('?', str_replace($baseUrl, '', Url::previous()))[0];
                    $checkUrl = Yii::$app->general->base64url_decode($checkUrl);
                    $checkUrl = str_replace('index', 'delete', $checkUrl);
                    return User::canRoute($checkUrl);
                }
                ?>
                <?php Pjax::begin(['id' => 'first_table']); ?>
                <div class="table-responsive">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <thead>
                                <tr>
                                    <th class='width10'></th>
                                    <th class='width10'><?= Yii::t('app', 'Basic Code') ?></th>
                                    <th><?= Yii::t('app', 'Tax Name') ?></th>
                                    <th class='width10'><?= Yii::t('app', 'Tax Value') ?></th>
                                    <th class='width15'><?= Yii::t('app', 'Operation') ?></th>
                                    <th class='width8'></th>
                                </tr>
                            </thead>
                            <?php
                            echo $form->field($taxModel, 'tax_detail_code', ['options' => ['class' => '']])->radioList($data, [
                                'item' => function($index, $label, $name, $checked, $value) use ($disable) {
                                    $data = explode('$', $label);
                                    $delete = ($data[0] == 1 || !$disable) ? '' : ((checkRoute()) ? GhostHtml::a_alert('<span class="glyphicon glyphicon-trash"></span>', ['/dcsaccounting/tbl-tax-detail/delete'], ['title' => 'Delete', 'data-id' => $value, 'class' => 'delete-tax']) : '');
                                    return "<tr><td><input type='radio' {$checked} name='{$name}' value='{$value}' ></td><td>" . $data[0] . "</td><td>" . $data[1] . "</td><td>" . $data[2] . "</td><td class='width15'>" . $data[3] . "</td><td>" . $delete . "</td></tr>";
                                }
                            ])->label(FALSE);
                            ?>
                        </table>
                    </div>

                    <div class="col-sm-6">
                        <table id="child_table" class='table table-bordered table-striped table-main'>
                            <tr id="header-tr">
                                <th class='width15'><?= Yii::t('app', 'Basic Code') ?></th>
                                <th class='width15'><?= Yii::t('app', 'Tax Name') ?></th>
                                <th class='width10'><?= Yii::t('app', 'Tax Value') ?></th>
                                <th class='width15'><?= Yii::t('app', 'Operation') ?></th>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php Pjax::end(); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-sm-12 mt15 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group mt15">
                <?= Html::a(Yii::t('app', 'Tax Test'), 'javascript:void(0)', ['class' => 'btn btn-default btn-create apply-shortcut test-tax', 'data-tax-id' => $searchModel->tax_code, 'shortcut_key' => 'ctrl+alt+c']); ?>
            </div>
        </div>
    </div>
</div>


<?php
//function checkRoute(){
//    if(strpos(Yii::$app->request->referrer,'index')){
//        Url::remember(Yii::$app->request->referrer);
//    }
//    return Url::previous()!='' && User::canRoute(str_replace('index','delete',Url::previous()));
//}

$script = "

    $('#first_table').on('click','input[name=\'TblTaxDepends[tax_detail_code]\']',function(){
        var value = $('input[name=\'TblTaxDepends[tax_detail_code]\']:checked').val();
        $('tr').removeClass('active');
        $('input[name=\'TblTaxDepends[tax_detail_code]\']:checked').closest('tr').addClass('active');

        $.ajax({
            type: 'POST',
             url: '" . Url::to(['/dcsaccounting/tbl-tax-detail/get-tax-depends']) . "',
            data: 'value='+value,
            success: function(data)
            {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'success')
                {
                   var content='';
                   $.each(obj1.data, function( index, value ) {
                        content += '<tr><td class=\'width15\'>'+value.tax_code+'</td><td class=\'width15\'>'+value.tax_name+'</td><td class=\'width10\'>'+value.percentage+'</td><td class=\'width15\'>'+value.operation+'</td></tr>'
                   });
                   $('#child_table tr:not(:first)').remove();
                   $(content).insertAfter('#header-tr');
                }
            }
          });
    });

    $('#first_table').on('click','.delete-tax',function(){
        var id = $(this).attr('data-id');

        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to delete Record?</span></div></div>',
            buttons: {
                'cancel': {
                                label: 'No',
                                className: 'btn btn-default'
                  },
                'confirm': {
                                label: 'Yes',
                                className: 'btn btn-default'
                 }
            },
            callback: function(result) {
                if (result){
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/dcsaccounting/tbl-tax-detail/delete']) . "',    
                        data: 'id='+id,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if(obj1.status=='success'){
                                $.pjax.reload({container: '#first_table'});
                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+obj1.msg+'</span></div></div>');
//                                bootbox.alert(obj1.msg);
                            }else{
                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>'+obj1.msg+'</span></div></div>');
//                                bootbox.alert(obj1.msg);
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
                    });
                }
            },
          });
    });
";
$this->registerJs($script, View::POS_END, 'tax-depends');
?>
