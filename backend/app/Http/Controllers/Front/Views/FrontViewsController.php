<?php

namespace App\Http\Controllers\Front\Views;

use App\Http\Controllers\Controller;
use App\Models\Views;
use App\Models\ViewsType;
use Illuminate\Http\Request;

class FrontViewsController extends Controller
{
    public function detail(Request $req)
    {
        $id = $req->id;
        $views = new Views();
        // 取得最新消息
        $detail = Views::find($id);
        //在同一個session中,如果未瀏覽過此消息
        if (empty(session()->get("views")))
        {
            // 瀏覽次數加1
            $detail->incrementCnt();
            // 註記此消息已瀏覽
            session()->put("views", $id);
        }

        // 近期消息
        $recentViews = $views->recentViews($id);
        // 上一則
        $prevViews = $views->prevViews($id);
        // 下一則
        $nextViews = $views->nextViews($id);
        // 分類及筆數
        $list = $views->typeList();
                
        return view("front.views.views_detail", compact("detail", "prevViews", "nextViews", "recentViews", "list"));
    }


}
