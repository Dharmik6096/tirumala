<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\general\models\TblBanner;

/**
 * This is the model class for table "tbl_banner_applicability".
 *
 * @property integer $banner_applicability_code
 * @property integer $banner_code
 * @property string $login_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBannerApplicability extends \app\models\ChildModel {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banner_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['login_type', 'banner_code', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'applicable_for', 'applicable_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date'], 'safe'],
                [['department', 'applicable_code'], 'required'],
                [['applicable_code'], 'validateBanner', 'skipOnEmpty' => false,],
                [['department'], 'validateDepartmentCount', 'when' => function($model) {
                    return (!empty($model->department) && !is_array($model->department));
                }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'banner_applicability_code' => Yii::t('app', 'Banner Applicability Code'),
            'banner_code' => Yii::t('app', 'Banner Code'),
            'login_type' => Yii::t('app', 'Login Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'department' => Yii::t('app', 'Department'),
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function validateBanner($attribute, $params) {
        return $this->validateData();
    }

    public function getBannerCode() {
        return $this->hasOne(TblBanner::className(), ['banner_code' => 'banner_code']);
    }

    public function validateData() {
        $data = $this->find()
                ->where(['applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'banner_code' => $this->banner_code, 'department' => $this->department])
                ->all();

        $message = [];
        if (!empty($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $mesageVal = $data[$i] ['department'];
                $message[$mesageVal] = $mesageVal;
            }
            if (count($message) > 0) {
                $messagestring = 'Following are the current applicabilities.<br/>' . implode('<br/>', $message);
                $this->addError('applicable_code', $messagestring);

                return false;
            }
            return true;
        }
    }

    // public function validateLoginTypeCount($attribute, $params) {
    //     $this->from_date = isset($this->bannerCode) ? $this->bannerCode->from_date : NULL;
    //     $this->to_date = isset($this->bannerCode) ? $this->bannerCode->to_date : NULL;

    //     $loginTypeCount = $this->checkLogintype($this->login_type, $this->from_date, $this->to_date, $this->applicable_code);

    //     if ($loginTypeCount >= 5) {
    //         $this->addError($attribute, 'More than 5 ' . $this->login_type . ' login type not allowed.');
    //         return false;
    //     }
    // }

    // public function checkLogintype($login_type, $from_date, $to_date, $applicable_code) {

    //     return $this->find()
    //                     ->select(['tbl_banner_applicability.*', 'tbl_banner.*'])
    //                     ->innerJoin('tbl_banner', 'tbl_banner.banner_code = tbl_banner_applicability.banner_code')
    //                     ->where([
    //                         'tbl_banner_applicability.login_type' => $login_type,
    //                         'tbl_banner_applicability.applicable_code' => $applicable_code,
    //                     ])
    //                     ->andWhere('(\'' . $from_date . '\'  between from_date and to_date) OR (\'' . $to_date . '\' between from_date  and to_date) OR (from_date between \'' . $from_date . '\' and  \'' . $to_date . '\') OR (to_date between \'' . $from_date . '\' and \'' . $to_date . '\')')
    //                     ->count();
    // }

    public function validateDepartmentCount($attribute, $params) {
        $this->from_date = isset($this->bannerCode) ? $this->bannerCode->from_date : NULL;
        $this->to_date = isset($this->bannerCode) ? $this->bannerCode->to_date : NULL;

        $departmentCount = $this->checkDepartment($this->department, $this->from_date, $this->to_date, $this->applicable_code);

        if ($departmentCount >= 5) {
            $this->addError($attribute, 'More than 5 ' . $this->department . ' department not allowed.');
            return false;
        }
    }

    public function checkDepartment($department, $from_date, $to_date, $applicable_code) {

        return $this->find()
                        ->select(['tbl_banner_applicability.*', 'tbl_banner.*'])
                        ->innerJoin('tbl_banner', 'tbl_banner.banner_code = tbl_banner_applicability.banner_code')
                        ->where([
                            'tbl_banner_applicability.department' => $department,
                            'tbl_banner_applicability.applicable_code' => $applicable_code,
                        ])
                        ->andWhere('(\'' . $from_date . '\'  between from_date and to_date) OR (\'' . $to_date . '\' between from_date  and to_date) OR (from_date between \'' . $from_date . '\' and  \'' . $to_date . '\') OR (to_date between \'' . $from_date . '\' and \'' . $to_date . '\')')
                        ->count();
    }

    public function setOrgDetail() {
        if ($this->applicable_for == 'BMC') {
            $this->bmc_code = $this->applicable_code;
            $bmc_detail = $this->bmcCode;
            $this->plant_code = $bmc_detail->plant_code;
            $this->mcc_plant_code = $bmc_detail->mcc_plant_code;
        }
    }

    public function getDepartmentId() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

}
