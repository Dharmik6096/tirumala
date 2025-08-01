<table class="table table-bordered table-striped fc">
    <thead><tr>
            <?php
            if (!empty($results)) {
                $firstRow = $results[0];
                foreach ($firstRow as $key => $value) {
                    ?>
            <th><?= ucwords(str_replace('_', ' ', $key)) ?></th>
                    <?php
                }
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $data) { ?>
            <tr>
                <?php foreach ($data as $value) { ?>
                    <td><?= $value ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>



