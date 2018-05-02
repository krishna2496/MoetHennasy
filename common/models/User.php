<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\models\RolePermission;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    public $new_password;
    public $confirm_password;
    public $userImage;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['username','first_name','last_name','email','role_id','status','device_type'], 'required'],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password','skipOnEmpty' => false,'message' => "Password doesn't match"],
            [['new_password','confirm_password'], 'string', 'min' => 6],
            [['first_name','last_name','username','password_hash','email','device_token','latitude','longitude','profile_photo'],'string','max'=>255,'on' => ['create','update']],
            [['role_id','parent_user_id'],'integer','on' => ['create','update']],
            [['email'], 'email'],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'integer','on' => ['create','update']],
            [['username','email'], 'unique'],
            [['userImage'], 'file','extensions'=>'jpg,png,jpeg','on' => ['create','update']],
            ['parent_user_id', 'required', 'when' => function ($model) { return ($model->role_id != Yii::$app->params['marketAdministratorRole'] && $model->role_id != Yii::$app->params['superAdminRole']); }, 'whenClient' => "function (attribute, value) { return $('#user-role_id').val() != '".Yii::$app->params['marketAdministratorRole']."'; }"],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t("app", "view_lbl_username"),
            'status' => Yii::t("app", "view_lbl_status"),
            'first_name' => Yii::t("app", "view_lbl_first_name"),
            'last_name' => Yii::t("app", "view_lbl_last_name"),
            'email' => Yii::t("app", "view_lbl_email"),
            'role_id' => Yii::t("app", "view_lbl_role"),
            'new_password' => Yii::t("app", "view_lbl_password"),
            'parent_user_id' => Yii::t("app", "view_lbl_parent_user_id"),
            'profile_photo' => Yii::t("app", "view_lbl_profile_photo"),
            
        ];
    }
    
    public static function findIdentity($id)
    {
        $cookies = Yii::$app->request->cookies;
        $authKey = $cookies->getValue('auth_key', '');

        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE, 'auth_key' => $authKey]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getPermissions(){
        return $this->hasMany(RolePermission::className(), ['role_id' => 'role_id'])->with('permission');
    }

    public function getRole(){
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
