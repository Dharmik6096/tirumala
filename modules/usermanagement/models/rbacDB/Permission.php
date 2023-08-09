<?php

namespace app\modules\usermanagement\models\rbacDB;

use Yii;

class Permission extends \webvimark\modules\UserManagement\models\rbacDB\Permission {

    use AbstractItemTrait;

    public function checkNotVendor() {
        return ($this->name == 'Vendor Permission' || ($this->entry_type == 1 && Yii::$app->session->get('organizations_type') == 'UNION')) ? false : true;
    }

}
