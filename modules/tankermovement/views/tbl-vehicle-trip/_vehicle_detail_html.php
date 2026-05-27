<table class="route-table">
    <tbody>
        <tr>
            <th>Capacity</th>
            <td><?= $vehicleData['capacity'] ?? 0 ?> Ltr</td>
        </tr>
        <tr>
            <th>No Of Compartment</th>
            <td><?= $vehicleData['compartment_no'] ?? 0 ?></td>
        </tr>
        <tr>
            <th>Transporter</th>
            <td><?= $vehicleData['transporter_name'] ?: 'N/A' ?></td>
        </tr>
    </tbody>
</table>
<script>
    $('#tblvehicletrip-driver_name').val('<?= $vehicleData['driver_name'] ?>');
    $('#tblvehicletrip-mobile_no').val('<?= $vehicleData['driver_contact_no'] ?>');
</script>
