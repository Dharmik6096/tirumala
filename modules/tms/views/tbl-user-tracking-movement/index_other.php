<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Individual Location of a User'));
$latLongArray = [];
$infoArray = [];
$newLatLongArray = [];
if (!empty($searchModel->user_code)) {
    $userDetail = $userDetailData['userDetail'];
    $newLatLongArray = $userDetailData['organizationLatlongData'];
}
$totalVisitedDcs = 0;
if (!empty($onlineData)) {
    foreach ($onlineData as $data) {
        $datetime = $data['tracking_datetime'];
        $date = date('Y-m-d', strtotime($datetime));
        $time = date('H:i', strtotime($datetime));
        if (!empty($data['lat']) && !empty($data['long'])) {
            $totalVisitedDcs++;
            $info = '<div class="map_info_content">';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Date') . ':</span> ' . $date . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Time') . ':</span> ' . $time . '</p>';
            $info .= '</div>';

            $latLongArray[] = [
                'info' => $info,
                'lat' => $data['lat'],
                'long' => $data['long'],
            ];
        }
    }
}

$startTime = null;
$endTime = null;
if (!empty($onlineData)) {
    $datetimes = array_column($onlineData, 'tracking_datetime');
    if (!empty($datetimes)) {
        $startTime = min($datetimes);
        $endTime = max($datetimes);
    }
}

if (empty($latLongArray)) {
    $latLongArray[] = [
        'info' => 'Default Location',
        'lat' => 23.051489132364928,
        'long' => 72.49427197180005,
    ];
}

$latLongArray = json_encode($latLongArray);
$mapIcon = $this->theme->getUrl('/assets/images/marker-icon.png');
if(!empty($newLatLongArray)){
    foreach ($newLatLongArray as &$location) {
        $info = $location['info'];
        if (strpos($info, 'MCC') !== false) {
            $location['icon'] = $this->theme->getUrl('/assets/images/yellow_dot.png');
        } else {
            $location['icon'] = $this->theme->getUrl('/assets/images/dot.png');
        }
    }
}
$newLatLongArray = json_encode($newLatLongArray);
$googleMapKey = Yii::$app->params['google_map_api_key'];
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= Html::encode($googleMapKey) ?>&libraries=&v=weekly" defer></script>
<div class="panel panel-default panel-grid tbl-complain-view hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'allUser' => FALSE]); ?>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="panel-heading">
                <?= Yii::t('app', 'Location of Selected User'); ?>
            </div>
            <div class="<?= !empty($searchModel->user_code) ? 'col-sm-9' : 'col-sm-12'; ?> mx_h_400">
                <div id="map"></div>
            </div>
            <?php if (!empty($searchModel->user_code)) { ?>
                <div class="col-sm-3 set_overflow_for_map">
                    <div class="individual-location">
                        <div class="individual-location-inner">
                            <p><strong>Name :</strong> <?= $userDetail['name'] ?></p>
                            <p><strong>Employee Id :</strong> <?= $userDetail['employee_id'] ?></p>
                            <p><strong>Mobile No :</strong> <?= $userDetail['mobile_no'] ?></p>
                            <p><strong>Department :</strong> <?= $userDetail['department'] ?></p>
                            <p><strong>Designation :</strong> <?= $userDetail['designation'] ?></p>
                            <?php $disable = ($userDetail['id'] != Yii::$app->session->get('UserCode')) ? '' : 'link-disable'; ?>
                            <p><strong>Assign Detail Count :</strong> <?= Html::a($userDetail['assign_detail_count'], ['/user-management/user/organization-map', 'id' => $userDetail['id'], 'hideControlsBtn' => TRUE], ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Organization', 'class' => $disable, 'target' => '_blank']) ?></p>
                        </div>
                    </div>
                    <div class="individual-location">
                        <div class="individual-location-inner">
                            <p><strong>Activity Date : </strong><?= $userDetail['tracking_datetime'] ?></p>
                            <p><strong>Day Activity Start Time : </strong> <?= $startTime ? date('h:i A', strtotime($startTime)) : 'Not available' ?></p>
                            <p><strong>Day Activity End Time : </strong> <?= $endTime ? date('h:i A', strtotime($endTime)) : 'Not available' ?></p>
                            <p id="distance-traveled"><strong>Total Distance : </strong> Not available</p>
                            <p id="total-unique-visit"><strong>Total Unique <?= Yii::t('app', 'DCS') ?> Visit : </strong> Not available</p>
                            <p><strong>Total Visited <?= Yii::t('app', 'DCS') ?> : </strong> <?= $totalVisitedDcs ?></p>
                        </div>
                    </div>
                    <div class="individual-location">
                        <div class="individual-location-inner">
                            <p><strong>Total Performance Of Date : </strong><?= $userDetail['tracking_datetime'] ?></p>
                            <p><strong>Total Tasks : </strong><?= $userDetail['total_tasks'] ?></p>
                            <p><strong>Total Indents : </strong><?= $userDetail['total_indents'] ?></p>
                            <p><strong>Total Enrollment <?= Yii::t('app', 'Member') ?>'s : </strong><?= $userDetail['total_enrollment_members'] ?></p>
                            <p><strong>Total <?= Yii::t('app', 'DCS') ?> Creation : </strong><?= $userDetail['total_vlcc_creation'] ?></p>
                            <p><strong>Total BULK <?= Yii::t('app', 'Vendor') ?> Creation : </strong><?= $userDetail['total_bulk_vendor_creation'] ?></p>
                            <p><strong>Total VCG/MRG Member Selection : </strong><?= $userDetail['total_vcg_mrg_member_selection'] ?></p>
                            <p><strong>Total VCG Meetings : </strong><?= $userDetail['total_vcg_meetings'] ?></p>
                            <p><strong>Total MRG Meetings : </strong><?= $userDetail['total_mrg_meetings'] ?></p>
                            <p><strong>Total Household Visits : </strong><?= $userDetail['total_household_visits'] ?></p>
                            <p><strong>Total <?= Yii::t('app', 'DCS') ?> Survey : </strong><?= $userDetail['total_mpp_survey'] ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    var locations = $latLongArray;
    var newLocations = $newLatLongArray;
    var thresholdDistance = 50;

    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371e3;
        var φ1 = lat1 * Math.PI/180;
        var φ2 = lat2 * Math.PI/180;
        var Δφ = (lat2-lat1) * Math.PI/180;
        var Δλ = (lon2-lon1) * Math.PI/180;
        var a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d;
    }

    function isWithinThreshold(newLocation, existingLocations, threshold) {
        for (var i = 0; i < existingLocations.length; i++) {
            var distance = calculateDistance(newLocation.lat, newLocation.long, existingLocations[i].lat, existingLocations[i].long);
            if (distance <= threshold) {
                return true;
            }
        }
        return false;
    }

    var countWithin = 0;
    if (Array.isArray(newLocations)) {
        for (var i = 0; i < newLocations.length; i++) {
            if (isWithinThreshold(newLocations[i], locations, thresholdDistance)) {
                countWithin++;
                document.getElementById('total-unique-visit').innerHTML = '<strong>Total Unique MPP Visit : </strong>' + countWithin;
            }
        }
    }

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: new google.maps.LatLng(locations[0].lat, locations[0].long),
    });
    var infoWindow = new google.maps.InfoWindow();
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
    });

    if (locations.length > 1) {
        var waypoints = locations.slice(1, -1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.long),
                stopover: true,
            };
        });

        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].long),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1].long), // End point
            waypoints: waypoints,
            travelMode: google.maps.TravelMode.DRIVING,
        };

        directionsService.route(request, function(result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
                var totalDistance = 0;
                var legs = result.routes[0].legs;
                for (var i = 0; i < legs.length; i++) {
                    totalDistance += legs[i].distance.value;
                }
                var totalDistanceKm = (totalDistance / 1000).toFixed(2);;
                document.getElementById('distance-traveled').innerHTML = '<strong>Total Distance : </strong>' + totalDistanceKm + ' km';
            } else {
                console.error('Directions request failed due to ' + status);
            }
        });
        newLocations.forEach(function(location) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(location.lat, location.long),
                map: map,
                icon: {
                    url: location.icon,
                    anchor: new google.maps.Point(8, 8)
                },
            });
            marker.addListener('click', function() {
                infoWindow.setContent(location.info);
                infoWindow.open(map, marker);
            });

            // Add circle around new location
            var circle = new google.maps.Circle({
                map: map,
                radius: thresholdDistance, // 50 meters in radius
                center: new google.maps.LatLng(location.lat, location.long),
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
            });
        });
    } else {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[0].lat, locations[0].long),
            map: map,
            icon: "$mapIcon",
        });

        marker.addListener('click', function() {
            infoWindow.setContent(locations[0].info);
            infoWindow.open(map, marker);
        });
    }
JS;

$this->registerJs($script, View::POS_READY, 'user-tracking-movement');
?>
