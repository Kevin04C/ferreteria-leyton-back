<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * 
 * @property int $id_producto
 * @property string $nombre
 * @property string $imagen
 * @property string|null $descripcion
 * @property int $categoria_id
 * @property int $provedor_id
 * @property int $cantidad
 * @property float $precio
 * @property bool $estado
 * @property string|null $imagen_id
 * 
 * @property Categorium $categorium
 * @property Proveedor $proveedor
 * @property Collection|Carrito[] $carritos
 * @property Collection|DetalleVentum[] $detalle_venta
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'producto';
	protected $primaryKey = 'id_producto';
	public $timestamps = false;

	protected $casts = [
		'categoria_id' => 'int',
		'provedor_id' => 'int',
		'cantidad' => 'int',
		'precio' => 'float',
		'estado' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'imagen',
		'descripcion',
		'categoria_id',
		'provedor_id',
		'cantidad',
		'precio',
		'estado',
		'imagen_id'
	];

	public function categorium()
	{
		return $this->belongsTo(Categorium::class, 'categoria_id');
	}

	public function proveedor()
	{
		return $this->belongsTo(Proveedor::class, 'provedor_id');
	}

	public function carritos()
	{
		return $this->belongsToMany(Carrito::class, 'carrito_productos')
					->withPivot('cantidad', 'total');
	}

	public function detalle_venta()
	{
		return $this->hasMany(DetalleVentum::class);
	}
}
