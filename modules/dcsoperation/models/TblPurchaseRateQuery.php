<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblPurchaseRate]].
 *
 * @see TblPurchaseRate
 */
class TblPurchaseRateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPurchaseRate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
