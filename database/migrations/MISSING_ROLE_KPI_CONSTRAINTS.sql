-- ============================================
-- BỔ SUNG CHO complete_ielts_platform_migration_updated.sql
-- Copy 2 đoạn này vào file SQL trước khi chạy
-- ============================================

-- ========== Đoạn 1: Thêm sau dòng "ALTER TABLE `sales_kpis` ... AUTO_INCREMENT;" ==========

-- KPI Targets
ALTER TABLE `role_kpi_targets`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- ========== Đoạn 2: Thêm sau dòng "ALTER TABLE `sales_kpis` ADD CONSTRAINT ... CASCADE;" ==========

-- KPI Targets
ALTER TABLE `role_kpi_targets`
  ADD CONSTRAINT `role_kpi_targets_role_id_foreign`
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_kpi_targets_created_by_foreign`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- ============================================
-- SAU KHI THÊM 2 ĐOẠN NÀY → Chạy SQL OK! ✅
-- ============================================
