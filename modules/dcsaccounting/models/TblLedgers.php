<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_ledgers".
 *
 * @property string $ledger_code
 * @property string $ledger_name
 * @property string $local_name
 * @property integer $has_sub_ledger
 * @property integer $ledger_group_code
 * @property integer $is_active
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblLedgers extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledgers';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ledger_name', 'ledger_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'has_sub_ledger', 'ledger_group_code', 'is_active', 'originating_type', 'created_at', 'updated_at', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['ledger_name', 'ledger_group_code', 'union_code'], 'required'],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['ledger_name'], 'unique', 'targetAttribute' => ['ledger_name', 'ledger_group_code'], 'message' => 'This name already exists in this group.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ledger_code' => Yii::t('app', 'Ledger Code'),
            'ledger_name' => Yii::t('app', 'Ledger Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'has_sub_ledger' => Yii::t('app', 'Has Sub Ledger ?'),
            'ledger_group_code' => Yii::t('app', 'Ledger Group'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getLedgerGroupCode() {
        return $this->hasOne(TblLedgerGroups::className(), ['ledger_group_code' => 'ledger_group_code']);
    }

    public function getVoucherTypesCode() {
        return $this->hasOne(TblVoucherTypes::className(), ['ledger_code' => 'ledger_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new \yii\base\UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new \yii\base\UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getLedgerList($union_code, $type) {
        $ledgerData = $this->find()->alias('l')
                ->innerJoin('tbl_ledger_groups lg', 'l.ledger_group_code = lg.ledger_group_code')
                ->innerJoin('tbl_ledger_types lt', 'lg.ledger_type_code = lt.ledger_type_code')
                ->where(['l.union_code' => $union_code])
                ->andWhere(['l.is_active' => 1])
                ->andWhere(['LOWER(lt.ledger_type_name)' => $type])
                ->all();
        if (!empty($ledgerData)) {
            return ArrayHelper::map($ledgerData, 'ledger_code', function($model) {
                        return $model->ledger_name;
                    });
        }
        return [];
    }

}
