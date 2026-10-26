<?php

namespace App\Support\Placement;

/**
 * Danh sách khoá học gợi ý ở trang kết quả Placement Test.
 *
 * DỮ LIỆU GIẢ — dùng chung cho:
 *   - trang kết quả học viên (/placement-test/result): thẻ khoá học + dropdown form tư vấn
 *   - trang admin "Yêu cầu tư vấn": bộ lọc theo khoá học
 *
 * TODO: khi có khoá học chính thức, thay all() bằng truy vấn thật
 * (VD: Webinar theo category IELTS) và giữ nguyên cấu trúc mảng trả về
 * để các view không phải sửa. `levels` = các final_level của Placement
 * Test được coi là phù hợp với khoá đó (dùng để gắn nhãn "Phù hợp với bạn").
 */
class PlacementCourseCatalog
{
    public static function all(): array
    {
        return [
            [
                'key'         => 'foundation',
                'title'       => 'Foundation IELTS',
                'range'       => 'A1 - A2',
                'description' => 'Xây nền vững chắc cho hành trình chinh phục IELTS.',
                'tier'        => 'Beginner',
                'duration'    => '8 - 10 tuần',
                'icon'        => 'fa-seedling',
                'levels'      => ['A1', 'A2'],
                'buy_url'     => '#',
                'detail_url'  => '#',
            ],
            [
                'key'         => 'intermediate',
                'title'       => 'Intermediate IELTS',
                'range'       => 'B1 - B2',
                'description' => 'Nâng cao toàn diện 4 kỹ năng với phương pháp bài bản.',
                'tier'        => 'Intermediate',
                'duration'    => '8 - 12 tuần',
                'icon'        => 'fa-rocket',
                'levels'      => ['B1', 'B2'],
                'buy_url'     => '#',
                'detail_url'  => '#',
            ],
            [
                'key'         => 'advanced',
                'title'       => 'Advanced IELTS',
                'range'       => 'C1 - C2',
                'description' => 'Luyện chuyên sâu, tối ưu chiến lược để đạt band điểm cao.',
                'tier'        => 'Advanced',
                'duration'    => '8 - 12 tuần',
                'icon'        => 'fa-trophy',
                'levels'      => ['B2+'],
                'buy_url'     => '#',
                'detail_url'  => '#',
            ],
        ];
    }

    /** key => title, dùng cho dropdown / validate. */
    public static function options(): array
    {
        return collect(self::all())->pluck('title', 'key')->all();
    }

    public static function find(?string $key): ?array
    {
        return collect(self::all())->firstWhere('key', $key);
    }

    public static function recommendedKeyFor(?string $finalLevel): ?string
    {
        foreach (self::all() as $course) {
            if (in_array((string) $finalLevel, $course['levels'] ?? [], true)) {
                return $course['key'];
            }
        }

        return null;
    }
}
