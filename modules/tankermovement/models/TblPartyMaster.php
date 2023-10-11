<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_party_master".
 *
 * @property string $party_master_code
 * @property string $union_code
 * @property string $party_name
 * @property string $party_contact_no
 * @property string $party_address
 * @property string $owner_name
 * @property string $owner_contact_no
 * @property string $owner_email
 * @property string $owner_address
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $beneficiary_name
 * @property string $pan_no
 * @property string $adhar_no
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblPartyMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_party_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['party_master_code'], 'required'],
            [['is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['party_master_code'], 'string', 'max' => 20],
            [['union_code', 'district_code'], 'string', 'max' => 3],
            [['party_name', 'owner_name', 'owner_email', 'beneficiary_name'], 'string', 'max' => 100],
            [['party_contact_no', 'owner_contact_no', 'bank_account_no', 'ifsc', 'pan_no', 'adhar_no', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['party_address', 'owner_address'], 'string', 'max' => 250],
            [['state_code'], 'string', 'max' => 2],
            [['sub_district_code'], 'string', 'max' => 5],
            [['village_code'], 'string', 'max' => 6],
            [['hamlet_code'], 'string', 'max' => 8],
            [['bank_code'], 'string', 'max' => 4],
            [['branch_code'], 'string', 'max' => 9],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['owner_email'], 'email'],
            [['is_active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'party_master_code' => Yii::t('app', 'Party Master Code'),
            'union_code' => Yii::t('app', 'Union'),
            'party_name' => Yii::t('app', 'Party Name'),
            'party_contact_no' => Yii::t('app', 'Party Contact No'),
            'party_address' => Yii::t('app', 'Party Address'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'owner_contact_no' => Yii::t('app', 'Owner Contact No'),
            'owner_email' => Yii::t('app', 'Owner Email'),
            'owner_address' => Yii::t('app', 'Owner Address'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'adhar_no' => Yii::t('app', 'Adhar No'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPartyList($unionCode, $RLS = 'TRUE', $notIn = [], $concatCode = false) {
        $query = $this->find()->select(['party_master_code', 'party_name'])
                ->where(['is_active' => 1]);
        $value = $query->orderBy('party_name asc')->all();
        $value = ArrayHelper::map($value, 'party_master_code', function($value) use ($concatCode) {
                    return $value->party_name . ($concatCode ? ' - ' . $value->party_master_code : '');
                });
        return $value;
    }

}
