<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

   protected $primaryKey = 'id_branch'; // Указываем, что первичный ключ — это `id_branch`
    protected $fillable = ['user_id', 'address']; // Указываем поля, которые можно заполнять

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
