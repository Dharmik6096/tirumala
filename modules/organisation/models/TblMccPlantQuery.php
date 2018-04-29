<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblMccPlant]].
 *
 * @see TblMccPlant
 */
class TblMccPlantQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMccPlant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMccPlant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
