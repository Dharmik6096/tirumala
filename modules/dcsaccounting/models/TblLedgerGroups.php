<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsaccounting\models\TblLedgerTypes;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_ledger_groups".
 *
 * @property integer $ledger_group_code
 * @property string $ledger_group_name
 * @property string $local_name
 * @property integer $ledger_type_code
 * @property integer $is_active
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
class TblLedgerGroups extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_group_name'], 'required', 'on' => ['androidsync']],
                [['ledger_group_name', 'ledger_group_code', 'ledger_type_code', 'is_active', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_type', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type', 'ref_code', 'is_cash'], 'safe'],
                [['ledger_type_code', 'ledger_group_name', 'union_code'], 'required'],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['ledger_group_name'], 'unique'],
                [['ref_code'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'union_code' => Yii::t('app', 'Union'),
            'ledger_group_code' => Yii::t('app', 'Ledger Group Code'),
            'ledger_group_name' => Yii::t('app', 'Ledger Group Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'ledger_type_code' => Yii::t('app', 'Ledger Type'),
            'is_active' => Yii::t('app', 'Is Active'),
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
            'ref_code' => Yii::t('app', 'Code'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getLedgerTypeCode() {
        return $this->hasOne(TblLedgerTypes::className(), ['ledger_type_code' => 'ledger_type_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code);
                $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code);
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, 'DELETE', $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

}
