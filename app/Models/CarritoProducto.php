<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarritoProducto
 * 
 * @property int $carrito_id
 * @property int $producto_id
 * @property int $cantidad
 * @property float $total
 * 
 * @property Carrito $carrito
 * @property Producto $producto
 *
 * @package App\Models
 */
class CarritoProducto extends Model
{
	protected $table = 'carrito_productos';
	protected $primaryKey = 'id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'carrito_id' => 'int',
		'producto_id' => 'int',
		'cantidad' => 'int',
		'total' => 'float'
	];

	protected $fillable = [
		'id',
		'carrito_id',
		'producto_id',
		'cantidad',
		'total'
	];

	public function carrito()
	{
		return $this->belongsTo(Carrito::class);
	}

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'producto_id');
	}
}
