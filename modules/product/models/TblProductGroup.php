<?php

namespace app\modules\product\models;

use app\modules\dcsaccounting\models\TblLedgerMappingProductGroup;
use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;
use app\modules\globalmaster\models\TblUnits;
use yii\helpers\ArrayHelper;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_product_group".
 *
 * @property integer $product_group_code
 * @property string $product_group_name
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 *
 * @property TblProduct[] $tblProducts
 */
class TblProductGroup extends \app\models\ChildModel {

    public $ledger_sale_code, $ledger_purchase_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_group';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_group_name', 'unit_code', 'union_code'], 'required'],
            [['product_group_name', 'created_by', 'updated_by', 'local_name'], 'string'],
//                ['product_group_name', 'unique'],
            [['product_group_name'], 'unique', 'targetAttribute' => ['product_group_name', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['product_group_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['created_at', 'updated_at', 'product_group_code', 'union_code', 'unit_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code'], 'safe'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_group_code' => Yii::t('app', 'Product Group Code'),
            'product_group_name' => Yii::t('app', 'Product Group Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'ref_code' => Yii::t('app', 'Reference Code'),
            'unit_code' => Yii::t('app', 'Unit'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProducts() {
        return $this->hasMany(TblProduct::className(), ['product_group_code' => 'product_group_code']);
    }

    public function getTblLedgerMappingProductGroup() {
        return $this->hasOne(TblLedgerMappingProductGroup::className(), ['product_group_code' => 'product_group_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductGroupQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductGroupQuery(get_called_class());
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getUnitCode() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'unit_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
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
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
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

    public function getProdutGroupList($unionCode) {
        $query = $this->find()
                ->select(['product_group_code', 'product_group_name'])
                ->where(['union_code' => $unionCode, 'is_active' => 1])
                ->all();
        $value = ArrayHelper::map($query, 'product_group_code', 'product_group_name');
        return $value;
    }

}
