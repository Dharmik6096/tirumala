<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\dcsoperation\models\TblSchemeRate;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_scheme_rate_applicability".
 *
 * @property integer $scheme_rate_app_code
 * @property string $scheme_rate_code
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $rtpl
 * @property string $union_code
 * @property string $rate_class
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSchemeRateApplicability extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'created_at', 'updated_at', 'is_active', 'originating_type', 'approved_at', 'approved_by', 'description'], 'safe'],
            [['from_shift', 'to_shift'], 'integer'],
            [['rtpl'], 'number'],
            [['rtpl'], 'number', 'on' => ['androidsync']],
            [['scheme_rate_code', 'applicable_for', 'applicable_code'], 'safe'],
            [['union_code', 'rate_class'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'is_member_rate'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'tab_download_datetime'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
//                [['applicable_code'], 'unique', 'targetAttribute' => ['applicable_code', 'from_date', 'to_date', 'applicable_for'], 'message' => Yii::t('app/validation', 'Record Is Alredy Exist.'), 'on' => ['approval']],
            [['applicable_code'], 'rangeValidate', 'on' => ['approval']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_rate_app_code' => Yii::t('app', 'Scheme Rate App Code'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'union_code' => Yii::t('app', 'Union Code'),
            'rate_class' => Yii::t('app', 'Rate Class'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'mcc_name' => Yii::t('app', 'Applicable Name'),
            'is_member_rate' => Yii::t('app', 'Is Member Rate'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function getSchemeRateCode() {
        return $this->hasOne(TblSchemeRate::className(), ['scheme_rate_code' => 'scheme_rate_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasMany(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getMccPlantCode() {
        return $this->hasMany(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getDcsName() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $generateSentbox = false;
        // $dcs_code = '';
        // $appendDcs = false; 
        if ($this->applicable_for == 'DCS') {
            $generateSentbox = true;
            $bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
        } else {
            $generateSentbox = true;
            $bmc_code = Yii::$app->general->getforeignkey($this->customerMasterCode, 'bmc_code');
            // $mcc_code = Yii::$app->general->getforeignkey($this->customerMasterCode, 'mcc_plant_code');
        }
        if ($generateSentbox) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $bmc_code, '', '', false);
            // $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $bmc_code, '', $dcs_code, $appendDcs);
            if ($this->applicable_for == 'DCS' && $this->is_member_rate == 1) {
                $sentboxArray[] = [
                    'code' => $this->applicable_code,
                    'type' => 'VLC'
                ];
            }
            foreach ($sentboxArray as $sent) {
                $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
                if ($this->is_active == 0) {
                    $flag = 'DELETE';
                }
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->applicable_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getSchemeRateApplicableData($data) {
        return $this->find()
                        ->where(['applicable_code' => $data['dcs_code'], 'rate_class' => $data['rate_class']])
                        ->andWhere(['<=', 'from_date', $data['dt_date']])
                        ->andWhere(['>=', 'to_date', $data['dt_date']])
                        ->one();
    }

    public function rangeValidate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query = $this->find()->where('union_code=\'' . $this->union_code . '\' and applicable_code=\'' . $this->applicable_code . '\' and rate_class=\'' . $this->rate_class . '\'and is_active=' . 1 . ' ')
                    ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date) OR (\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))');
//            echo $query->createCommand()->sql;
//            exit;
            $record = $query->one();
            if ($record) {
                $this->addError($attribute, Yii::t('app/validation', 'Date Range already inserted'));
                return false;
            }
        }
    }

    public function getDcsSchemeRateApplicableData($date) {
        return $this->find()
                        ->where(['applicable_code' => $this->applicable_code])
                        ->andWhere(['<=', 'cast(from_date as date)', $date])
                        ->andWhere(['>=', 'cast(to_date as date)', $date])
                        ->one();
    }

    public function getCustomerMasterCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

}
