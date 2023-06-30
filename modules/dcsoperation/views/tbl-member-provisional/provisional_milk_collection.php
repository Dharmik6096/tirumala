<?php

use kartik\detail\DetailView;
?>

<div class="modal fade in popup_modal" id="provisionalMilkCollection" role="dialog">
    <div class="modal-dialog w750 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title">Provisional Milk Collection Detail of member <?= $model->member_name?> (<?= $model->member_code?>) of society <?=Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name')?></h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?=
                                            $this->render('_milk_collection_grid', [
                                                'dataProvider' => $dataProvider,
                                                'searchModel' => $searchModel,

                                            ]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>