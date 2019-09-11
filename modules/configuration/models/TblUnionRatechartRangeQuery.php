<?php

namespace app\modules\configuration\models;

/**
 * This is the ActiveQuery class for [[TblUnionRatechartRange]].
 *
 * @see TblUnionRatechartRange
 */
class TblUnionRatechartRangeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnionRatechartRange[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnionRatechartRange|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
