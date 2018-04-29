<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProductRate]].
 *
 * @see TblProductRate
 */
class TblProductRateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductRate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductRate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
