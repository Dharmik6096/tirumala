<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_ledger_types".
 *
 * @property integer $ledger_type_code
 * @property string $ledger_type_name
 * @property string $local_name
 * @property integer $balance_sheet
 * @property integer $profit_loss
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
class TblLedgerTypes extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_types';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'ledger_type_name', 'ledger_type_code', 'balance_sheet', 'profit_loss', 'is_active', 'local_name', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'updated_at', 'ref_code'], 'safe'],
                [['ledger_type_name', 'union_code'], 'required'],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['ledger_type_name'], 'unique'],
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
            'ledger_type_code' => Yii::t('app', 'Ledger Type Code'),
            'ledger_type_name' => Yii::t('app', 'Ledger Type Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'balance_sheet' => Yii::t('app', 'Is Balance Sheet?'),
            'profit_loss' => Yii::t('app', 'Is Profit Loss?'),
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

}
