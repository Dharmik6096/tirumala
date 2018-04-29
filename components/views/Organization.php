<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
use yii\widgets\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
?>

<!--Set Language Modal starts-->
<div class="modal modal-default fade" id="OrganizationConfirmModal" role="dialog"  data-backdrop="static"
     data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h4 class="modal-title">Organization Selection</h4>
            </div>            
            <?php
//            $languageModel = new \app\models\TblStatesLocal();
            $form = ActiveForm::begin(['options' => [
                            'validateOnBlur' => true,
                            'class' => 'popup-form',
                            'id' => 'language-form',
                            'enableAjaxValidation' => false,
                        ], 'fieldConfig' => [
                        //'labelOptions' => [ 'class' => false],
            ]]);

            //echo \yii\helpers\StringHelper::basename(get_class($languageModel));
            ?>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-sm-5">Select Organization</label>
                        <div class="col-sm-7 field-tblstateslocal-0-local_name">
                            <?php
                            $i = 0;
                            echo Html::hiddenInput('mode', 'create', ['id' => 'mode']);
                            ?>
                            <?php
                            echo Html::dropDownList('organizations', null, $array, [
                                'class' => 'form-control',
                               
                            ]);
                            ?>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="modal-footer">
                <?php
                AjaxSubmitButton::begin([

                    'label' => 'Save',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => \yii\helpers\Url::to(['/site/set-organization/']),
                        'success' => new \yii\web\JsExpression('function(data){

                                                    if(data=="success")
                                                    {
                                                        $("#OrganizationConfirmModal").modal("toggle");
                                                         window.location="' . Yii::$app->request->baseUrl . '/index.php?r=site/screen2";
                                                    }

                                                }'),
                    ],
                    'options' => ['class' => 'btn btn-default btn-raised',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
            <?php echo Html::a('Logout', ['/user-management/auth/logout'], ['class' => 'btn btn-default']); ?>
            </div>
<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
