<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'level',
        'var_dump',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public static function note($name, $descrip, $level, $user_id = null, $varDump = [], $type = '')
    {
        return Note::create([
            'name' => $name,
            'description' => $descrip,
            'level' => $level,
            'user_id' => $user_id,
            'var_dump' => json_encode($varDump),
            'type' => $type
        ]);
    }
}
