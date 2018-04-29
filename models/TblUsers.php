<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_users".
 *
 * @property string $user_code
 * @property integer $is_active
 * @property string $create_at
 * @property integer $is_delete
 * @property string $delete_at
 * @property string $name
 * @property string $password
 * @property string $update_at
 * @property string $user_id
 * @property string $user_name
 *
 * @property TblAnimalType[] $tblAnimalTypes
 * @property TblAnimalType[] $tblAnimalTypes0
 * @property TblAnimalType[] $tblAnimalTypes1
 * @property TblAnimalTypeLocal[] $tblAnimalTypeLocals
 * @property TblAnimalTypeLocal[] $tblAnimalTypeLocals0
 * @property TblAnimalTypeLocal[] $tblAnimalTypeLocals1
 * @property TblBanks[] $tblBanks
 * @property TblBanks[] $tblBanks0
 * @property TblBanks[] $tblBanks1
 * @property TblBanksLocal[] $tblBanksLocals
 * @property TblBanksLocal[] $tblBanksLocals0
 * @property TblBanksLocal[] $tblBanksLocals1
 * @property TblBranches[] $tblBranches
 * @property TblBranches[] $tblBranches0
 * @property TblBranches[] $tblBranches1
 * @property TblBranchesLocal[] $tblBranchesLocals
 * @property TblBranchesLocal[] $tblBranchesLocals0
 * @property TblBranchesLocal[] $tblBranchesLocals1
 * @property TblDcsTypes[] $tblDcsTypes
 * @property TblDcsTypes[] $tblDcsTypes0
 * @property TblDcsTypes[] $tblDcsTypes1
 * @property TblDcsTypesLocal[] $tblDcsTypesLocals
 * @property TblDcsTypesLocal[] $tblDcsTypesLocals0
 * @property TblDcsTypesLocal[] $tblDcsTypesLocals1
 * @property TblDistricts[] $tblDistricts
 * @property TblDistricts[] $tblDistricts0
 * @property TblDistricts[] $tblDistricts1
 * @property TblDistrictsLocal[] $tblDistrictsLocals
 * @property TblDistrictsLocal[] $tblDistrictsLocals0
 * @property TblDistrictsLocal[] $tblDistrictsLocals1
 * @property TblFederations[] $tblFederations
 * @property TblFederations[] $tblFederations0
 * @property TblFederations[] $tblFederations1
 * @property TblFederationsLocal[] $tblFederationsLocals
 * @property TblFederationsLocal[] $tblFederationsLocals0
 * @property TblFederationsLocal[] $tblFederationsLocals1
 * @property TblHamlets[] $tblHamlets
 * @property TblHamlets[] $tblHamlets0
 * @property TblHamlets[] $tblHamlets1
 * @property TblHamletsLocal[] $tblHamletsLocals
 * @property TblHamletsLocal[] $tblHamletsLocals0
 * @property TblHamletsLocal[] $tblHamletsLocals1
 * @property TblLanguages[] $tblLanguages
 * @property TblLanguages[] $tblLanguages0
 * @property TblLanguages[] $tblLanguages1
 * @property TblLedgerGroup[] $tblLedgerGroups
 * @property TblLedgerGroup[] $tblLedgerGroups0
 * @property TblLedgerGroup[] $tblLedgerGroups1
 * @property TblLedgerGroupLocal[] $tblLedgerGroupLocals
 * @property TblLedgerGroupLocal[] $tblLedgerGroupLocals0
 * @property TblLedgerGroupLocal[] $tblLedgerGroupLocals1
 * @property TblLedgerType[] $tblLedgerTypes
 * @property TblLedgerType[] $tblLedgerTypes0
 * @property TblLedgerType[] $tblLedgerTypes1
 * @property TblLedgerTypeLocal[] $tblLedgerTypeLocals
 * @property TblLedgerTypeLocal[] $tblLedgerTypeLocals0
 * @property TblLedgerTypeLocal[] $tblLedgerTypeLocals1
 * @property TblMilkqualityType[] $tblMilkqualityTypes
 * @property TblMilkqualityType[] $tblMilkqualityTypes0
 * @property TblMilkqualityType[] $tblMilkqualityTypes1
 * @property TblMilkqualityTypeLocal[] $tblMilkqualityTypeLocals
 * @property TblMilkqualityTypeLocal[] $tblMilkqualityTypeLocals0
 * @property TblMilkqualityTypeLocal[] $tblMilkqualityTypeLocals1
 * @property TblMiscellaneous[] $tblMiscellaneouses
 * @property TblMiscellaneous[] $tblMiscellaneouses0
 * @property TblMiscellaneous[] $tblMiscellaneouses1
 * @property TblMiscellaneousLocal[] $tblMiscellaneousLocals
 * @property TblMiscellaneousLocal[] $tblMiscellaneousLocals0
 * @property TblMiscellaneousLocal[] $tblMiscellaneousLocals1
 * @property TblRoutes[] $tblRoutes
 * @property TblRoutes[] $tblRoutes0
 * @property TblRoutes[] $tblRoutes1
 * @property TblRoutesLocal[] $tblRoutesLocals
 * @property TblRoutesLocal[] $tblRoutesLocals0
 * @property TblRoutesLocal[] $tblRoutesLocals1
 * @property TblStates[] $tblStates
 * @property TblStates[] $tblStates0
 * @property TblStates[] $tblStates1
 * @property TblStatesLocal[] $tblStatesLocals
 * @property TblStatesLocal[] $tblStatesLocals0
 * @property TblStatesLocal[] $tblStatesLocals1
 * @property TblSubDistricts[] $tblSubDistricts
 * @property TblSubDistricts[] $tblSubDistricts0
 * @property TblSubDistricts[] $tblSubDistricts1
 * @property TblSubDistrictsLocal[] $tblSubDistrictsLocals
 * @property TblSubDistrictsLocal[] $tblSubDistrictsLocals0
 * @property TblSubDistrictsLocal[] $tblSubDistrictsLocals1
 * @property TblUnions[] $tblUnions
 * @property TblUnions[] $tblUnions0
 * @property TblUnions[] $tblUnions1
 * @property TblUnionsLocal[] $tblUnionsLocals
 * @property TblUnionsLocal[] $tblUnionsLocals0
 * @property TblUnionsLocal[] $tblUnionsLocals1
 * @property TblUnits[] $tblUnits
 * @property TblUnits[] $tblUnits0
 * @property TblUnits[] $tblUnits1
 * @property TblUnitsLocal[] $tblUnitsLocals
 * @property TblUnitsLocal[] $tblUnitsLocals0
 * @property TblUnitsLocal[] $tblUnitsLocals1
 * @property TblUserProfileMapping[] $tblUserProfileMappings
 * @property TblUserProfileMapping[] $tblUserProfileMappings0
 * @property TblProfiles[] $profiles
 * @property TblProfiles[] $profiles0
 * @property TblProfiles[] $profiles1
 * @property TblProfiles[] $profiles2
 * @property TblVillageMiscellaneous[] $tblVillageMiscellaneouses
 * @property TblVillageMiscellaneous[] $tblVillageMiscellaneouses0
 * @property TblVillageMiscellaneous[] $tblVillageMiscellaneouses1
 * @property TblVillageMiscellaneousLocal[] $tblVillageMiscellaneousLocals
 * @property TblVillageMiscellaneousLocal[] $tblVillageMiscellaneousLocals0
 * @property TblVillageMiscellaneousLocal[] $tblVillageMiscellaneousLocals1
 * @property TblVillages[] $tblVillages
 * @property TblVillages[] $tblVillages0
 * @property TblVillages[] $tblVillages1
 * @property TblVillagesLocal[] $tblVillagesLocals
 * @property TblVillagesLocal[] $tblVillagesLocals0
 * @property TblVillagesLocal[] $tblVillagesLocals1
 */
class TblUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_code'], 'required'],
            [['is_active', 'is_delete'], 'integer'],
            [['create_at', 'delete_at', 'update_at'], 'safe'],
            [['user_code'], 'string', 'max' => 255],
            [['name', 'password', 'user_name'], 'string', 'max' => 100],
            //[['user_id'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_code' => Yii::t('app', 'User Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'create_at' => Yii::t('app', 'Create At'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'delete_at' => Yii::t('app', 'Delete At'),
            'name' => Yii::t('app', 'Name'),
            'password' => Yii::t('app', 'Password'),
            'update_at' => Yii::t('app', 'Update At'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_name' => Yii::t('app', 'User Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypes()
    {
        return $this->hasMany(TblAnimalType::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypes0()
    {
        return $this->hasMany(TblAnimalType::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypes1()
    {
        return $this->hasMany(TblAnimalType::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypeLocals()
    {
        return $this->hasMany(TblAnimalTypeLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypeLocals0()
    {
        return $this->hasMany(TblAnimalTypeLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAnimalTypeLocals1()
    {
        return $this->hasMany(TblAnimalTypeLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanks()
    {
        return $this->hasMany(TblBanks::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanks0()
    {
        return $this->hasMany(TblBanks::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanks1()
    {
        return $this->hasMany(TblBanks::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanksLocals()
    {
        return $this->hasMany(TblBanksLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanksLocals0()
    {
        return $this->hasMany(TblBanksLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanksLocals1()
    {
        return $this->hasMany(TblBanksLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches()
    {
        return $this->hasMany(TblBranches::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches0()
    {
        return $this->hasMany(TblBranches::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches1()
    {
        return $this->hasMany(TblBranches::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranchesLocals()
    {
        return $this->hasMany(TblBranchesLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranchesLocals0()
    {
        return $this->hasMany(TblBranchesLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranchesLocals1()
    {
        return $this->hasMany(TblBranchesLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypes()
    {
        return $this->hasMany(TblDcsTypes::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypes0()
    {
        return $this->hasMany(TblDcsTypes::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypes1()
    {
        return $this->hasMany(TblDcsTypes::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypesLocals()
    {
        return $this->hasMany(TblDcsTypesLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypesLocals0()
    {
        return $this->hasMany(TblDcsTypesLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsTypesLocals1()
    {
        return $this->hasMany(TblDcsTypesLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistricts()
    {
        return $this->hasMany(TblDistricts::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistricts0()
    {
        return $this->hasMany(TblDistricts::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistricts1()
    {
        return $this->hasMany(TblDistricts::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistrictsLocals()
    {
        return $this->hasMany(TblDistrictsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistrictsLocals0()
    {
        return $this->hasMany(TblDistrictsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDistrictsLocals1()
    {
        return $this->hasMany(TblDistrictsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations()
    {
        return $this->hasMany(TblFederations::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations0()
    {
        return $this->hasMany(TblFederations::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations1()
    {
        return $this->hasMany(TblFederations::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsLocals()
    {
        return $this->hasMany(TblFederationsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsLocals0()
    {
        return $this->hasMany(TblFederationsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsLocals1()
    {
        return $this->hasMany(TblFederationsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamlets()
    {
        return $this->hasMany(TblHamlets::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamlets0()
    {
        return $this->hasMany(TblHamlets::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamlets1()
    {
        return $this->hasMany(TblHamlets::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamletsLocals()
    {
        return $this->hasMany(TblHamletsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamletsLocals0()
    {
        return $this->hasMany(TblHamletsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamletsLocals1()
    {
        return $this->hasMany(TblHamletsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLanguages()
    {
        return $this->hasMany(TblLanguages::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLanguages0()
    {
        return $this->hasMany(TblLanguages::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLanguages1()
    {
        return $this->hasMany(TblLanguages::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroups()
    {
        return $this->hasMany(TblLedgerGroup::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroups0()
    {
        return $this->hasMany(TblLedgerGroup::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroups1()
    {
        return $this->hasMany(TblLedgerGroup::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroupLocals()
    {
        return $this->hasMany(TblLedgerGroupLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroupLocals0()
    {
        return $this->hasMany(TblLedgerGroupLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerGroupLocals1()
    {
        return $this->hasMany(TblLedgerGroupLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypes()
    {
        return $this->hasMany(TblLedgerType::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypes0()
    {
        return $this->hasMany(TblLedgerType::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypes1()
    {
        return $this->hasMany(TblLedgerType::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypeLocals()
    {
        return $this->hasMany(TblLedgerTypeLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypeLocals0()
    {
        return $this->hasMany(TblLedgerTypeLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerTypeLocals1()
    {
        return $this->hasMany(TblLedgerTypeLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypes()
    {
        return $this->hasMany(TblMilkqualityType::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypes0()
    {
        return $this->hasMany(TblMilkqualityType::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypes1()
    {
        return $this->hasMany(TblMilkqualityType::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypeLocals()
    {
        return $this->hasMany(TblMilkqualityTypeLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypeLocals0()
    {
        return $this->hasMany(TblMilkqualityTypeLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkqualityTypeLocals1()
    {
        return $this->hasMany(TblMilkqualityTypeLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneouses()
    {
        return $this->hasMany(TblMiscellaneous::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneouses0()
    {
        return $this->hasMany(TblMiscellaneous::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneouses1()
    {
        return $this->hasMany(TblMiscellaneous::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneousLocals()
    {
        return $this->hasMany(TblMiscellaneousLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneousLocals0()
    {
        return $this->hasMany(TblMiscellaneousLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMiscellaneousLocals1()
    {
        return $this->hasMany(TblMiscellaneousLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutes()
    {
        return $this->hasMany(TblRoutes::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutes0()
    {
        return $this->hasMany(TblRoutes::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutes1()
    {
        return $this->hasMany(TblRoutes::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutesLocals()
    {
        return $this->hasMany(TblRoutesLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutesLocals0()
    {
        return $this->hasMany(TblRoutesLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutesLocals1()
    {
        return $this->hasMany(TblRoutesLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStates()
    {
        return $this->hasMany(TblStates::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStates0()
    {
        return $this->hasMany(TblStates::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStates1()
    {
        return $this->hasMany(TblStates::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStatesLocals()
    {
        return $this->hasMany(TblStatesLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStatesLocals0()
    {
        return $this->hasMany(TblStatesLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStatesLocals1()
    {
        return $this->hasMany(TblStatesLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistricts()
    {
        return $this->hasMany(TblSubDistricts::className(), ['updated_By' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistricts0()
    {
        return $this->hasMany(TblSubDistricts::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistricts1()
    {
        return $this->hasMany(TblSubDistricts::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistrictsLocals()
    {
        return $this->hasMany(TblSubDistrictsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistrictsLocals0()
    {
        return $this->hasMany(TblSubDistrictsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistrictsLocals1()
    {
        return $this->hasMany(TblSubDistrictsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions()
    {
        return $this->hasMany(TblUnions::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions0()
    {
        return $this->hasMany(TblUnions::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions1()
    {
        return $this->hasMany(TblUnions::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsLocals()
    {
        return $this->hasMany(TblUnionsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsLocals0()
    {
        return $this->hasMany(TblUnionsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsLocals1()
    {
        return $this->hasMany(TblUnionsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnits()
    {
        return $this->hasMany(TblUnits::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnits0()
    {
        return $this->hasMany(TblUnits::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnits1()
    {
        return $this->hasMany(TblUnits::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnitsLocals()
    {
        return $this->hasMany(TblUnitsLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnitsLocals0()
    {
        return $this->hasMany(TblUnitsLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnitsLocals1()
    {
        return $this->hasMany(TblUnitsLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUserProfileMappings()
    {
        return $this->hasMany(TblUserProfileMapping::className(), ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUserProfileMappings0()
    {
        return $this->hasMany(TblUserProfileMapping::className(), ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(TblProfiles::className(), ['id' => 'profile_id'])->viaTable('tbl_user_profile_mapping', ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles0()
    {
        return $this->hasMany(TblProfiles::className(), ['id' => 'profile_id'])->viaTable('tbl_user_profile_mapping', ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles1()
    {
        return $this->hasMany(TblProfiles::className(), ['id' => 'profile_id'])->viaTable('tbl_user_profile_mapping', ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles2()
    {
        return $this->hasMany(TblProfiles::className(), ['id' => 'profile_id'])->viaTable('tbl_user_profile_mapping', ['user_code' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneouses()
    {
        return $this->hasMany(TblVillageMiscellaneous::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneouses0()
    {
        return $this->hasMany(TblVillageMiscellaneous::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneouses1()
    {
        return $this->hasMany(TblVillageMiscellaneous::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneousLocals()
    {
        return $this->hasMany(TblVillageMiscellaneousLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneousLocals0()
    {
        return $this->hasMany(TblVillageMiscellaneousLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneousLocals1()
    {
        return $this->hasMany(TblVillageMiscellaneousLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillages()
    {
        return $this->hasMany(TblVillages::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillages0()
    {
        return $this->hasMany(TblVillages::className(), ['created_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillages1()
    {
        return $this->hasMany(TblVillages::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillagesLocals()
    {
        return $this->hasMany(TblVillagesLocal::className(), ['deleted_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillagesLocals0()
    {
        return $this->hasMany(TblVillagesLocal::className(), ['updated_by' => 'user_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillagesLocals1()
    {
        return $this->hasMany(TblVillagesLocal::className(), ['created_by' => 'user_code']);
    }

    /**
     * @inheritdoc
     * @return TblUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUsersQuery(get_called_class());
    }
}
