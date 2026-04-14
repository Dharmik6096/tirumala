<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\usermanagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : 'All Reports - List');
$inclass = !empty($result) ? '' : 'in';
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
$defaultToggle = true;

$disableCopyClass = '';
if (!empty($data['config'])) {
    $config = is_object($data['config']) ? (array) $data['config'] : $data['config'];
    if (!empty($config) && !empty($config['excel_readonly'])) {
        $disableCopyClass = 'disable-copy-class hide-grid-export';
    }
}
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="report-area  <?= $disableCopyClass ?>">
            <div class="modal modal-default fade" id="dynamic_report_search_filter" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $title; ?></h4>
                        </div>
                        <div class="">
                            <div id='dynamic-form-div'>
                                <?php if (empty($data)) { ?>
                                    <?php
                                    $form = ActiveForm::begin(['options' => [
                                                    'id' => 'dynamicreport-form',
                                                    'field-class' => 'form-group col-sm-6'
                                                ],
                                                'method' => 'GET',
                                                'validateOnBlur' => FALSE,
//                                                'validateOnEnter' => TRUE,
                                                'validateOnChange' => FALSE,
                                                'enableClientValidation' => true,
                                                'validateOnSubmit' => true,
                                    ]);
                                    ?>  
                                    <div class="row margin_0">

                                        <div class="modal-body">
                                            <div class="col-sm-12">
                                                <?= Yii::$app->dropdown->dropdown('report_code', $model, $form, '', 'Report Name'); ?>
                                            </div>
                                        </div>

                                        <div class="modal-footer mt10 col-sm-12">
                                            <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                                        </div>
                                    </div>
                                    <?php
                                    ActiveForm::end();
                                } else {
                                    echo $this->render('@app/modules/dynamicreport/views/default/_form', ['model' => $model, 'data' => $data]);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <?php
            $class = 'beforeGridLoad';
            if (!empty($result)) {
                $defaultToggle = false;
                if (!(isset($data['download_only'])) && is_array($result)) {
                    $class = '';
                }
            }
            if (!empty($model->getErrors())) {
                $defaultToggle = true;
            }
            ?>

            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?>">
                <div class="btn-group btn btn-default dynamic_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>




            <div id='dynamic-report-grid'>
                <?php if (!empty($result)) { ?>
                    <!--                    <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                                            <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                                        </div>-->
                <?php } ?>
                <?php
                if (!empty($result) && !is_array($result)) {
                    echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
                } else if (!empty($result)) {
                    $attr = [];
                    foreach ($result[0] as $att => $value) {
                        $attr_arr = [];
                        $attr_arr['value'] = function($model) use ($att) {
                            return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
                        };
                        $attr_arr['attribute'] = $att;
                        $attr[] = $attr_arr;
                    }
                    $grid_option = [
                        'id' => 'dynamic-report-list',
                        'attributes' => $attr,
                        'active_column' => false,
                    ];

                    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], false);
                }
                ?>
            </div>
        </div>
    </div>
</div>       

<?php
$script = "$(document).ready(function(){
     function BindDynamicForm(id){   
      $('#pageloader').show();
      $('#loadercontent').show();
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/dynamicreport/default/generate-form']) . "',
                data: {'id' : id},             
                success: function(data) {
                 $('#dynamic-form-div').html(data);
                 $('#dynamic-report-grid').html(''); 
                 $('#loadercontent').hide();
                 $('#pageloader').hide();
                },
                error: function(data) {  
                 $('#loadercontent').hide();
                 $('#pageloader').hide();
                }
            });
        }
     $(document).on('change','#dynamicform-report_code',function(e){
    BindDynamicForm($(this).val())
    });
 
});

$('.dynamic_report_modal_toggle').on('click', function(){
    $('#dynamic_report_search_filter').modal('toggle');
});
";
if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#dynamic_report_search_filter').modal('toggle');
        });
    ";
}
$this->registerJs($script, View::POS_END, 'dynamic-form-script');
?>