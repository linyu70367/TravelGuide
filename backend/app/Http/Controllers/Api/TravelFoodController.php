<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class TravelFoodController extends Controller
{
    /**
     * 取得全部清洗後的美食資料。
     *
     * GET /api/travelfoods
     */
    public function index(): JsonResponse
    {
        try {
            $foods = $this->getCleanFoods();

            return response()->json([
                'status' => true,
                'count' => $foods->count(),
                'data' => $foods,
            ]);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                'status' => false,
                'message' => '美食資料暫時無法取得',
                'data' => [],
            ], 503);
        }
    }

    /**
     * 取得指定整數 ID 的美食資料。
     *
     * GET /api/travelfoods/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $food = $this->getCleanFoods()->firstWhere('id', $id);

            if (!$food) {
                return response()->json([
                    'status' => false,
                    'message' => '找不到指定的美食資料',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $food,
            ]);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                'status' => false,
                'message' => '美食資料暫時無法取得',
            ], 503);
        }
    }

    /**
     * 從農業部 API 取得資料並進行清洗。
     */
    private function getCleanFoods(): Collection
    {
        // 1. 快取只負責儲存「純陣列 (array)」
        $cleanArray = Cache::remember('travel_foods_clean_data', now()->addHour(), function () {
            try {
                $response = Http::withoutVerifying()
                    ->connectTimeout(5)
                    ->timeout(15)
                    ->retry(2, 300)
                    ->get('https://data.moa.gov.tw/Service/OpenData/ODwsv/ODwsvTravelFood.aspx', [
                        'IsTransData' => '1',
                        'UnitId' => '193',
                    ]);

                if ($response->successful()) {
                    $result = $response->json() ?? [];

                    $source = isset($result['Data']) && is_array($result['Data'])
                        ? $result['Data']
                        : $result;

                    if (is_array($source) && !empty($source)) {
                        return collect($source)
                            ->filter(fn($item) => is_array($item))
                            ->values()
                            ->map(function (array $item, int $index) {
                                return [
                                    'id' => $index + 1,
                                    'Name' => $this->cleanText($item['Name'] ?? ''),
                                    'Address' => $this->cleanText($item['Address'] ?? ''),
                                    'Tel' => $this->cleanText($item['Tel'] ?? ''),
                                    'Url' => $this->cleanText($item['Url'] ?? $item['URL'] ?? ''),
                                    'Email' => $this->cleanText($item['Email'] ?? ''),
                                    'FoodFeature' => $this->cleanText($item['FoodFeature'] ?? $item['HostWords'] ?? ''),
                                    'City' => $this->cleanText($item['City'] ?? ''),
                                    'Town' => $this->cleanText($item['Town'] ?? ''),
                                    'PicURL' => $this->cleanText($item['PicURL'] ?? ''),
                                ];
                            })
                            ->toArray(); // 轉成純陣列後寫入 Cache
                    }
                }
            } catch (Throwable $e) {
                report($e);
            }

            // 2. 若 API 失敗或無資料，回傳備援資料
            return $this->getFallbackFoods();
        });

        // 3. 從快取取出陣列後，再轉成 Collection 回傳給 Controller 使用
        return collect($cleanArray);
    }

    /**
     * 農業部 API 異常時使用的備援資料。
     */
    private function getFallbackFoods(): array
    {
        return [
            [
                'id' => 1,
                'Name' => '在地經典美食（備援資料）',
                'Address' => '台北市中正區重慶南路一段 1 號',
                'Tel' => '02-23456789',
                'Url' => '',
                'Email' => '',
                'FoodFeature' => '當農業部 API 暫時無法連線時顯示的測試資料。',
                'City' => '臺北市',
                'Town' => '中正區',
                'PicURL' => '',
            ],
        ];
    }

    /**
     * 清除 HTML、換行與多餘空白。
     */
    private function cleanText(mixed $value): string
    {
        if (!is_scalar($value)) {
            return '';
        }

        $value = strip_tags((string) $value);
        $value = preg_replace('/\s+/u', ' ', $value);

        return trim($value);
    }
}