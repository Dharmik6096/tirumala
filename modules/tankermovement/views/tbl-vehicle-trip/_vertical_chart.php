<?php
$this->title = Yii::t('app', 'Vehicle Trip Map');
?>
<div class="trip-map-containers col-sm-6 col-sm-offset-3">
    <h2 class="trip-title">Vehicle Trip Status</h2>
    <h4>Trip Code: <?= !empty($tripTrack[0]->trip_code) ? $tripTrack[0]->trip_code : '' ?></h4>
    <h4>Parsing No: <?= !empty($parsingNo->parsing_no) ? $parsingNo->parsing_no : '' ?></h4>
</div>
<div class="timeline col-sm-6 col-sm-offset-3">
    <?php
    $fixedStatuses = ['generated', 'gate_out', 'gate_in', 'plant_lot_pending', 'cleaning_pending', 'qa_pending', 'tanker_qualified'];
    $total_record = !empty($tripTrack) ? count($tripTrack) - 1 : 0;


    $lable_array = [
        'generated' => 'Generated',
        'gate_out' => 'Plant Gate Out',
        'plant_lot_pending' => 'Plant Gate In',
        'gate_in' => 'Milk Dispatch',
        'cleaning_pending' => 'Plant Lot Done',
        'qa_pending' => 'Cleaning Done',
        'tanker_qualified' => 'QA Done',
    ];

    $existingStatuses = array_column($tripTrack, 'trip_sub_status');

    foreach (array_diff($fixedStatuses, $existingStatuses) as $status) {
        $tripTrack[] = (object) [
                    'trip_sub_status' => $status,
                    'sub_status_time' => '',
                    'module_code' => '',
                    'remarks' => '',
                    'visibility_status' => null,
        ];
    }

    $collapsibleData = [];
    $index = 0;
    foreach ($tripTrack as $track) {
        if ($track->visibility_status == 2 || $track->visibility_status == 1) {
            $index++;
        }
        if ($track->visibility_status == 2) {
            $collapsibleData[$track->module_code]['header'] = $track;
        }
        if (isset($collapsibleData[$track->module_code])) {
            $collapsibleData[$track->module_code]['contents'][] = $track;
        }
    }
    $track_key = 1;
    foreach ($tripTrack as $t_key => $track) {
        $class = ($total_record >= $t_key) ? 'sub_status_selected' : 'sub_status_not_selected';
        if (isset($collapsibleData[$track->module_code]) && $track->visibility_status == 2) {
            $vehicleCode = $track->module_code;
            $header = $collapsibleData[$vehicleCode]['header'];
            ?>
            <div class="containers collapsible-container <?php echo $class; ?>">
                <?php
                if ($index == $track_key):
                    ?>
                    <div class="image-container">
                        <img src="themes/pcdf/assets/images/milk_truck.jpg" alt="Timeline Image">
                    </div>
                    <?php
                endif;
                $track_key++;
                ?>
                <div class="collapsible sub_status_content">
                    <h5 style="left: -170px;">
                        <i class="fa fa-angle-down collapsible-icon"></i>
                        <strong style="color:#00a3de"><?= !empty($lable_array[$header->trip_sub_status]) ? $lable_array[$header->trip_sub_status] : $track->trip_sub_status ?></strong>
                    </h5>
                    <p><?= Yii::$app->controls->view_datetime($header->sub_status_time) ?></p>
                    <p><?= $header->remarks ?></p>
                </div>
                <div class="collapsible_contents sub_status_content">
                    <?php foreach ($collapsibleData[$vehicleCode]['contents'] as $key => $content): ?>
                        <?php
                        if ($content->trip_sub_status != 'milk_receipt'):
                            $bgColorClass = ($key % 2 == 0) ? 'bg-gray' : 'white';
                            ?>
                            <div class="collapsible_box">
                                <div class="box minimalist-border-box <?= $bgColorClass ?>">
                                    <div class="col-sm-2" style="align-content: center;">
                                        <?php
                                        if (str_contains(strtolower($content->trip_sub_status), 'dispatch') || str_contains(strtolower($content->trip_sub_status), 'receipt')):
                                            ?>
                                            <i class="fa fa-truck"></i>
                                        <?php else: ?>
                                            <?php if ($content->trip_sub_status == 'gate_in'): ?>
                                                <i class="fa fa-sign-in"></i>
                                            <?php elseif ($content->trip_sub_status == 'gate_out'): ?>
                                                <i class="fa fa-sign-out"></i>
                                            <?php else: ?>
                                                <i class="fa fa-info-circle"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-10">
                                        <p><b><?= ucwords(str_replace('_', ' ', $content->trip_sub_status)) ?></b></p>
                                        <p><?= Yii::$app->controls->view_datetime($content->sub_status_time) ?></p>
                                        <p><?= $content->remarks ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
            <?php } elseif (!isset($collapsibleData[$track->module_code])) { ?>
            <div class="containers <?php echo $class; ?>">
                <?php
                if ($index == $track_key):
                    ?>
                    <div class="image-container">
                        <img src="themes/pcdf/assets/images/milk_truck.jpg" alt="Timeline Image">
                    </div>
                    <?php
                endif;
                $track_key++;
                ?>
                <div class="sub_status_content">
                    <h5><strong><?= !empty($lable_array[$track->trip_sub_status]) ? $lable_array[$track->trip_sub_status] : $track->trip_sub_status ?></strong></h5>
                    <p><?= Yii::$app->controls->view_datetime($track->sub_status_time) ?></p>
                    <p><?= $track->remarks ?></p>
                </div>
            </div>
        <?php } ?>
<?php } ?>
</div>
<script>
    var coll = document.getElementsByClassName("collapsible");
    for (var i = 0; i < coll.length; i++) {
        var icon = coll[i].querySelector('.collapsible-icon');
        icon.addEventListener("click", function (event) {
            event.stopPropagation();
            var parentCollapsible = this.closest('.collapsible');
            var content = parentCollapsible.nextElementSibling;
            var icon = this;

            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.classList.replace('fa-angle-up', 'fa-angle-down');
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.classList.replace('fa-angle-down', 'fa-angle-up');
            }
        });
    }
</script>
