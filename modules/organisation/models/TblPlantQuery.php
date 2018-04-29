<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblPlant]].
 *
 * @see TblPlant
 */
class TblPlantQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPlant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPlant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
