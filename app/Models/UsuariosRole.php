<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsuariosRole
 * 
 * @property int $rol_id
 * @property int $usuario_id
 * 
 * @property Role $role
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class UsuariosRole extends Model
{
	protected $table = 'usuarios_roles';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'rol_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'rol_id',
		'usuario_id'
	];

	public function role()
	{
		return $this->belongsTo(Role::class, 'rol_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}
}
