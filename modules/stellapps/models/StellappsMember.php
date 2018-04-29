<?php

namespace app\modules\stellapps\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;

class StellappsMember extends TblMember {

    public function rules() {
        return [
            [['hamlet_code', 'federation_code', 'village_code', 'sub_district_code', 'district_code', 'state_code', 'union_code'], 'safe'],
            [['ex_member_code'], 'string', 'min' => 4, 'max' => 4],
            [['ex_member_code'], 'number', 'min' => 1],
            [['is_download'], 'default', 'value' => '0'],
            [['member_type_code'], 'default', 'value' => '1'],
            [['member_name', 'ex_member_code', 'dcs_code', 'is_active', 'mobile_no'], 'required'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['dcs_code'], 'AddAutoData', 'skipOnError' => true,]
        ];
    }

    public function AddAutoData($attribute, $params) {
        if ($this->isNewRecord) {
            $this->member_code = $this->dcs_code . str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
            $this->hamlet_code = $this->dcsCode->hamlet_code;
            $this->village_code = $this->dcsCode->village_code;
            $this->sub_district_code = $this->dcsCode->sub_district_code;
            $this->district_code = $this->dcsCode->district_code;
            $this->state_code = $this->dcsCode->state_code;
            $this->union_code = $this->dcsCode->union_code;
            $this->federation_code = $this->unionCode->federation_code;
        }
    }

}
