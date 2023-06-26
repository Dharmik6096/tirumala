<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Create Identity';
?>

<div class="container" id="login-wrapper">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-identity-form">
                <div class="panel-heading text-center"><h4><?= Yii::t('app', 'EIPL Application') ?></h4></div>
                <div class="panel-body">

                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'identity-form',
                                'options' => [
                                    'autocomplete' => 'off',
                                    'class' => 'clearfix'
                                ],
                                'validateOnBlur' => false,
                                'fieldConfig' => [
                                    'template' => "{input}\n{error}",
                                ],
                            ])
                    ?>

                    <?php echo $form->errorSummary($model); ?>
                    <div class="col-sm-12">
                        <label>Identity</label>
                        <?= $form->field($model, 'identity', ['options' => ['class' => 'form-group']])->dropDownList(['NATIONAL-FEDERATION-2' => 'Federation', 'FEDERATION-UNION-3' => 'Union'], ['prompt' => 'Select Identity']); ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <label>Parent Type</label>
                        <?= $form->field($model, 'parent_type')->textInput(['placeholder' => 'Parent Type', 'autocomplete' => 'off', 'readonly' => true]) ?>

                    </div>

                    <div class="col-sm-6">
                        <label>Parent</label>
                        <?php
                        echo $form->field($model, 'parent_code')->widget(DepDrop::classname(), [
                            'data' => [$model->parent_code => $model->parent_code],
                            'name' => 'fonts',
                            'pluginOptions' => [
                                'depends' => ['identitymaster-identity'],
                                'placeholder' => 'Select Parent Code',
                                'url' => Url::to(['/installation/default/parent-list']),
                                'initialize' => true
                            ]
                        ]);
                        ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <label>Organization Type</label>
                        <?= $form->field($model, 'organization_type')->textInput(['placeholder' => 'Organization Type', 'autocomplete' => 'off', 'readonly' => true]) ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Organization</label>
                        <?php
                        echo $form->field($model, 'organization_code')->widget(DepDrop::classname(), [
                            'data' => [$model->organization_code => $model->organization_code],
                            'name' => 'fonts',
                            'pluginOptions' => [
                                'depends' => ['identitymaster-parent_code'],
                                'placeholder' => 'Select Organization Code',
                                'url' => Url::to(['/installation/default/parent-list']),
                                'initialize' => true,
                            ]
                        ]);
                        ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <label>Sync URL</label>
                        <?= $form->field($model, 'sync_url')->textInput(['placeholder' => 'Sync URL']) ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Own URL</label>
                        <?= $form->field($model, 'own_url')->textInput(['placeholder' => 'Own URL']) ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <label>Username</label>
                        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username']) ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Password</label>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'autocomplete' => 'off']) ?>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-12">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-login' : 'btn btn-login']) ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
     $('#identitymaster-identity').on('change',function(){
        var org = this.value;
        var ary = org.split('-');
        $('#identitymaster-parent_type').val(ary[0]);
        $('#identitymaster-organization_type').val(ary[1]);
    });
";
$this->registerJs($script, View::POS_END, 'delete-manager');
?>