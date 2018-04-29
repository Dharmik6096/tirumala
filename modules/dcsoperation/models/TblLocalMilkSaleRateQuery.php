<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblLocalMilkSaleRate]].
 *
 * @see TblLocalMilkSaleRate
 */
class TblLocalMilkSaleRateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleRate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleRate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
