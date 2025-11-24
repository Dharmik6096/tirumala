<?php

namespace app\components;

use nullref\datatable\assets\DataTableAsset;
use nullref\datatable\assets\DataTableBootstrapAsset;
use nullref\datatable\assets\DataTableFaAsset;
use nullref\datatable\assets\DataTableJuiAsset;

class CustomDataTableAsset extends DataTableAsset {

    public function init() {
        parent::init();

        switch ($this->styling) {
            case self::STYLING_JUI:
                $this->depends[] = DataTableJuiAsset::class;
                break;
            case self::STYLING_BOOTSTRAP:
                $this->depends[] = DataTableBootstrapAsset::class;
                break;
            case self::STYLING_DEFAULT:
                $this->depends[] = CustomDataTableBaseAsset::class;
                break;
            default;
        }

        if ($this->fontAwesome) {
            $this->depends[] = DataTableFaAsset::class;
        }
    }

}
