<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes as EloquentSoftDeletes;

class Company extends Model
{
    use HasUuids , SoftDeletes ;

    protected $table = 'companies';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'logo_path',
        'industry',
        'size',
        'location',
        'website',
        'email',
        'phone',
    ];

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
