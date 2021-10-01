<div class="modal fade in popup_modal" id="viewHistoryPopupModal" role="dialog">
    <div class="modal-dialog width_100-50 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title">History Detail</h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                    <div class="col-sm-12 panel">
                        <?php
                        if (!empty($result) && !is_array($result)) {
                            echo "<b><p class='text-center mt-50'>" . $result . "</p></b>";
                        } else if (!empty($result)) {
                            $header = !empty($result[0]) ? array_keys($result[0]) : [];
                            ?>
                            <div class="table-responsive kv-grid-container">
                                <table class="kv-grid-table kv-table-wrap table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
                                    <thead>
                                        <tr>                   
                                            <?php foreach ($header as $h) { ?>
                                                <th class="font_fff"><?= Yii::t('app', ucwords(str_replace('_', ' ', $h))) ?></th>
                                                <?php } ?>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $r) { ?>
                                            <tr>
                                                <?php
                                                foreach ($header as $h) {
                                                    $val = !empty($r[$h]) ? (Yii::$app->general->decryptData($r[$h]) !== FALSE ? Yii::$app->general->decryptData($r[$h]) : $r[$h]) : (isset($r[$h]) && $r[$h] == 0 && $r[$h] != '' ? 0 : '');
                                                    ?>
                                                    <td><?= $val ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
//                            echo "<b><p class='text-center mt-50'>asdasd</p></b>";
//                            $attr = [];
//                            foreach ($result[0] as $att => $value) {
//                                $attr_arr = [];
//                                $format = 'raw';
//                                if (in_array($att, ['Quantity', 'FAT', 'CLR', 'SNF'])) {
//                                    $format = ['decimal', 2];
//                                }
////                    $attr_arr['attribute'] = $att;
//                                $attr_arr = [];
//                                if (true || !empty($data['to_decrypt'])) {
//                                    $attr_arr['value'] = function($model) use ($att) {
//                                        return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
//                                    };
//                                }
//
//                                $str = ucwords(str_replace('_', ' ', $att));
//                                $attr_arr['attribute'] = $att;
//                                $attr_arr['label'] = Yii::t('app', $str);
//                                $attr_arr['format'] = $format;
//                                $attr_arr['filter'] = false;
//                                $attr[] = $attr_arr;
////                    $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
//                            }
//                            $grid_option = [
//                                'id' => 'history-view-list_' . date('Y-m-d h:i:s'),
//                                'attributes' => $attr,
//                                'active_column' => false,
//                            ];
//
////                            $c = 0;
////                            echo '<div id="grid_show_hide_list" class="dropdown-check-list" tabindex="100">';
////                            echo '<span class="anchor"><i class="fa fa-chevron-down"></i></span>';
////                            echo '<ul class="items">';
////                            foreach ($attr as $key => $value) {
////                                echo '<li><input class="toggle-vis" data-column="' . $c++ . '" type="checkbox" checked/>' . $value['title'] . '</li>';
////                            }
////                            echo '</ul>';
////                            echo '</div>';
//                            // echo '<a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>';
//                            // var_dump($dataProvider->getModels());
////                            echo \nullref\datatable\DataTable::widget([
////                                'id' => 'custom_report_temp',
////                                'autoWidth' => true,
//////                    'searching' => true,
////                                'data' => $dataProvider->getModels(),
////                                'scrollX' => true,
////                                'scrollY' => '100px',
////                                'scrollCollapse' => false,
////                                'paging' => false,
////                                'columns' => $attr,
////                                'info' => false,
////                                'withColumnFilter' => false,
////                                'order' => []
////                            ]);
////                echo \nullref\datatable\DataTable::widget([
////                    'id' => 'custom_report',
////                    'data' => $dataProvider->getModels(),
////                    // 'scrollY' => '200px',
////                    'scrollCollapse' => true,
////                    'paging' => false,
////                    'columns' => $attr,
////                    'info' => false,
////                    'withColumnFilter' => true
////                ]);
//                            $removeExportType = [];
//                            $exportEvents = [];
//                            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true, $removeExportType, $exportEvents);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>