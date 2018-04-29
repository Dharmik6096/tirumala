<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblBanks]].
 *
 * @see TblBanks
 */
class TblBanksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBanks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBanks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
