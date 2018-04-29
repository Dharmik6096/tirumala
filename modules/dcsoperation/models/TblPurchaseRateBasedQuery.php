<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblPurchaseRateBased]].
 *
 * @see TblPurchaseRateBased
 */
class TblPurchaseRateBasedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPurchaseRateBased[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateBased|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
