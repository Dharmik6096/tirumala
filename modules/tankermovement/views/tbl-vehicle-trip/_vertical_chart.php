<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking Timeline</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .contents {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }
    </style>
</head>
<body>
    <div class="timeline col-sm-6 col-sm-offset-3">
        <div class="timeline-highlight"></div>
        <?php
        $fixedStatuses = [
            'generated',
            'quality_checked',
            'BMC_Dispatch',
            'Plant_Lot_Pending',
            'Cleaning_Pending',
            'QA_Pending',
            'Tanker_Qualified',
        ];

        // Extract existing statuses from $tripTrack
        $existingStatuses = array_column($tripTrack, 'trip_sub_status');

        // Find missing statuses
        $missingStatuses = array_diff($fixedStatuses, $existingStatuses);

        // Add missing statuses to $tripTrack with default values
        foreach ($missingStatuses as $status) {
            $tripTrack[] = (object)[
                'trip_sub_status' => $status,
                'sub_status_time' => 'N/A',
                'vehicle_trip_detail_code' => 'N/A',
                'remarks' => 'Added as missing status',
                'visibility_status' => null,
            ];
        }

        $trip_detail_codes = [];
        $collapsibleData = [];

        // First pass: collect collapsible headers and relevant data
        foreach ($tripTrack as $track) {
            if ($track->visibility_status == 2) {
                $trip_detail_codes[] = $track->vehicle_trip_detail_code;
                $collapsibleData[$track->vehicle_trip_detail_code]['header'] = $track;
            }
        }

        // Second pass: collect collapsible contents
        foreach ($tripTrack as $track) {
            if (in_array($track->vehicle_trip_detail_code, $trip_detail_codes)) {
                $collapsibleData[$track->vehicle_trip_detail_code]['contents'][] = $track;
            }
        }
        $total_record = !empty($tripTrack) ? count($tripTrack) - 1 : 0;
        // Render the timeline
        foreach ($tripTrack as $key => $track) {
            $class = ($total_record >= $key) ? 'selected' : '';
            if (in_array($track->vehicle_trip_detail_code, $trip_detail_codes) && $track->visibility_status == 2) {
                $vehicleCode = $track->vehicle_trip_detail_code;
                $header = $collapsibleData[$vehicleCode]['header']; ?>
                <div class="containers collapsible-container <?php echo $class; ?>">
                    <div class="collapsible content" style="position: relative; cursor: pointer;">
                        <h5 style="left: -170px;">
                            <i class="fa fa-angle-down collapsible-icon"></i>
                            <strong style="color:#00a3de"><?= htmlspecialchars($header->trip_sub_status) ?></strong>
                        </h5>
                        <p><?= htmlspecialchars($header->sub_status_time) ?></p>
                        <p><?= htmlspecialchars($header->vehicle_trip_detail_code) ?> - <?= htmlspecialchars($header->remarks) ?></p>
                    </div>
                    <div class="contents content">
                        <?php foreach ($collapsibleData[$vehicleCode]['contents'] as $content): ?>
                            <div style="border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px;">
                                <div class="box minimalist-border-box gray">
                                    <div class="col-sm-2" style="align-content: center;">
                                        <i class="fa fa-sign-in"></i>
                                    </div>
                                    <div class="col-sm-10">
                                        <p><b>Gate In</b></p>
                                        <p><?= htmlspecialchars($content->sub_status_time) ?></p>
                                        <p><?= htmlspecialchars($content->vehicle_trip_detail_code) ?> - <?= htmlspecialchars($content->remarks) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php } else { ?>
                <?php if (!in_array($track->vehicle_trip_detail_code, $trip_detail_codes)): ?>
                    <div class="containers <?php echo $class; ?>">
                        <div class="content">
                            <h5><strong><?= htmlspecialchars($track->trip_sub_status) ?></strong></h5>
                            <p><?= htmlspecialchars($track->sub_status_time) ?></p>
                            <p><?= htmlspecialchars($track->vehicle_trip_detail_code) ?> - <?= htmlspecialchars($track->remarks) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php } ?>
        <?php } ?>
    </div>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        for (var i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                var content = this.nextElementSibling;
                var icon = this.querySelector('.collapsible-icon');
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
</body>
</html>
