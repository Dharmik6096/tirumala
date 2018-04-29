<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SftpRecords]].
 *
 * @see SftpRecords
 */
class SftpRecordsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SftpRecords[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SftpRecords|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
