<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLegalInfo extends Model
{
    /**
     * Название таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'user_legal_info';

    /**
     * Поля, которые можно массово присваивать.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'organization_name',
        'legal_address',
        'inn',
        'kpp',
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