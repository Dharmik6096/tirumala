<?php

namespace app\components;

use nullref\datatable\assets\DataTableBaseAsset;

class CustomDataTableBaseAsset extends DataTableBaseAsset {

    public function init() {
        parent::init();
        $this->js[] = '\..\..\..\themes\pcdf\assets\js\jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
    }

}
