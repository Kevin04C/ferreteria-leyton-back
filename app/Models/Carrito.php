<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Carrito
 * 
 * @property int $id_carrito
 * @property int $usuario_id
 * 
 * @property Usuario $usuario
 * @property Collection|Producto[] $productos
 *
 * @package App\Models
 */
class Carrito extends Model
{
	protected $table = 'carrito';
	protected $primaryKey = 'id_carrito';
	public $timestamps = false;

	protected $casts = [
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'usuario_id'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}

	public function productos()
	{
		return $this->belongsToMany(Producto::class, 'carrito_productos')
					->withPivot('cantidad', 'total');
	}
}
