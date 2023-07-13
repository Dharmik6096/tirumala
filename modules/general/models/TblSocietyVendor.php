<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_society_vendor".
 *
 * @property integer $society_vendor_code
 * @property string $dcs_code
 * @property string $vendor_code
 * @property integer $is_active
 *
 * @property TblDcs $societyCode
 * @property User $vendorCode
 */
class TblSocietyVendor extends \app\models\ChildModel {

    public $union_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_society_vendor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active'], 'integer'],
                [['vendor_code'], 'string'],
                [['dcs_code'], 'required'],
                [['vendor_code'], 'default', 'value' => 'EIPL', 'on' => ['saveCreamyData']],
                [['is_active'], 'default', 'value' => 1, 'on' => ['saveCreamyData']],
                //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
                //[['vendor_code'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['vendor_code' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'society_vendor_code' => Yii::t('app', 'Society Vendor Code'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'vendor_code' => Yii::t('app', 'Vender Code'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenderCode() {
        //return $this->hasOne(User::className(), ['id' => 'vendor_code']);
    }

    /**
     * @inheritdoc
     * @return TblSocietyVendorQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSocietyVendorQuery(get_called_class());
    }

    public function getDcsVendor($dcs_code) {
        $data = $this->find()->select('*')->where(['dcs_code' => $dcs_code])->one();
        return $data['vendor_code'];
    }

    public function getRecord() {
        return $data = $this->find()->where(['dcs_code' => $this->dcs_code])->one();
    }

}
