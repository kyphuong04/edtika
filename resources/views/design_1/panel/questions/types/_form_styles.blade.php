{{-- Shared styles for question type forms --}}
<style>
.type-form-content {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.type-form-content h4 {
    display: flex;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 16px !important;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.type-form-content h4 i {
    font-size: 18px;
}

.type-form-content .form-group {
    margin-bottom: 16px;
}

.type-form-content .form-group-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.type-form-content .form-group-label.is-required::after {
    content: ' *';
    color: #dc3545;
}

.type-form-content .form-control {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    padding: 10px 12px;
    font-size: 14px;
}

.type-form-content .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.type-form-content select.form-control {
    cursor: pointer;
}

/* Info boxes */
.type-form-content .info-box {
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 13px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.type-form-content .info-box i { flex-shrink: 0; margin-top: 2px; }
.type-form-content .info-box.info { background: rgba(59, 130, 246, 0.1); color: #1e40af; }
.type-form-content .info-box.success { background: rgba(16, 185, 129, 0.1); color: #065f46; }
.type-form-content .info-box.warning { background: rgba(245, 158, 11, 0.1); color: #92400e; }
.type-form-content .info-box.danger { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
.type-form-content .info-box.purple { background: rgba(139, 92, 246, 0.1); color: #5b21b6; }

/* Options grid - for multiple choice */
.type-form-content .options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 12px;
}

.type-form-content .option-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.type-form-content .option-letter {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    color: #374151;
    flex-shrink: 0;
}

.type-form-content .option-item .form-control {
    flex: 1;
}

/* Correct answer checkboxes */
.type-form-content .answer-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.type-form-content .answer-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.type-form-content .answer-checkbox:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.type-form-content .answer-checkbox input:checked + span {
    font-weight: 600;
    color: #059669;
}

/* Dynamic inputs container */
.type-form-content .dynamic-inputs {
    margin-bottom: 8px;
}

.type-form-content .input-group {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.type-form-content .input-group .form-control {
    flex: 1;
}

.type-form-content .input-group .input-group-text,
.type-form-content .input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    padding: 0 10px;
    background: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    color: #374151;
}

.type-form-content .input-group .btn-sm {
    padding: 8px 12px;
    border-radius: 8px;
}

.type-form-content .alternatives-list {
    margin-bottom: 8px;
}

/* Buttons */
.type-form-content .btn-outline-secondary {
    background: #f9fafb;
    border: 1px dashed #d1d5db;
    color: #6b7280;
    transition: all 0.2s;
}

.type-form-content .btn-outline-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.type-form-content .btn-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.type-form-content .btn-danger:hover {
    background: #fee2e2;
}

/* Divider */
.type-form-content hr {
    border: 0;
    border-top: 1px solid #e5e7eb;
    margin: 20px 0;
}

/* Toggle switch */
.type-form-content .switch-group {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
}

.type-form-content .switch-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.type-form-content .switch-group label {
    margin-bottom: 0;
    font-size: 13px;
    color: #4b5563;
    cursor: pointer;
}

/* Text muted */
.type-form-content .text-muted {
    font-size: 12px;
    color: #6b7280 !important;
}

/* Colors */
.type-form-content .text-success { color: #059669 !important; }
.type-form-content .text-info { color: #0284c7 !important; }
.type-form-content .text-warning { color: #d97706 !important; }
.type-form-content .text-danger { color: #dc2626 !important; }
.type-form-content .text-primary { color: #2563eb !important; }
.type-form-content .text-purple { color: #7c3aed !important; }
.type-form-content .text-secondary { color: #6b7280 !important; }

/* Row spacing fix */
.type-form-content .row {
    margin-left: -8px;
    margin-right: -8px;
}

.type-form-content .row > [class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
}

/* Ensure proper z-index */
.type-form-content .form-control,
.type-form-content .input-group-text,
.type-form-content .btn {
    position: relative;
    z-index: 1;
}

/* ============================================
   BATCH MODE UNIFIED STYLES
   ============================================ */

/* Batch table header */
.type-form-content .batch-header {
    display: flex;
    align-items: center;
    margin: 0 -20px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.batch-header .col-check { width: 44px; text-align: center; }
.batch-header .col-qnum { width: 56px; text-align: center; }
.batch-header .col-action { width: 44px; }

/* Batch row base */
.type-form-content .batch-row {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    margin-bottom: 8px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    background: #fff;
    transition: all 0.2s ease;
}

.type-form-content .batch-row:hover {
    border-color: #adb5bd;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.type-form-content .batch-row.selected {
    border-color: var(--type-color, #667eea);
    background: var(--type-bg, rgba(102, 126, 234, 0.03));
}

.type-form-content .batch-row.has-error {
    border-color: #dc3545 !important;
    background: rgba(220, 53, 69, 0.03) !important;
}

.type-form-content .batch-row.is-valid {
    border-color: #28a745 !important;
}

/* Q Number Badge - unified */
.type-form-content .q-badge {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    color: #fff;
    background: var(--type-gradient, linear-gradient(135deg, #667eea 0%, #764ba2 100%));
    border-radius: 8px;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Checkbox unified */
.type-form-content .batch-check {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--type-color, #667eea);
}

/* Input validation states */
.type-form-content .form-control.is-invalid {
    border-color: #dc3545;
    background-image: none;
}

.type-form-content .form-control.is-valid {
    border-color: #28a745;
    background-image: none;
}

.type-form-content .form-control:invalid:not(:placeholder-shown) {
    border-color: #ffc107;
}

/* Input with status indicator */
.type-form-content .input-wrap {
    position: relative;
    flex: 1;
}

.type-form-content .input-wrap .status-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.2s;
}

.type-form-content .input-wrap.filled .status-icon.check { opacity: 1; color: #28a745; }
.type-form-content .input-wrap.empty .status-icon.warn { opacity: 1; color: #ffc107; }
.type-form-content .input-wrap.error .status-icon.error { opacity: 1; color: #dc3545; }

/* Answer field highlight */
.type-form-content .answer-field {
    background: #f8fff8;
    border-color: #c3e6cb;
}

.type-form-content .answer-field:focus {
    background: #fff;
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
}

/* Footer actions */
.type-form-content .batch-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e9ecef;
}

.type-form-content .batch-counter {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6c757d;
}

.type-form-content .batch-counter strong {
    font-size: 18px;
    color: var(--type-color, #667eea);
}

/* Add button unified */
.type-form-content .btn-add-row {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, var(--type-color, #667eea) 0%, var(--type-color-dark, #5a67d8) 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.type-form-content .btn-add-row:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.type-form-content .btn-add-row:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Remove button */
.type-form-content .btn-remove {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.type-form-content .btn-remove:hover {
    background: #fff5f5;
    border-color: #dc3545;
    color: #dc3545;
}

/* Progress indicator */
.type-form-content .batch-progress {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-radius: 8px;
    margin-top: 12px;
}

.type-form-content .batch-progress .progress-bar {
    flex: 1;
    height: 6px;
    background: #fff;
    border-radius: 3px;
    overflow: hidden;
}

.type-form-content .batch-progress .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.type-form-content .batch-progress .progress-text {
    font-size: 12px;
    font-weight: 600;
    color: #155724;
    white-space: nowrap;
}

/* Empty state */
.type-form-content .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.type-form-content .empty-state i {
    font-size: 48px;
    opacity: 0.3;
    margin-bottom: 12px;
}

/* Max rows warning */
.type-form-content .max-rows-warning {
    display: none;
    padding: 8px 12px;
    background: #fff3cd;
    border-radius: 6px;
    font-size: 12px;
    color: #856404;
    margin-top: 8px;
}

.type-form-content .max-rows-warning.show {
    display: block;
}

/* Type-specific color variables */
.type-form-content[data-type-color="blue"] { --type-color: #2563eb; --type-color-dark: #1d4ed8; --type-bg: rgba(37,99,235,0.03); --type-gradient: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
.type-form-content[data-type-color="green"] { --type-color: #059669; --type-color-dark: #047857; --type-bg: rgba(5,150,105,0.03); --type-gradient: linear-gradient(135deg, #059669 0%, #047857 100%); }
.type-form-content[data-type-color="yellow"] { --type-color: #d97706; --type-color-dark: #b45309; --type-bg: rgba(217,119,6,0.03); --type-gradient: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); }
.type-form-content[data-type-color="red"] { --type-color: #dc2626; --type-color-dark: #b91c1c; --type-bg: rgba(220,38,38,0.03); --type-gradient: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
.type-form-content[data-type-color="purple"] { --type-color: #7c3aed; --type-color-dark: #6d28d9; --type-bg: rgba(124,58,237,0.03); --type-gradient: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }
.type-form-content[data-type-color="cyan"] { --type-color: #0891b2; --type-color-dark: #0e7490; --type-bg: rgba(8,145,178,0.03); --type-gradient: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
.type-form-content[data-type-color="pink"] { --type-color: #db2777; --type-color-dark: #be185d; --type-bg: rgba(219,39,119,0.03); --type-gradient: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
.type-form-content[data-type-color="gray"] { --type-color: #6b7280; --type-color-dark: #4b5563; --type-bg: rgba(107,114,128,0.03); --type-gradient: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }

/* Tooltip for validation */
.type-form-content .validation-tip {
    font-size: 11px;
    color: #dc3545;
    margin-top: 4px;
    display: none;
}

.type-form-content .validation-tip.show {
    display: block;
}

/* Keyboard shortcut hints */
.type-form-content .shortcut-hint {
    font-size: 11px;
    color: #9ca3af;
    margin-left: 8px;
}

.type-form-content .shortcut-hint kbd {
    padding: 2px 6px;
    background: #f3f4f6;
    border-radius: 4px;
    font-size: 10px;
    font-family: monospace;
}

/* Animation for new rows */
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.type-form-content .batch-row.new {
    animation: slideIn 0.3s ease;
}

/* Drag handle for reordering (future) */
.type-form-content .drag-handle {
    cursor: grab;
    color: #d1d5db;
    padding: 0 4px;
}

.type-form-content .drag-handle:hover {
    color: #9ca3af;
}
</style>
