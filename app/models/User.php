<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use app\library\files\v0\FileProvider;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function getRememberToken()
	{
		return $this->remember_token;
	}
	
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}
	
	public function scopeContact($query, $project)
	{
		//$contact = 'contact_'.$project;
		//return $query->leftJoin($contact,$this->table.'.id','=',$contact.'.id')->where('active','1')->where($this->table.'.id',$this->getAuthIdentifier())->first();
	}
	
	public $fileProvider;
	
	public function get_file_provider() {
		$this->fileProvider = new FileProvider();
		return $this->fileProvider;
	}
	public function docsHasRequester() {
		return $this->hasMany('VirtualFile', 'id_user');//->has('requester','=',0);
	}
	
	public function contact($project) {
		$instance = new Contact;
		$instance->setTable('contact_'.$project);
		return new HasOne($instance->newQuery(), $this, $instance->getTable().'.id', 'id');
	}

}