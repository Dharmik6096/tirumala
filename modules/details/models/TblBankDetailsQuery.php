<?php

namespace app\modules\details\models;

/**
 * This is the ActiveQuery class for [[TblBankDetails]].
 *
 * @see TblBankDetails
 */
class TblBankDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBankDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBankDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
