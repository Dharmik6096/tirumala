<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblDispatchCenterType;
use app\modules\organisation\models\TblUnions;
use app\modules\product\models\TblProductGroup;
use app\modules\usermanagement\models\TblUserDispatchCenterMapping;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_dispatch_center".
 *
 * @property string $dispatch_center_code
 * @property string $dispatch_center_name
 * @property string $dispatch_center_type_code
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
class TblDispatchCenter extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dispatch_center';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dispatch_center_name', 'dispatch_center_type_code', 'union_code'], 'required'],
            [['created_at', 'updated_at', 'dispatch_center_code', 'originating_type', 'dispatch_center_name', 'dispatch_center_type_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['dispatch_center_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dispatch_center_code' => Yii::t('app', 'Dispatch Center Code'),
            'dispatch_center_name' => Yii::t('app', 'Dispatch Center Name'),
            'union_code' => Yii::t('app', 'Union Name'),
            'dispatch_center_type_code' => Yii::t('app', 'Dispatch Center Type'),
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

    public function getDispatchCenterTypeCode() {
        return $this->hasOne(TblDispatchCenterType::className(), ['dispatch_center_type_code' => 'dispatch_center_type_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getProductGroupCode() {
        return $this->hasOne(TblProductGroup::className(), ['product_group_code' => 'dispatch_center_type_code']);
    }

    public function getDispatchCenterType($type) {
        $product_grp_name = '';
        if (!empty($type)) {
            $dispModel = new TblProductGroup();
            $dispModelData = $dispModel->find()->select(['product_group_name'])->where(['product_group_code' => explode(',', $type)])->all();
            $name = array_map(function($e) {
                return $e['product_group_name'];
            }, $dispModelData);
            $name = array_unique(array_values($name));
            $product_grp_name = implode(',', $name);
        }

        return $product_grp_name;
    }
    
    public function getDispatchCenters($dispatchCenterTypeCode) {
        $orConditions = [
            ['=', 'tbl_dispatch_center.dispatch_center_type_code', $dispatchCenterTypeCode], // Exact match
            ['like', 'tbl_dispatch_center.dispatch_center_type_code', "$dispatchCenterTypeCode,%", false], // Starts with 'code,'
            ['like', 'tbl_dispatch_center.dispatch_center_type_code', "%,$dispatchCenterTypeCode,%", false], // Contains ',code,'
            ['like', 'tbl_dispatch_center.dispatch_center_type_code', "%,$dispatchCenterTypeCode", false], // Ends with ',code'
        ];
        $query = $this->find()
        ->joinWith('userDispatchCenterMapping')
        ->andWhere(array_merge(['or'], $orConditions));
        if(!Yii::$app->user->isSuperadmin){
            $query = $query->andWhere(['tbl_user_dispatch_center_mapping.user_code' => Yii::$app->session->get('UserCode')]);
        }
        $query = $query->all();
        $dispatchCenter = ArrayHelper::map($query, 'dispatch_center_code', 'dispatch_center_name');
        return $dispatchCenter;
    }

    public function getUserDispatchCenterMapping() {
        return $this->hasOne(TblUserDispatchCenterMapping::className(), ['dispatch_center_code' => 'dispatch_center_code']);
    }

}
