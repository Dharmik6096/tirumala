<?php

namespace app\modules\webservice\eipl\models;

/**
 * This is the ActiveQuery class for [[TblIdentityMaster]].
 *
 * @see TblIdentityMaster
 */
class TblIdentityMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblIdentityMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblIdentityMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
