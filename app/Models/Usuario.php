<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class Usuario
 * 
 * @property int $id
 * @property string $nombre
 * @property string $apellidos
 * @property int $dni
 * @property string $correo
 * @property string $contrasena
 * @property bool $estado
 * 
 * @property Carrito $carrito
 * @property Collection|Role[] $roles
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Usuario extends Authenticatable implements JWTSubject
{
	use Notifiable;

	protected $table = 'usuario';
	/**
	 * Summary of timestamps
	 * @var 
	 */
	public $timestamps = false;

	/**
	 * Summary of casts
	 * @var array
	 */
	protected $casts = [
		'dni' => 'int',
		'estado' => 'bool'
	];

	/**
	 * Summary of fillable
	 * @var array
	 */
	protected $fillable = [
		'nombre',
		'apellidos',
		'dni',
		'correo',
		'contrasena',
		'estado'
	];

	/**
	 * Summary of carrito
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function carrito()
	{
		return $this->hasOne(Carrito::class);
	}

	/**
	 * Summary of roles
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(Role::class, 'usuarios_roles', 'usuario_id', 'rol_id');
	}

	/**
	 * Summary of ventas
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function ventas()
	{
		return $this->hasMany(Venta::class, 'vendedor');
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
