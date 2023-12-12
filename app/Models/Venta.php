<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property int $id_venta
 * @property string $dni
 * @property string $nombres
 * @property string $apellidos
 * @property Carbon|null $fecha
 * @property int|null $vendedor
 * 
 * @property Usuario|null $usuario
 * @property DetalleVentum $detalle_ventum
 * @property Pdf $pdf
 *
 * @package App\Models
 */
class Venta extends Model
{
	protected $table = 'ventas';
	protected $primaryKey = 'id_venta';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'vendedor' => 'int',
		'vendido' => 'bool'
	];

	protected $fillable = [
		'dni',
		'nombres',
		'apellidos',
		'fecha',
		'vendedor',
		'vendido'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'vendedor');
	}

	public function detalle_ventum()
	{
		return $this->hasOne(DetalleVentum::class);
	}

	public function pdf()
	{
		return $this->hasOne(Pdf::class, 'ventas_id');
	}
}
