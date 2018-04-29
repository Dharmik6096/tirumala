<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblPlantProductGroup]].
 *
 * @see TblPlantProductGroup
 */
class TblPlantProductGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPlantProductGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPlantProductGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
