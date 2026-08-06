<?php
// app/Models/DataInputItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataInputItem extends Model
{
    protected $fillable = [
        'data_input_id', 'boost_type_id', 'start_date',
        'amount', 'mm_kyat', 'discount', 'line_total',
    ];

    protected $casts = [
        'start_date' => 'date',
        'amount'     => 'decimal:2',
        'mm_kyat'    => 'decimal:2',
        'discount'   => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function boostType()
    {
        return $this->belongsTo(BoostType::class);
    }

    public function dataInput()
    {
        return $this->belongsTo(DataInput::class);
    }
}
