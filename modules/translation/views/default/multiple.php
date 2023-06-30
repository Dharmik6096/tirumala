<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Url;

$this->title = ucfirst(Yii::$app->getRequest()->getQueryParam('l')) . ' Translation';
?>
<?php
//echo $this->render('@app/modules/import/views/default/index', ['type' => $model['data']['post']]);
?>
<!--<div class="state-langauges-form">-->
<div class="panel panel-main">
    <div class="panel-heading">
        <?php echo ucfirst(Yii::$app->getRequest()->getQueryParam('l')); ?>
        Name
        <div class="btn-group pull-right">
            <button id="w3" class="btn dropdown-toggle" title="Export data in selected format" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="glyphicon glyphicon-export"></i> <i class="glyphicon"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><?php
                    $params = http_build_query(array_merge($_GET, ['flag' => 'export', 'type' => 'csv']));
                    echo Html::a('<i class="text-primary fa fa-file-code-o"></i> CSV', ['/translation/default/multiple?' . $params])
                    ?>
                    <?php //Html::a('<i class="text-primary fa fa-file-code-o"></i> CSV',['/translation','l'=>Yii::$app->getRequest()->getQueryParam('l'),'flag'=>'export','type'=>'csv'])  ?></li>


                <li><?php
                    $params = http_build_query(array_merge($_GET, ['flag' => 'export', 'type' => 'excel']));
                    echo Html::a('<i class="text-primary fa fa-file-code-o"></i> Excel', ['/translation/default/multiple?' . $params])
                    ?>
                    <?php //Html::a('<i class="text-primary fa fa-file-code-o"></i> Excel',['/translation','l'=>Yii::$app->getRequest()->getQueryParam('l'),'flag'=>'export','type'=>'excel'])  ?></li>
            </ul>
        </div>
    </div>

    <?php
    $form = ActiveForm::begin(['options' => [
                    'class' => 'lang-form',
                    'field-class' => 'form-group col-sm-2 padding-right-0',
                ], 'fieldConfig' => [
                    'labelOptions' => [ 'class' => false],
    ]]);
    ?>

    <div class="inner panel-body multiple-panel">
        <div class="panel-subheading">
            <?php echo $form->errorSummary($model['language_local']); ?>
            <div class="row">
                <?php
                $dd = explode(',', $model['dd']);

                foreach ($dd as $key => $d) {
                    if ($key == 0)
                        Yii::$app->dropdown->{$d}($model['language_local'], $form, $dd[$key] . '_code');
                    else
                        Yii::$app->dropdown->{$d}($model['language_local'], $form, strtolower($model['data']['post']) . '-' . $dd[$key - 1] . '_code', $dd[$key] . '_code');
                }
                ?>

                <div class="col-md-3">
                    <?= $form->field($model['language_local'], 'language_code')->dropDownList(ArrayHelper::map(ArrayHelper::toArray($modelLangauge), 'code', 'language_name'), ['prompt' => 'Select Language'])->label(false); ?>
                </div>

                <div class="clearfix"></div>

                <?php
                $fields = explode(',', $model['multiple']);
                foreach ($fields as $value) {
                    ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?= $form->field($model['language_local'], $value)->textInput(['maxlength' => true, 'class' => 'form-control ', 'id' => $value]) ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <?= Html::activeHiddenInput($model['language_local'], 'local_code', ['id' => 'local_code']) ?>
            </div>
        </div>
    </div>

    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
        <?php
        if (checkRoute()) {
            Yii::$app->controls->save('Save', $model['language_local']);
        }
        ?>
        <?= Yii::$app->controls->cancel($model['language_local'], $model['action']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!--</div>-->

<?php

function checkRoute() {
    $baseUrl = Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . '/';
    $URL = str_replace($baseUrl, '', Yii::$app->request->referrer);
    $URL = Yii::$app->general->base64url_decode($URL);
    return Yii::$app->request->referrer != '' && User::canRoute(str_replace('index', 'create', $URL));
    //return Yii::$app->request->referrer != '' && User::canRoute(str_replace('index', 'create', str_replace((Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . '/'), '', Yii::$app->request->referrer)));
}

$script = "
   $( document ).ready(function() {
            $('div.help-block').remove();
            $('div').removeClass('has-error');

    });
";
$this->registerJs($script, View::POS_END, 'remove-class');
$m = strtolower($model['data']['post']);
$script = "
    $('#" . $m . "-language_code').on('change',function(){
            var code= $('#" . $m . "-" . $model['data']['fields'] . "').val();
            var language = $(this).val();

            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/translation/default/already-data']) . "',
                        data: {'code':'" . $model['data']['fields'] . "-'+code,'language_code':language,'model':'" . $model['data']['post'] . "','data_fields':'" . $model['data']['insert_fields'] . "'},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            var str='" . $model['data']['insert_fields'] . "';
                            var fld=str.split(',');
                            $.each(fld,function (index, value) {
                                $('#'+value).val(obj1[value]);
                            });
                            $('#local_code').val(obj1['local_code']);
                        },
                        error:function(data){

                                }
            });
            //var strLength= $('#tblhamlets-hamlet_code').val().length;
            //$('#tblhamlets-hamlet_code').focus();
           // $('#tblhamlets-hamlet_code')[0].setSelectionRange(strLength, strLength);
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>