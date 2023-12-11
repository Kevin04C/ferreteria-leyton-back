<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleVentum
 * 
 * @property int $venta_id
 * @property int $producto_id
 * @property int $cantidad
 * @property float $total
 * 
 * @property Producto $producto
 * @property Venta $venta
 *
 * @package App\Models
 */
class DetalleVentum extends Model
{
	protected $table = 'detalle_venta';
	protected $primaryKey = 'id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'venta_id' => 'int',
		'producto_id' => 'int',
		'cantidad' => 'int',
		'total' => 'float'
	];

	protected $fillable = [
		'id',
		'venta_id',
		'producto_id',
		'cantidad',
		'total'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class);
	}

	public function venta()
	{
		return $this->belongsTo(Venta::class);
	}
}
