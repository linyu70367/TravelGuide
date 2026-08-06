<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    /**
     * static : 靜態, 不用new class(產生物件)即可引用
     * photo: 圖檔, path: 路徑  middle:是否有中圖, mw:中圖的寬  mh:中圖的高
     * small: 是否有小圖  sw: 小圖的寬  sh: 小圖的高
     */
    public static function uploadPhoto($photo, $path, $middle, $mw, $mh, $small, $sw, $sh)
    {
        // 中圖的寛及高預設為490
        $mwidth = 490;
        $mheight = 490;
        // 小圖的寛及高預設為80
        $swidth = 80;
        $shieght = 80;

        // 如果有傳入中圖的寛, 則使用傳入的寛
        //$mwidth = !empty($mw) ? $mw: $mwidth;
        if (!empty($mw)) {
            $mwidth = $mw;
        }

        // 如果有傳入中圖的高, 則使用傳入的高
        if (!empty($mh)) {
            $mheight = $mh;
        }

        /*
            !isset = empty : 沒有設定檔 = 空的
            isset = !empty : 有設定檔 = 不是空的，但遇到0時， isset(0): 有設定檔 !empty(0) 是空的
        */

        // !empty: 不是空的  isset:有設定
        // 二者在使用上沒有差別, 唯一例外就是當值為0時,會有不同結果  !empty(0): false  isset(0):true
        if (isset($sw)) {
            $swidth = $sw;
        }

        if (isset($sh)) {
            $sheight = $sh;
        }

        // 資料夾不存在時
        if (!file_exists($path)) {
            // 建資料夾, 允許讀(4),寫(2),執行(1)
            mkdir($path, 0777);
        }

        // 副檔名(.jpg, .png)
        //$ext = $photo->extension();
        $ext = "jpg";

        $times = explode(" ", microtime());

        // 將檔名以 年_月_日_時_分_秒_微秒 的格式重新命名:例如 2024_01_22_09_20_40.jpg
        // 目的是避免同時有二個以上檔案上傳時,檔名會重複的問題, 造成不同檔案而檔案相同
        // substr:取字串  substr($times[0], 2, 3): 從times[0]的第三(2+1)個字開始, 取3個字
        // strftime:會出現刪除線的原因是因為這是PHP舊版的語法, 新版的PHP建議可以改用其他語法, 但不會影響使用
        // 因為不確定將來系統上線時,server的版本是否相符, 冒然用新版的語法,萬一server的版本不符,會造成系統災難
        $fileName = strftime("%Y_%m_%d_%H_%M_%S_", $times[1]) . substr($times[0], 2, 3) . "." . $ext;

        // 將上傳檔案由暫存區移至指定資料夾
        $photo->move($path, $fileName);

        if ($middle) {
            if (!file_exists($path . "/M")) {
                mkdir($path . "/M", 0777);
            }

            // 自動縮圖
            // $path ."/M/" . $fileName :要儲存中圖的路徑及檔名
            // $path . "/" . $fileName : 原始圖的路徑及檔案
            // mwidth : 要縮圖的寬
            // mheight: 要縮圖的高
            // "0" : 第一個 0, 是否截切  第二個0, 是否放大
            // ext : 副檔名
            new Resize($path . "/M/" . $fileName, $path . "/" . $fileName, $mwidth, $mheight, "0", "0", $ext);
        }

        if ($small) {
            if (!file_exists($path . "/S")) {
                mkdir($path . "/S", 0777);
            }

            new Resize($path . "/S/" . $fileName, $path . "/" . $fileName, $swidth, $sheight, "0", "0", $ext);
        }

        return $fileName;
    }
}
