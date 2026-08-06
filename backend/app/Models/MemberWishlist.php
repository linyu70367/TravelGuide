<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberWishlist extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "member_wishlists";
    protected $primaryKey = 'id';
    protected $fillable = ["id", "memberId", "viewsId", "created_at"];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, "memberId", "id");
    }

    public function views(): BelongsTo
    {
        return $this->belongsTo(Views::class, "viewsId", "id");
    }
}
