<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Admin\PlacementConsultRequestController as BaseController;

/**
 * Trang "Yêu cầu tư vấn" trong /panel — dành cho role Admin (không vào được /admin).
 * Toàn bộ logic lọc / cập nhật / xoá và kiểm quyền nằm ở lớp cha; ở đây chỉ đổi
 * view và tiền tố tên route.
 */
class PlacementConsultRequestController extends BaseController
{
    protected string $view = 'design_1.panel.placement_consults.index';

    protected string $routeName = 'panel.placement_consults';
}
