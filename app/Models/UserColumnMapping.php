<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserColumnMapping extends Model
{
    use HasFactory;

    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'user_column_mappings';

    /**
     * Поля, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'column_mappings',
    ];

    /**
     * Преобразование типов атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'column_mappings' => 'array', // Автоматически преобразуем JSON в массив
    ];

    /**
     * Отношение к модели User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}