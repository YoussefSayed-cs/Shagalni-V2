<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSeekerProfile extends Model
{
    use HasUuids;

    protected $table = 'job_seeker_profiles';

    protected $fillable = [
        'user_id',
        'phone',
        'location',
        'bio',
        'avatar_path',
        'linkedin_url',
        'github_url',
        'portfolio_url',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
