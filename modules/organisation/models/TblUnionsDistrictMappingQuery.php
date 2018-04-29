<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblUnionsDistrictMapping]].
 *
 * @see TblUnionsDistrictMapping
 */
class TblUnionsDistrictMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
