<?php

namespace App\Http\Controllers\Panel\Traits;

use App\Models\Bundle;
use App\Models\BundleCheckpoint;
use App\Models\BundleGift;
use App\Models\BundleHighlight;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

/**
 * GĐ2 - Các trường khóa học theo design mới trong form tạo/sửa bundle của teacher.
 * Bước 1: loại khóa, kỳ thi, band, lộ trình, thời lượng video giới thiệu.
 * Bước 3: giá gốc, combo.
 * Bước 5: quà tặng, slider nội dung, cấu hình học tuần tự, Mid-term/Final.
 */
trait BundleCourseFieldsTrait
{
    /**
     * Các key trong request không phải cột của bảng bundles, phải bỏ trước khi $bundle->update($data).
     */
    protected function bundleExtraRequestKeys(): array
    {
        return ['gifts', 'highlights', 'checkpoints', 'bundle_extras_submitted', 'learning_config_submitted'];
    }

    protected function bundleCourseInfoRules(): array
    {
        return [
            'bundle_type' => 'nullable|in:' . implode(',', Bundle::TYPES),
            'exam_type' => 'nullable|in:' . implode(',', Bundle::EXAM_TYPES),
            'band_from' => 'nullable|numeric|min:0|max:9',
            'band_to' => 'nullable|numeric|min:0|max:9',
            'roadmap_months' => 'nullable|integer|min:1|max:36',
            'demo_video_duration' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Band mục tiêu phải >= band đầu vào (chỉ kiểm tra khi nhập cả hai).
     */
    protected function validateBundleBandRange(array $data): array
    {
        $from = $data['band_from'] ?? null;
        $to = $data['band_to'] ?? null;

        if (is_numeric($from) and is_numeric($to) and (float)$to < (float)$from) {
            return ['band_to' => trans('bundle_learning.band_to_invalid')];
        }

        return [];
    }

    /**
     * Chuẩn hóa dữ liệu bước 1 thành giá trị cột.
     */
    protected function normalizeBundleCourseInfo(array $data): array
    {
        $numberOrNull = function ($value) {
            return (isset($value) and $value !== '' and is_numeric($value)) ? $value : null;
        };

        $bandFrom = $numberOrNull($data['band_from'] ?? null);
        $bandTo = $numberOrNull($data['band_to'] ?? null);
        $roadmapMonths = $numberOrNull($data['roadmap_months'] ?? null);
        $demoDuration = $numberOrNull($data['demo_video_duration'] ?? null);

        return [
            'bundle_type' => in_array($data['bundle_type'] ?? null, Bundle::TYPES, true) ? $data['bundle_type'] : null,
            'exam_type' => in_array($data['exam_type'] ?? null, Bundle::EXAM_TYPES, true) ? $data['exam_type'] : 'ielts',
            'band_from' => !is_null($bandFrom) ? round((float)$bandFrom, 1) : null,
            'band_to' => !is_null($bandTo) ? round((float)$bandTo, 1) : null,
            'roadmap_months' => !is_null($roadmapMonths) ? (int)$roadmapMonths : null,
            'demo_video_duration' => !is_null($demoDuration) ? (int)$demoDuration : null,
        ];
    }

    protected function bundlePricingRules(): array
    {
        return [
            'original_price' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Chuẩn hóa dữ liệu bước 3 (giá gốc, combo).
     */
    protected function normalizeBundlePricing(array $data): array
    {
        $originalPrice = $data['original_price'] ?? null;

        return [
            'original_price' => (isset($originalPrice) and $originalPrice !== '' and is_numeric($originalPrice) and $originalPrice > 0) ? (int)$originalPrice : null,
            'is_combo' => !empty($data['is_combo']),
        ];
    }

    protected function bundleExtrasRules(): array
    {
        return [
            'gifts' => 'nullable|array|max:' . (int)config('bundle_learning.max_gifts', 10),
            'gifts.*.title' => 'nullable|string|max:255',
            'gifts.*.description' => 'nullable|string|max:1000',
            'highlights' => 'nullable|array|max:' . (int)config('bundle_learning.max_highlights', 12),
            'highlights.*.title' => 'nullable|string|max:255',
            'highlights.*.description' => 'nullable|string|max:2000',
            'checkpoints' => 'nullable|array',
            'checkpoints.*.title' => 'nullable|string|max:255',
            'checkpoints.*.unlock_after_modules' => 'nullable|integer|min:1|max:50',
        ];
    }

    /**
     * Kiểm tra quiz chọn cho checkpoint đang bật: bắt buộc chọn và phải thuộc module của khóa.
     * Trả về mảng lỗi theo key "checkpoints.{type}.quiz_id".
     */
    protected function validateBundleCheckpoints(Bundle $bundle, array $data): array
    {
        $errors = [];
        $checkpoints = $data['checkpoints'] ?? [];
        $webinarIds = $bundle->bundleWebinars()->pluck('webinar_id')->all();

        foreach (BundleCheckpoint::TYPES as $type) {
            $checkpoint = $checkpoints[$type] ?? [];

            if (empty($checkpoint['enabled'])) {
                continue;
            }

            $quizId = $checkpoint['quiz_id'] ?? null;

            $validQuiz = !empty($quizId) && !empty($webinarIds) && Quiz::query()
                    ->where('id', $quizId)
                    ->whereIn('webinar_id', $webinarIds)
                    ->whereNull('file_id')
                    ->exists();

            if (!$validQuiz) {
                $errors["checkpoints.{$type}.quiz_id"] = trans('bundle_learning.checkpoint_quiz_required');
            }
        }

        return $errors;
    }

    /**
     * Lưu dữ liệu bước 5. Trả về các cột của bundles cần cập nhật (cờ học tuần tự).
     */
    protected function saveBundleExtras(Bundle $bundle, array $data): array
    {
        if (empty($data['bundle_extras_submitted'])) {
            return [];
        }

        $now = time();

        DB::transaction(function () use ($bundle, $data, $now) {
            // Quà tặng: ghi lại toàn bộ theo thứ tự trên form
            BundleGift::query()->where('bundle_id', $bundle->id)->delete();
            $order = 0;
            foreach (($data['gifts'] ?? []) as $gift) {
                $title = trim($gift['title'] ?? '');
                if ($title === '') {
                    continue;
                }

                $icon = $gift['icon'] ?? 'gift';
                BundleGift::query()->create([
                    'bundle_id' => $bundle->id,
                    'title' => $title,
                    'icon' => in_array($icon, config('bundle_learning.gift_icons', ['gift']), true) ? $icon : 'gift',
                    'description' => trim($gift['description'] ?? '') ?: null,
                    'order' => $order++,
                    'created_at' => $now,
                ]);
            }

            // Slider "Chi tiết và nội dung"
            BundleHighlight::query()->where('bundle_id', $bundle->id)->delete();
            $order = 0;
            foreach (($data['highlights'] ?? []) as $highlight) {
                $title = trim($highlight['title'] ?? '');
                if ($title === '') {
                    continue;
                }

                BundleHighlight::query()->create([
                    'bundle_id' => $bundle->id,
                    'title' => $title,
                    'description' => trim($highlight['description'] ?? '') ?: null,
                    'order' => $order++,
                    'created_at' => $now,
                ]);
            }

            // Mid-term / Final: bật thì tạo/cập nhật, tắt thì xóa
            foreach (BundleCheckpoint::TYPES as $index => $type) {
                $checkpoint = $data['checkpoints'][$type] ?? [];

                if (empty($checkpoint['enabled'])) {
                    BundleCheckpoint::query()->where('bundle_id', $bundle->id)->where('type', $type)->delete();
                    continue;
                }

                $unlockAfter = $checkpoint['unlock_after_modules'] ?? null;

                $existing = BundleCheckpoint::query()->where('bundle_id', $bundle->id)->where('type', $type)->first();
                $values = [
                    'title' => trim($checkpoint['title'] ?? '') ?: null,
                    'quiz_id' => !empty($checkpoint['quiz_id']) ? (int)$checkpoint['quiz_id'] : null,
                    // Final luôn yêu cầu xong tất cả module
                    'unlock_after_modules' => ($type === BundleCheckpoint::TYPE_MID and is_numeric($unlockAfter) and $unlockAfter > 0) ? (int)$unlockAfter : null,
                    'order' => $index,
                ];

                if (!empty($existing)) {
                    $existing->update($values + ['updated_at' => $now]);
                } else {
                    BundleCheckpoint::query()->create($values + [
                            'bundle_id' => $bundle->id,
                            'type' => $type,
                            'created_at' => $now,
                        ]);
                }
            }
        });

        if (empty($data['learning_config_submitted'])) {
            return [];
        }

        return [
            'sequential_modules' => !empty($data['sequential_modules']),
            'sequential_lessons' => !empty($data['sequential_lessons']),
        ];
    }
}
