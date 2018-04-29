<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblUserOrganizationMapping]].
 *
 * @see TblUserOrganizationMapping
 */
class TblUserOrganizationMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUserOrganizationMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUserOrganizationMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
