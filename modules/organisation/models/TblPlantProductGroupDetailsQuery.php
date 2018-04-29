<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblPlantProductGroupDetails]].
 *
 * @see TblPlantProductGroupDetails
 */
class TblPlantProductGroupDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPlantProductGroupDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPlantProductGroupDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
