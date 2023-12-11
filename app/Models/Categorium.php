<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categorium
 * 
 * @property int $id_categoria
 * @property string $nombre
 * @property bool $estado
 * 
 * @property Collection|Producto[] $productos
 *
 * @package App\Models
 */
class Categorium extends Model
{
	protected $table = 'categoria';
	protected $primaryKey = 'id_categoria';
	public $timestamps = false;

	protected $casts = [
		'estado' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'estado'
	];

	public function productos()
	{
		return $this->hasMany(Producto::class, 'categoria_id');
	}
}
