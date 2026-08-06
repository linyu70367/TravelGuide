<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MemberWishlist;
use App\Models\Views;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiWishlistController extends Controller
{
    public function list()
    {
        $member = Auth::user();

        if (empty($member)) {
            return response()->json([
                'status' => false,
                'message' => '尚未登入',
            ], 401);
        }

        $lists = $member->wishlist()->with('views.imgs')->get();
        return response()->json([
            'status' => true,
            'data' => $lists
        ]);
    }

    public function add(Request $req)
    {
        $id = Auth::id();

        if (empty($id)) {
            return response()->json([
                'status' => false,
                'message' => '尚未登入',
            ], 401);
        }

        if (empty($req->viewsId)) {
            return response()->json([
                'status' => false,
                'message' => '找不到景點',
            ], 400);
        }

        $result = MemberWishlist::where('viewsId', $req->viewsId)->where('memberId', $id)->first();
        if (!empty($result)) {
            return response()->json([
                'status' => false,
                'message' => '景點已收藏',
            ], 400);
        }

        $list = new MemberWishlist();
        $list->memberId = $id;
        $list->viewsId = $req->viewsId;
        $list->save();

        return response()->json([
            'status' => true,
            'message' => '新增成功'
        ]);
    }

    public function delete(Request $req)
    {
        $id = Auth::id();

        if (empty($id)) {
            return response()->json([
                'status' => false,
                'message' => '尚未登入',
            ], 401);
        }

        if (empty($req->viewsId)) {
            return response()->json([
                'status' => false,
                'message' => '找不到景點',
            ], 400);
        }

        $list = MemberWishlist::where('memberId', $id)->where('viewsId', $req->viewsId)->first();
        $list->delete();

        return response()->json([
            'status' => true,
            'message' => '刪除成功'
        ]);
    }

    public function checkLiked(Request $req)
    {
        if (Auth::check()) {
            $id = Auth::id();

            $result = MemberWishlist::where('memberId', $id)->where('viewsId', $req->viewsId)->first();

            if (!empty($result)) {
                return response()->json([
                    'status' => true,
                    'message' => '有收藏'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => "沒有收藏"
        ], 401);
    }

    public function getLikes(Request $req)
    {
        if (empty($req->viewsId)) {
            return response()->json([
                'status' => false,
                'message' => '找不到景點',
            ], 400);
        }

        $view = Views::with('wishlists')->find($req->viewsId);
        $likecnt = $view->wishlists->count();
        return response()->json([
            'status' => true,
            'likes' => $likecnt
        ]);
    }
}
