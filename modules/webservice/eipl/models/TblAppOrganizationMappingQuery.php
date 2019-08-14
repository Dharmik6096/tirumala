<?php

namespace app\modules\webservice\eipl\models;

/**
 * This is the ActiveQuery class for [[TblAppOrganizationMapping]].
 *
 * @see TblAppOrganizationMapping
 */
class TblAppOrganizationMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblAppOrganizationMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblAppOrganizationMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
