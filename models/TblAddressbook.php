<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_addressbook".
 *
 * @property integer $id
 * @property boolean $flag_entry
 * @property string $organization_code
 * @property string $organization_type
 * @property string $sync_url
 * @property string $table_name
 */
class TblAddressbook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_addressbook';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flag_entry'], 'boolean'],
            [['organization_code', 'organization_type', 'sync_url', 'table_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'flag_entry' => Yii::t('app', 'Flag Entry'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'sync_url' => Yii::t('app', 'Sync Url'),
            'table_name' => Yii::t('app', 'Table Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblAddressbookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblAddressbookQuery(get_called_class());
    }
}
