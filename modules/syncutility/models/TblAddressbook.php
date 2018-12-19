<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_addressbook".
 *
 * @property integer $id
 * @property integer $destinations
 * @property integer $flag_entry
 * @property string $organization_code
 * @property string $organization_type
 * @property string $source_org_type
 * @property string $sync_url
 * @property string $table_name
 * @property integer $to_child
 * @property integer $to_parent
 * @property integer $type
 * @property string $originating_org_id
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblAddressbook extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_addressbook';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['destinations', 'flag_entry', 'to_child', 'to_parent', 'type', 'originating_type'], 'integer'],
            [['organization_code', 'organization_type', 'source_org_type', 'sync_url', 'table_name', 'originating_org_id', 'originating_org_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'destinations' => Yii::t('app', 'Destinations'),
            'flag_entry' => Yii::t('app', 'Flag Entry'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'sync_url' => Yii::t('app', 'Sync Url'),
            'table_name' => Yii::t('app', 'Table Name'),
            'to_child' => Yii::t('app', 'To Child'),
            'to_parent' => Yii::t('app', 'To Parent'),
            'type' => Yii::t('app', 'Type'),
            'originating_org_id' => Yii::t('app', 'Originating Org ID'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
