<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pdf
 * 
 * @property int $ventas_id
 * @property string $pdf_url
 * 
 * @property Venta $venta
 *
 * @package App\Models
 */
class Pdf extends Model
{
	protected $table = 'pdf';
	protected $primaryKey = 'ventas_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ventas_id' => 'int'
	];

	protected $fillable = [
		'ventas_id',
		'pdf_url'
	];

	public function venta()
	{
		return $this->belongsTo(Venta::class, 'ventas_id');
	}
}
