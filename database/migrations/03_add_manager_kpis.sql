-- ============================================
-- BỔ SUNG: MANAGER KPI TABLE
-- Thêm vào file complete_ielts_platform_migration_updated.sql
-- ============================================

-- VỊ TRÍ: Thêm sau bảng `admin_kpis`, trước `sales_kpis`

CREATE TABLE `manager_kpis` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(10) UNSIGNED NOT NULL,
  `period_type` enum('daily','weekly','monthly','quarterly') NOT NULL,
  `period_date` date NOT NULL,
  -- Team performance metrics
  `total_teachers` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_admins` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_sales` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_team_kpi_achievement` decimal(5,2) DEFAULT NULL COMMENT 'Average KPI achievement % of team',
  -- Student metrics
  `total_active_students` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `new_students_enrolled` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `student_retention_rate` decimal(5,2) DEFAULT NULL COMMENT 'Percentage',
  `avg_student_satisfaction` decimal(3,2) DEFAULT NULL COMMENT '0.00-5.00 stars',
  -- Revenue metrics (overview)
  `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `target_revenue` decimal(15,2) DEFAULT NULL,
  `revenue_achievement_rate` decimal(5,2) DEFAULT NULL,
  -- Quality metrics
  `course_completion_rate` decimal(5,2) DEFAULT NULL COMMENT 'Overall completion rate',
  `avg_test_pass_rate` decimal(5,2) DEFAULT NULL COMMENT 'Students passing tests',
  `content_quality_score` decimal(5,2) DEFAULT NULL COMMENT 'Based on reviews/reports',
  -- Operational metrics
  `pending_approvals` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Content waiting approval',
  `escalated_issues` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `resolved_escalations` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `manager_period` (`manager_id`,`period_type`,`period_date`),
  KEY `manager_id` (`manager_id`),
  KEY `period_date` (`period_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manager performance and team oversight KPIs';

-- ============================================
-- PRIMARY KEY (thêm vào section PRIMARY KEYS)
-- ============================================

ALTER TABLE `manager_kpis`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- ============================================
-- FOREIGN KEY (thêm vào section FOREIGN KEYS)
-- ============================================

ALTER TABLE `manager_kpis`
  ADD CONSTRAINT `manager_kpis_manager_id_foreign`
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- ============================================
-- NOTES
-- ============================================
-- Manager KPIs khác Admin KPIs:
-- - Admin: Xử lý tickets, kiểm duyệt nội dung (operational)
-- - Manager: Quản lý team, doanh thu, chất lượng (strategic)
--
-- Sau khi thêm: Total = 24 tables (22 trước + manager_kpis)
-- Update comment cuối file: "Total: 22 tables + 2 translation tables = 24 tables"
-- ============================================
