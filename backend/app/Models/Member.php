<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Override;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = "members";
    protected $primaryKey = 'id';
    protected $fillable = ['memberName', 'email', 'pwd', 'tel', 'birthday', 'address', 'status'];


    public function wishlist(): HasMany
    {
        return $this->hasMany(MemberWishlist::class, "memberId", "id");
    }

    public function getAdminMembers()
    {
        $list = DB::table($this->table)->selectRaw("id, memberName, email, pwd, status, created_at, updated_at")->paginate(10);
        return $list;
    }

    #[Override]
    public function getAuthPassword()
    {
        return $this->pwd;
    }

    public static function checkEmail($email, $id = null)
    {
        $result =  Member::where('email', $email)->where('id', '!=', $id)->exists();
        $isexist = $result ? true : false;
        return $isexist;
    }
}
