<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_attachment".
 *
 * @property string $attachment_code
 * @property string $module_name
 * @property string $module_code
 * @property string $attachment_type
 * @property string $remarks
 * @property string $attachment
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $thumbnail
 */
class TblAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attachment_code'], 'required'],
            [['attachment', 'thumbnail'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['attachment_code', 'module_code', 'attachment_type', 'created_by', 'updated_by'], 'string', 'max' => 20],
            [['module_name', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'attachment_type' => Yii::t('app', 'Attachment Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'attachment' => Yii::t('app', 'Attachment'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
        ];
    }
    
    public function getData() {
        $replaceServer = Yii::$app->params['attachment_server'];
        if (!empty($replaceServer)) {
            $currentServer = Yii::$app->request->serverName;
            return $this->find()->select(['attachment_code',
                                'module_name',
                                'module_code',
                                'attachment_type',
                                'remarks',
                                'attachment' => "REPLACE(attachment,'$replaceServer','$currentServer')",
                                'thumbnail' => "REPLACE(thumbnail,'$replaceServer','$currentServer')"])
                            ->where(['module_code' => $this->module_code, 'module_name' => $this->module_name])
                            ->andFilterWhere(['remarks' => $this->remarks])
                            ->orderBy('created_at desc')->all();
        }
        return $this->find()->where(['module_code' => $this->module_code, 'module_name' => $this->module_name])
                        ->andFilterWhere(['remarks' => $this->remarks])
                        ->orderBy('created_at desc')->all();
    }
}
