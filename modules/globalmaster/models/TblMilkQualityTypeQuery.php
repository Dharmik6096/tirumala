<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblMilkQualityType]].
 *
 * @see TblMilkQualityType
 */
class TblMilkQualityTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkQualityType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkQualityType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
