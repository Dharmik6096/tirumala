<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_group_mapping_history".
 *
 * @property integer $id
 * @property integer $bmc_mapping_code
 * @property string $bmc_code
 * @property string $p_bmc_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_by
 * @property string $history_created_at
 */
class TblOrganizationLatLongApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_organization_latlong_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['organization_latlong_applicability_code','organization_latlong_code','user_code','applicable_for','applicable_code','union_code','originating_org_code','originating_org_type','originating_type','updated_at','updated_by','created_at','created_by','operation_type','history_created_at','history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */

}
