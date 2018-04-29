<?php

namespace app\modules\verification\models;

/**
 * This is the ActiveQuery class for [[TblKycRecord]].
 *
 * @see TblKycRecord
 */
class TblKycRecordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblKycRecord[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblKycRecord|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
