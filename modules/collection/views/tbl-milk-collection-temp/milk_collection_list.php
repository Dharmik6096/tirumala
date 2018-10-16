<div class="modal fade in popup_modal" id="crossTabDetailsModal" role="dialog">
    <div class="modal-dialog w750 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title">Milk Collection Detail</h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $attribute = [
                            ['header' => 'Member Code', 'attribute' => 'member_code', 'value' => function($model) {
                                    return substr($model->member_code, -4);
                                }, 'filter' => false],
                            ['attribute' => 'member_code', 'label' => 'Member Name', 'value' => function($model) {
                                    return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                                }, 'filter' => false],
                            ['attribute' => 'name', 'filter' => false, 'visible' => false],
                            ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => false],
                            ['attribute' => 'fat', 'filter' => false],
                            ['attribute' => 'snf', 'filter' => false],
                            ['attribute' => 'qty', 'filter' => false],
                            ['attribute' => 'rtpl', 'filter' => false],
                            ['attribute' => 'amount', 'filter' => false],
                            ['attribute' => 'date_time_of_collection', 'filter' => false, 'value' => function($model) {
                                    return Yii::$app->controls->view_date($model->date_time_of_collection);
                                }],
                            ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
                        ];

                        $grid_option = [
                            'id' => 'milk-coll-temp-grid',
                            'attributes' => $attribute,
                            'active_column' => FALSE,
                        ];
                        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['milk-coll-temp-list-grid'], false, [], [], false);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>