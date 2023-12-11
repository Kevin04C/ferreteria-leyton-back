<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proveedor
 * 
 * @property int $id_proveedor
 * @property string $nombre
 * @property int $telefono
 * @property string|null $correo
 * @property string $direccion
 * 
 * @property Collection|Producto[] $productos
 *
 * @package App\Models
 */
class Proveedor extends Model
{
	protected $table = 'proveedor';
	protected $primaryKey = 'id_proveedor';
	public $timestamps = false;

	protected $casts = [
		'telefono' => 'int',
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'telefono',
		'correo',
		'direccion',
		'estado'
	];

	public function productos()
	{
		return $this->hasMany(Producto::class, 'provedor_id');
	}
}
