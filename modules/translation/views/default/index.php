<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Url;

$this->title = $model['data']['title'];
?>
<?php
echo $this->render('@app/modules/import/views/default/index', ['type' => $model['data']['post']]);
?>
<div class="grid-search-inner clearfix">
    <?php
    if (isset($model['data']['view']) && ($model['data']['view'] == 'tbl-districts' || $model['data']['view'] == 'tbl-sub-districts' || $model['data']['view'] == 'tbl-villages' || $model['data']['view'] == 'tbl-hamlets'))
        echo $this->render('@app/modules/geo/views/' . $model['data']['view'] . '/_search', ['model' => $searchModel, 'actions' => ['index', 'l' => $model['left_column'][0]->param]]);
    ?>
</div>
<div class="state-langauges-form">
    <div class="panel panel-main">
        <div class="panel-heading"><?php echo $model['data']['title']; ?>
            <div class="btn-group pull-right">
                <button id="w3" class="btn dropdown-toggle" title="Export data in selected format" data-toggle="dropdown" aria-expanded="false">
                    <i class="glyphicon glyphicon-export"></i> <i class="glyphicon"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><?php
    $params = http_build_query(array_merge($_GET, ['flag' => 'export', 'type' => 'csv']));
    echo Html::a('<i class="text-primary fa fa-file-code-o"></i> CSV', ['/translation?' . $params])
    ?>
                        <?php //Html::a('<i class="text-primary fa fa-file-code-o"></i> CSV',['/translation','l'=>Yii::$app->getRequest()->getQueryParam('l'),'flag'=>'export','type'=>'csv']) ?></li>


                    <li><?php
                        $params = http_build_query(array_merge($_GET, ['flag' => 'export', 'type' => 'excel']));
                        echo Html::a('<i class="text-primary fa fa-file-code-o"></i> Excel', ['/translation?' . $params])
                        ?>
                        <?php //Html::a('<i class="text-primary fa fa-file-code-o"></i> Excel',['/translation','l'=>Yii::$app->getRequest()->getQueryParam('l'),'flag'=>'export','type'=>'excel']) ?></li>
                </ul>
            </div>
        </div>
        <?php
        $param = (!empty(Yii::$app->request->get('local_fields'))) ? Yii::$app->request->get('local_fields') : 'local_name';

        $localFields = [];
        if (!empty($model['data']['insert_fields'])) {
            $localFields = explode(',', $model['data']['insert_fields']);
            $n = array_map(function($word) {
                return ucfirst(str_replace('_', ' ', $word));
            }, $localFields);
            $localFields = array_combine($localFields, $n);
        }
        if ($localFields) {
            $form = ActiveForm::begin([
                        'options' => [
                            'field-class' => 'form-group col-sm-2 padding-right-5'
                        ],
                        'action' => ['/translation', 'l' => Yii::$app->request->get('l')],
                        'method' => 'get',
            ]);
            ?>
    <?php if ($localFields) { ?>
                <div class="col-md-3">
                    <div style="display: <?= ($localFields) ? 'block' : 'none' ?>">
        <?= Html::dropDownList('local_fields', $param, $localFields, ['class' => 'form-control']) ?>
                    </div>
                </div>
            <?php } ?>

            <?= Yii::$app->controls->search(); ?>

            <?php ActiveForm::end();
        } ?>
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'lang-form'
                    ], 'fieldConfig' => [
                        'labelOptions' => [ 'class' => false],
        ]]);
        ?>

        <div class="inner panel-body">
                <?= Html::hiddenInput('field', $param); ?>
            <div class="panel-subheading">
            <?php echo $form->errorSummary($model['language_local']); ?>
            </div>
<?php if (!empty($model['left_column'])) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-language">
                        <thead>
                            <tr>
                                <th><?php echo $model['data']['grid']; ?></th>
                                <?php
                                $i = 0;
                                foreach ($modelLangauge as $col) {
                                    ?>
                                    <th><?php echo $col->language_name ?></th>
    <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            echo Html::hiddenInput('local_fields', $param);
                            foreach ($dataProvider as $row) {
//                                foreach ($model['left_column'] as $row) {

                                $field = explode(',', $model['data']['fields']);
                                $local_data = \app\modules\translation\Translation::getLanguages($model['language_local'], $field[0], $row->{$field[0]}, $param);
                                ?>
                                <tr>
                                    <td><?php echo $row->{$field[1]}; ?></td>
                                    <?php
                                    $j = 0;
                                    foreach ($modelLangauge as $col) {
                                        $id = '';
                                        $local_name = '';
                                        $val = \app\modules\translation\Translation::ifExist($col->code, $local_data);
                                        if ($val) {
                                            $local_name = $val->{$param};
                                            $id = $val->local_code;
                                        }
                                        ?>
                                        <td>
                                            <?= $form->field($model['language_local'], '[' . $i . '][' . $j . ']' . $param)->textInput(['maxlength' => true, 'value' => $local_name, 'class' => 'form-control '])->label(false) ?>
                                            <?= Html::activeHiddenInput($model['language_local'], '[' . $i . '][' . $j . ']' . $field[0], ['value' => $row->{$field[0]}]) ?>
                                            <?= Html::activeHiddenInput($model['language_local'], '[' . $i . '][' . $j . ']language_code', ['value' => $col->code]) ?>
                                        <?= Html::activeHiddenInput($model['language_local'], '[' . $i . '][' . $j . ']id', ['value' => $id]) ?>
                                        </td>
                                        <?php
                                        $j++;
                                    }
                                    ?>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
<?php } ?>


        </div>

        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">

            <?php
            if (checkRoute())
                Yii::$app->controls->save('Save', $model['language_local']);
            ?>

            <?= Yii::$app->controls->cancel($model['language_local'], $model['action']) ?>
            <?php
            if (checkRoute())
                echo Html::a('import Data', 'javascript:void(0)', ['class' => 'btn btn-default apply-shortcut import-file', 'id' => 'import-file', 'shortcut_key' => 'ctrl+alt+i']);
            ?>
        </div>
<?php ActiveForm::end(); ?>
    </div>

</div>
<?php
$script = "
   $( document ).ready(function() {
            $('div.help-block').remove();
            $('div').removeClass('has-error');

    });
";
$this->registerJs($script, View::POS_END, 'remove-class');

function checkRoute() {
//    if(strpos(Yii::$app->request->referrer,'index')){
//        Url::remember(Yii::$app->request->referrer);
//    }
//    return Url::previous()!='' && User::canRoute(str_replace('index','create',str_replace((Yii::$app->request->getHostInfo().Yii::$app->request->baseUrl.'/'), '',Url::previous() )));

    $baseUrl = Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . '/';
    $URL = str_replace($baseUrl, '', Yii::$app->request->referrer);
    $URL = Yii::$app->general->base64url_decode($URL);
    if (strpos($URL, 'index')) {
        Url::remember(Yii::$app->request->referrer);
    }
    return Url::previous() != '' && User::canRoute(str_replace('index', 'create', $URL));
}
?>
