<?php

namespace app\modules\sms\models;

/**
 * This is the ActiveQuery class for [[TblApiMaster]].
 *
 * @see TblApiMaster
 */
class TblApiMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblApiMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblApiMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
