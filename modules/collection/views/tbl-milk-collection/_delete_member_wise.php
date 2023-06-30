<?php

use demogorgorn\ajax\AjaxSubmitButton;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class="modal modal-default fade" id="MemberDeleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Bulk Delete Milk Collection') ?></h4>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'member-wise-delete-form',
                                ],
                                'action' => Url::to(['/collection/tbl-milk-collection/delete-member-wise'])
                    ]);
                    ?>
                    <div class="row">
                        <div class="grid-search no-effect">
                            <?php
                            $attribute = [
                                ['class' => 'kartik\grid\CheckboxColumn',
                                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                                    'checkboxOptions' => function($model) {
                                        return ['class' => 'checkbox-collection', 'value' => $model['milk_collection_code']];
                                    }],
                                ['attribute' => 'dcs_code',
                                    'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                                    }, 'filter' => false],
                                ['header' => 'Member Code', 'attribute' => 'member_code', 'filter' => false],
                                ['header' => Yii::t('app', 'Member Code Ex'), 'attribute' => 'member_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
                                    }, 'filter' => false],
                                ['header' => Yii::t('app', 'Member'), 'attribute' => 'member_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                                    }, 'filter' => false],
                                ['label' => 'Date', 'attribute' => 'date_time_of_collection',
                                    'filterType' => GridView::FILTER_DATE,
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                            'autoclose' => true]
                                    ],
                                    'value' => function($model) {
                                        return Yii::$app->controls->view_date($model->date_time_of_collection);
                                    }, 'filter' => false],
                                ['attribute' => 'shift_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'sample_no', 'filter' => FALSE],
                                ['attribute' => 'milk_type_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                                        return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
                                    }, 'filter' => FALSE],
                                ['attribute' => 'qty', 'filter' => FALSE],
                                ['attribute' => 'fat', 'filter' => FALSE],
                                ['attribute' => 'snf', 'filter' => FALSE],
                                ['attribute' => 'clr', 'filter' => FALSE],
                                ['attribute' => 'rtpl', 'filter' => FALSE],
                                ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
                            ];

                            $grid_options = [
                                'id' => 'member-wise-delete-form',
                                'attributes' => $attribute,
                                'active_column' => false,
                                'showPageSummary' => false,
                            ];

                            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_options, ['#'], false);
                            ?>
                        </div>

                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            if (!empty($dataProvider->getModels())) {
                                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete-member-collection']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
        $('#MemberDeleteModal .kv-panel-before').hide();$('#MemberDeleteModal .filters').hide();
        $(document).on('click','#delete-member-collection',function(){
            var collectionData = [];
            $('#member-wise-delete-form .checkbox-collection').each(function () {
                if(this.checked){
                    collectionData.push($(this).val());
                }
            });
            var len = collectionData.length;
            if(len == 0){
                bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-danger\"><i class=\"fa fa-times\"></i></div><span>" . Yii::t("app", "Please select at least one Collection.") . "</span></div></div>');
                return false;
            } else {
//            +'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code
                $.ajax({
                    type: 'post',
                    url: '" . Url::to($redirectUrl) . "',
                    data: 'collectionData='+collectionData,
                    success: function(data) {
                         var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            location.reload();
                        }else{
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                        }
                    },
                    error:function(data){
                        //alert('Your data has not been submitted..Please try again');
                    }
                });
//                $('#delete-milk-collection').submit();
            }
        });

";

$this->registerJs($script, View::POS_END, 'panel-before-hide-asdasd');
?>
