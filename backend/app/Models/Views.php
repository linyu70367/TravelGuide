<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Views extends Model
{
    protected $table = "views";
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'city', 'town', 'address', 'typeId', 'brief', 'content', 'tel', 'like'];

    public function imgs()
    {
        // 景點 id 對應到 imgs 資料表中的 viewsId
        return $this->hasMany(Img::class, 'viewsId', 'id');
    }

    // 類別名稱
    public function types(): BelongsTo
    {
        /*
            SELECT a.*, b.* FROM news a INNER JOIN news_type b ON a.typeId = b.id

            DB::table("news AS a")->join("news_type AS b")->selectRaw("a.*, b.*)->get();
            或DB::table("news AS a")->selectRaw("a.*, b.*)->join("news_type AS b")->get();
        */
        return $this->belongsTo(ViewsType::class, "typeId");
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(MemberWishlist::class, "viewsId", "id");
    }

    // 用id取上一則
    public function prevViews(int $id)
    {
        // first():取第一筆
        // SELECT * FROM news WEHRE id<xxx ORDER BY id DESC LIMIT 1
        $prevViews = Views::where("id", "<", $id)->orderByDesc("id")->first();
        return $prevViews;
    }

    // 下一則
    public function nextViews(int $id)
    {
        // SELECT * FROM news WEHRE id > xxx ORDER BY id ASC LIMIT 1
        $nextViews = Views::where("id", ">", $id)->orderBy("id")->first();
        return $nextViews;
    }

    // 近期消息
    public function recentViews(int $id)
    {
        /*
            latest: 取最後的(由大到小), ORDER BY id DESC
            take: 取幾筆, 等同於Limit
            SELECT * FROM news WEHRE id != xxx ORDER BY id DESC LIMIT 3
        */
        // $recentViews = Views::where("id", "!=", $id)->latest("id")->take(3)->get();
        $recentViews = Views::with('imgs')->latest()->take(5)->get();
        return $recentViews;
    }

    // 分類及筆數
    public function typeList()
    {
        // withCount:計算筆數
        // SELECT b.typeName, count(a.id) AS news_count FROM news a INNER JOIN news_type b ON a.typeId = b.id GROUP BY b.typeName
        $list = ViewsType::withCount("views")->get();

        return $list;
    }

    public function getView(Request $req)
    {
        $sql = Views::with("types", "imgs");

        if ($req->filled("typeId")) // 判斷typeId是否有值(有沒有選取類別)
        {
            $sql->where("typeId", $req->typeId);
        }

        if ($req->filled("keywords")) {
            $sql->where("name", "LIKE", "%" . $req->keywords . "%");
        }

        $views = $sql->get(); // get:全部資料，也可以用all()

        return $views;
    }

    public function incrementCnt()
    {
        $this->increment("like");
    }
}
