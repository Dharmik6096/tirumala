<?php
if ($popup != 'allow_popup') {
    $fixed_header = false;
    ?>
    <div class="modal fade in" id="chartToTableModal" role="dialog">
        <div class="modal-dialog w750">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title" id="modal-title"><?= $title; ?></h4>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="milk-collection hide_overflow h450">
                        <div class="table-responsive hide_overflow hide_toolbar_only hide_filters_only">
                            <?php
                            if (!empty($result)) {
                                $attr = [];
                                foreach ($result[0] as $att => $value) {
                                    $attr_arr = [];
                                    $format = 'raw';
                                    if (in_array($att, ['quantity', 'avg_fat', 'avg_snf'])) {
                                        $format = ['decimal', 2];
                                    }
                                    $attr[] = ['attribute' => $att, 'format' => $format, 'filter' => false];
                                }
                                $grid_option = [
                                    'id' => 'table-popup-list',
                                    'attributes' => $attr,
                                    'active_column' => false,
                                ];

                                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], false, [], [], $fixed_header);
                            } else {
                                echo '<p class=\'center_text\'>No Data Available</p>';
                            }
                            ?>

                        </div>
                    </div>
                </div>     
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="table-responsive h450 height_div">
        <table class="table table-striped">
            <?php if (!empty($result)) { ?>
                <thead>
                    <tr>
                        <?php foreach ($result[0] as $header => $value) { ?>
                            <th><?= Yii::t('app', $header) ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <?php
                foreach ($result as $data) {
                    echo "<tr>";
                    foreach ($data as $key => $value) {
                        ?>
                        <td class="auto_width min_width_100"><?= $value ?></td>
                    <?php
                    }
                    echo "</tr>";
                }
            } else {
                ?>
                <tr><td colspan="20">No Data Available.</td></tr>
    <?php }
    ?>
        </table>
    </div>
<?php } ?>
