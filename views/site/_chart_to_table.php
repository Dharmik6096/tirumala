<?php if ($popup != 'allow_popup') { ?>
    <div class="modal fade in" id="chartToTableModal" role="dialog">
        <div class="modal-dialog w750">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title" id="modal-title"><?= $title; ?></h4>
                </div>
                <div class="modal-body" id="modal-body">
                <?php } ?>
                <div class="milk-collection  h450">
                    <div class="table-responsive hide_toolbar_only hide_filters_only">
                        <?php
                        if (!empty($result)) {
                            $attr = [];
                            foreach ($result[0] as $att => $value) {
                                $attr_arr = [];
                                $format = 'raw';
                                if(in_array($att, ['quantity','avg_fat','avg_snf'])){
                                    $format = ['decimal',2];
                                }
                                $attr[] = ['attribute' => $att, 'format' => $format];
                            }
                            $grid_option = [
                                'id' => 'table-popup-list',
                                'attributes' => $attr,
                                'active_column' => false,
                            ];

                            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], false, [], [], false);
                        } else {
                            echo '<p class=\'center_text\'>No Data Available</p>';
                        }
                        ?>

                    </div>
                </div>
                <?php if ($popup != 'allow_popup') { ?> 
                </div>     
            </div>
        </div>
    </div>
<?php } ?>