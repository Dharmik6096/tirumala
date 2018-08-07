<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateBased]].
 *
 * @see TblDcsPurchaseRateBased
 */
class TblDcsPurchaseRateBasedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateBased[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateBased|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
