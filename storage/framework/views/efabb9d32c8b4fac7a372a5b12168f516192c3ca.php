

<?php $__env->startPush('styles_top'); ?>
<style>
    .dictionary-container {
        max-width: 100%;
        margin: 0;
        padding: 0 30px;
    }
    
    .user-stats-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dark-mode .user-stats-card {
        background: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    
    .user-name {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .user-name {
        color: #f1f5f9;
    }
    
    .band-estimate {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
    
    .dark-mode .band-estimate {
        color: #94a3b8;
    }
    
    .search-section {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .search-section {
        background: #1e293b;
    }
    
    .search-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
    }
    
    .dark-mode .search-title {
        color: #f1f5f9;
    }
    
    .search-wrapper {
        display: flex;
        gap: 10px;
    }
    
    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .dark-mode .search-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .search-btn {
        padding: 12px 30px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-btn:hover {
        background: #2563eb;
    }
    
    .word-lists-section {
        margin-bottom: 30px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .section-title {
        color: #f1f5f9;
    }
    
    .toggle-btn {
        padding: 8px 20px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .toggle-btn.active {
        background: #3b82f6;
        color: white;
    }
    
    .dark-mode .toggle-btn {
        background: #0f172a;
        color: #94a3b8;
    }
    
    .word-list-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .word-list-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .dark-mode .word-list-card {
        background: #1e293b;
    }
    
    .word-list-card.locked {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .word-list-card.locked:hover {
        transform: none;
    }
    
    .lock-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #94a3b8;
        font-size: 20px;
    }
    
    .word-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .word-list-name {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .word-list-name {
        color: #f1f5f9;
    }
    
    .word-count-badge {
        padding: 4px 12px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .dark-mode .word-count-badge {
        background: #0c4a6e;
        color: #7dd3fc;
    }
    
    .word-list-description {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }
    
    .dark-mode .word-list-description {
        color: #94a3b8;
    }
    
    .word-list-expanded {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        display: none;
    }
    
    .dark-mode .word-list-expanded {
        background: #0f172a;
    }
    
    .word-list-expanded.show {
        display: block;
    }
    
    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-input {
        flex: 1;
        min-width: 200px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .dark-mode .filter-input {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .filter-btn {
        padding: 10px 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .dark-mode .filter-btn {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .word-item {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        transition: all 0.3s;
    }
    
    .word-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .word-item {
        background: #1e293b;
    }
    
    .word-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .word-content {
        flex: 1;
    }
    
    .word-text {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .dark-mode .word-text {
        color: #f1f5f9;
    }
    
    .word-pronunciation {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
    }
    
    .dark-mode .word-pronunciation {
        color: #94a3b8;
    }
    
    .word-definition {
        font-size: 14px;
        color: #475569;
        margin-top: 5px;
    }
    
    .dark-mode .word-definition {
        color: #cbd5e1;
    }
    
    .learned-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background: #10b981;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .learned-badge.show {
        display: flex;
    }
    
    .learned-badge:hover::after {
        content: 'Đánh dấu đã học';
        position: absolute;
        bottom: 100%;
        right: 0;
        background: #1e293b;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        margin-bottom: 5px;
    }
    
    .action-bar {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .dark-mode .action-bar {
        border-color: #334155;
    }
    
    .action-btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-select-all {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-deselect-all {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .btn-practice {
        background: #3b82f6;
        color: white;
    }
    
    .btn-practice:hover {
        background: #2563eb;
    }
    
    .dark-mode .btn-select-all,
    .dark-mode .btn-deselect-all {
        background: #0f172a;
        color: #cbd5e1;
    }
    
    .dark-mode .btn-delete {
        background: #7f1d1d;
        color: #fca5a5;
    }
    
    .stats-widget {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .dark-mode .stats-widget {
        background: #1e293b;
    }
    
    .widget-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .dark-mode .widget-title {
        color: #f1f5f9;
    }
    
    .streak-circles {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    
    .streak-circle {
        background: #f8fafc;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #94a3b8;
        transition: all 0.3s;
    }
    
    .streak-circle.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .dark-mode .streak-circle {
        background: #0f172a;
        border-color: #334155;
    }
    
    .dark-mode .streak-circle.active {
        background: #3b82f6;
        border-color: #3b82f6;
    }
    
    .ranking-placeholder {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
        font-size: 14px;
        line-height: 1.6;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .dark-mode .ranking-placeholder {
        background: #0f172a;
        color: #64748b;
    }

    .flashcard-preview {
        text-align: center;
    }

    .flashcard-info {
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .dark-mode .flashcard-info {
        background: #0f172a;
    }

    .flashcard-info p {
        font-size: 14px;
        line-height: 1.6;
    }

    .flashcard-info .text-muted {
        color: #64748b !important;
        font-size: 13px;
    }

    .dark-mode .flashcard-info .text-muted {
        color: #94a3b8 !important;
    }
    
    .practice-mode-container {
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .practice-mode-container {
        background: #1e293b;
    }
    
    .score-board {
        display: flex;
        justify-content: space-around;
        margin-bottom: 30px;
    }
    
    .score-item {
        text-align: center;
    }
    
    .score-label {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 5px;
    }
    
    .score-value {
        font-size: 24px;
        font-weight: 700;
    }
    
    .score-value.correct {
        color: #10b981;
    }
    
    .score-value.incorrect {
        color: #ef4444;
    }
    
    .question-text {
        font-size: 18px;
        color: #1e293b;
        text-align: center;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    
    .dark-mode .question-text {
        color: #f1f5f9;
    }
    
    .answers-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .answer-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .answer-card:hover {
        border-color: #3b82f6;
        transform: scale(1.02);
    }
    
    .answer-card.selected {
        border-color: #3b82f6;
        background: #dbeafe;
    }
    
    .answer-card.correct {
        border-color: #10b981;
        background: #d1fae5;
    }
    
    .answer-card.incorrect {
        border-color: #ef4444;
        background: #fee2e2;
    }
    
    .dark-mode .answer-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    .answer-word {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .dark-mode .answer-word {
        color: #f1f5f9;
    }
    
    .answer-pronunciation {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
    }
    
    .dark-mode .answer-pronunciation {
        color: #94a3b8;
    }
    
    .practice-actions {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }
    
    .practice-btn {
        flex: 1;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-exit {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-exit:hover {
        background: #e2e8f0;
    }
    
    .btn-next {
        background: #3b82f6;
        color: white;
    }
    
    .btn-next:hover {
        background: #2563eb;
    }
    
    .btn-next:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }
    
    .dark-mode .btn-exit {
        background: #0f172a;
        color: #cbd5e1;
    }
    
    .hidden {
        display: none !important;
    }

    /* Dictionary Result Styles */
    .dictionary-result-container {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dark-mode .dictionary-result-container {
        background: #1e293b;
    }

    .result-word {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .dark-mode .result-word {
        color: #f1f5f9;
    }

    .result-pronunciation {
        font-size: 18px;
        color: #3b82f6;
        font-style: italic;
        margin-bottom: 20px;
    }

    .dark-mode .result-pronunciation {
        color: #60a5fa;
    }

    .result-pos {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .dark-mode .result-pos {
        background: #0c4a6e;
        color: #7dd3fc;
    }

    .result-definition {
        font-size: 16px;
        color: #475569;
        margin: 10px 0;
        line-height: 1.6;
    }

    .dark-mode .result-definition {
        color: #cbd5e1;
    }

    .result-example {
        font-size: 15px;
        color: #64748b;
        font-style: italic;
        margin: 8px 0;
        padding-left: 15px;
        border-left: 3px solid #e2e8f0;
    }

    .dark-mode .result-example {
        color: #94a3b8;
        border-left-color: #334155;
    }

    .result-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .result-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-btn-primary {
        background: #3b82f6;
        color: white;
    }

    .result-btn-primary:hover {
        background: #2563eb;
    }

    .result-btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .result-btn-secondary:hover {
        background: #e2e8f0;
    }

    .dark-mode .result-btn-secondary {
        background: #0f172a;
        color: #cbd5e1;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .col-lg-8,
        .col-lg-4 {
            margin-bottom: 30px;
        }
        
        .dictionary-container {
            padding: 0 10px;
        }
    }
    
    @media (min-width: 992px) {
        .dictionary-container {
            padding: 0 20px;
        }
        
        /* Add gap between columns */
        .row {
            margin-left: -10px;
            margin-right: -10px;
        }
        
        .col-lg-8,
        .col-lg-4 {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
    
    @media (min-width: 1200px) {
        .dictionary-container {
            padding: 0 40px;
        }
        
        .row {
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .col-lg-8,
        .col-lg-4 {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
    
    @media (min-width: 1400px) {
        .dictionary-container {
            padding: 0 50px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dictionary-container">
    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column: Search, Word Lists (66%) -->
        <div class="col-lg-8">
            <!-- Search Section -->
            <div class="search-section">
                <h3 class="search-title">Search English</h3>
                <div class="search-wrapper">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search the word...">
                    <button class="search-btn" id="searchBtn">Search</button>
                </div>
            </div>

            <!-- Dictionary Result Container (hidden by default) -->
            <div class="dictionary-result-container hidden" id="dictionaryResult">
                <!-- Search results will be displayed here -->
            </div>
            <!-- Dictionary Result Container (hidden by default) -->
            <div class="dictionary-result-container hidden" id="dictionaryResult">
                <!-- Search results will be displayed here -->
            </div>

            <!-- Academic Word Lists Section -->
            <div class="word-lists-section" id="academicWordListsSection">
                <div class="section-header">
                    <h2 class="section-title">Essential IELTS Academic Word List</h2>
                    <button class="toggle-btn active" id="toggleMyWordListBtn">My Word List</button>
                </div>
                
                <?php $__currentLoopData = $academicWordLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wordList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="word-list-card <?php echo e($wordList['is_locked'] ? 'locked' : ''); ?>" 
                     data-list-id="<?php echo e($wordList['id']); ?>"
                     data-list-type="academic">
                    <?php if($wordList['is_locked']): ?>
                        <i class="iconsax lock-icon" data-icon="lock-1"></i>
                    <?php endif; ?>
                    
                    <div class="word-list-header">
                        <h3 class="word-list-name"><?php echo e($wordList['name']); ?> (<?php echo e($wordList['band_level']); ?>)</h3>
                        <span class="word-count-badge">Tổng số từ: <?php echo e($wordList['word_count']); ?> từ</span>
                    </div>
                    <p class="word-list-description"><?php echo e($wordList['description']); ?></p>
                    
                    <!-- Expanded Content (hidden by default) -->
                    <div class="word-list-expanded" id="expanded-<?php echo e($wordList['id']); ?>">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="Search the word...">
                            <button class="filter-btn" data-filter="alphabet">A-Z</button>
                            <button class="filter-btn" data-filter="learned">Đã học</button>
                        </div>
                        
                        <div class="words-container" id="words-<?php echo e($wordList['id']); ?>">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">Select All</button>
                            <button class="action-btn btn-deselect-all">Deselect All</button>
                            <button class="action-btn btn-practice">Practice</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- My Word List Section (hidden by default) -->
            <div class="word-lists-section hidden" id="myWordListSection">
                <div class="section-header">
                    <h2 class="section-title">My Word List</h2>
                    <button class="toggle-btn" id="toggleAcademicListBtn">Academic Word List</button>
                </div>
                
                <div class="word-list-card" data-list-id="<?php echo e($myWordList->id); ?>" data-list-type="my">
                    <div class="word-list-header">
                        <h3 class="word-list-name"><?php echo e($myWordList->name); ?></h3>
                        <span class="word-count-badge">Tổng số từ: <?php echo e($myWordList->word_count); ?> từ</span>
                    </div>
                    <p class="word-list-description"><?php echo e($myWordList->description); ?></p>
                    
                    <!-- Expanded Content -->
                    <div class="word-list-expanded" id="expanded-my-<?php echo e($myWordList->id); ?>">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="Search the word...">
                            <button class="filter-btn" data-filter="alphabet">A-Z</button>
                            <button class="filter-btn" data-filter="learned">Đã học</button>
                        </div>
                        
                        <div class="words-container" id="words-my-<?php echo e($myWordList->id); ?>">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">Select All</button>
                            <button class="action-btn btn-deselect-all">Deselect All</button>
                            <button class="action-btn btn-delete">Delete</button>
                            <button class="action-btn btn-practice">Practice</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practice Mode Container (hidden by default) -->
            <div class="practice-mode-container hidden" id="practiceModeContainer">
                <div class="score-board">
                    <div class="score-item">
                        <div class="score-label">CORRECT: <span class="score-value correct" id="correctCount">0</span></div>
                    </div>
                    <div class="score-item">
                        <div class="score-label">INCORRECT: <span class="score-value incorrect" id="incorrectCount">0</span></div>
                    </div>
                </div>
                
                <div class="question-text" id="questionText"></div>
                
                <div class="answers-grid" id="answersGrid">
                    <!-- Answers will be loaded here -->
                </div>
                
                <div class="practice-actions">
                    <button class="practice-btn btn-exit" id="exitPracticeBtn">Exit</button>
                    <button class="practice-btn btn-next" id="nextQuestionBtn" disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- Right Column: Flashcards, Streak, Ranking (33%) -->
        <div class="col-lg-4">
            <!-- Flashcards Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">FLASHCARDS</h3>
                <div class="flashcard-preview">
                    <div class="flashcard-info">
                        <p class="mb-2">📚 Based on your Band <?php echo e(number_format($userStats['band_estimate'], 1)); ?> level</p>
                        <p class="mb-0 text-muted">Build your vocabulary with smart flashcards</p>
                    </div>
                    <a href="/panel/dictionary/flashcards" class="btn btn-sm btn-primary mt-3 w-100">
                        <i class="iconsax mr-1" data-icon="cards"></i>
                        View All Flashcards
                    </a>
                </div>
            </div>

            <!-- Streak Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">STREAK</h3>
                <div class="streak-circles">
                    <?php for($i = 1; $i <= 7; $i++): ?>
                        <div class="streak-circle <?php echo e($i <= $userStats['streak'] ? 'active' : ''); ?>"><?php echo e($i); ?></div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Ranking Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">RANKING</h3>
                <div class="ranking-placeholder">
                    <p>Your ranking will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
(function($) {
    "use strict";

    let currentWordListId = null;
    let currentWordListType = null;
    let currentQuestions = [];
    let currentQuestionIndex = 0;
    let correctAnswers = 0;
    let incorrectAnswers = 0;
    let selectedAnswer = null;

    // Search functionality
    let isSearching = false; // Prevent duplicate searches

    $('#searchBtn').on('click', function(e) {
        e.preventDefault();
        performSearch();
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            performSearch();
        }
    });

    function performSearch() {
        // Prevent duplicate calls
        if (isSearching) {
            return;
        }

        const searchTerm = $('#searchInput').val().trim();
        
        if (searchTerm === '') {
            alert('Please enter a word to search');
            return;
        }

        isSearching = true;

        $.ajax({
            url: '/panel/dictionary/search-first',
            method: 'POST',
            data: {
                query: searchTerm,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#searchBtn').prop('disabled', true).text('Searching...');
            },
            success: function(response) {
                if (response.success && response.data) {
                    displaySearchResult(response.data);
                    $('#dictionaryResult').removeClass('hidden');
                } else {
                    alert('No results found for: ' + searchTerm);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', xhr.responseText);
                
                let errorMessage = 'An error occurred while searching';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false).text('Search');
                isSearching = false;
            }
        });
    }

    function displaySearchResult(data) {
        console.log('Dictionary data:', data); // Debug log
        
        let html = `
            <div class="result-word">${data.headword || data.word || 'Unknown'}</div>
        `;

        // Handle pronunciations
        if (data.pronunciations && data.pronunciations.length > 0) {
            const pronText = data.pronunciations[0].ipa || data.pronunciations[0].text || '';
            if (pronText) {
                html += `<div class="result-pronunciation">/${pronText}/</div>`;
            }
        }

        // Handle meanings (new API structure)
        if (data.meanings && data.meanings.length > 0) {
            data.meanings.forEach(function(meaning) {
                if (meaning.partOfSpeech) {
                    html += `<div class="result-pos">${meaning.partOfSpeech}</div>`;
                }

                if (meaning.definitions && meaning.definitions.length > 0) {
                    meaning.definitions.forEach(function(defObj) {
                        if (defObj.definition) {
                            html += `<div class="result-definition">${defObj.definition}</div>`;
                        }
                        
                        if (defObj.example) {
                            html += `<div class="result-example">"${defObj.example}"</div>`;
                        }
                    });
                }
            });
        }
        // Fallback to old senses structure
        else if (data.senses && data.senses.length > 0) {
            data.senses.forEach(function(sense) {
                if (sense.part_of_speech) {
                    html += `<div class="result-pos">${sense.part_of_speech}</div>`;
                }

                if (sense.definitions && sense.definitions.length > 0) {
                    sense.definitions.forEach(function(definition) {
                        html += `<div class="result-definition">${definition}</div>`;
                    });
                }

                if (sense.examples && sense.examples.length > 0) {
                    sense.examples.forEach(function(example) {
                        html += `<div class="result-example">"${example}"</div>`;
                    });
                }
            });
        }

        html += `
            <div class="result-actions">
                <button class="result-btn result-btn-primary" id="saveToFlashcard" data-word="${data.headword || data.word}">
                    <i class="iconsax" data-icon="bookmark"></i>
                    Save to Flashcard
                </button>
                <button class="result-btn result-btn-secondary" id="addToWordList" data-word="${data.headword || data.word}">
                    <i class="iconsax" data-icon="add-circle"></i>
                    Add to Word List
                </button>
            </div>
        `;

        $('#dictionaryResult').html(html);
    }

    // Save to flashcard
    $(document).on('click', '#saveToFlashcard', function() {
        const word = $('.result-word').text();
        // Implement save to flashcard logic
        alert('Saving "' + word + '" to flashcard...');
    });

    // Add to word list
    $(document).on('click', '#addToWordList', function() {
        const word = $('.result-word').text();
        // Implement add to word list logic
        alert('Adding "' + word + '" to word list...');
    });

    // Toggle between Academic and My Word List
    $('#toggleMyWordListBtn').on('click', function() {
        $('#academicWordListsSection').addClass('hidden');
        $('#myWordListSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleAcademicListBtn').addClass('active');
    });

    $('#toggleAcademicListBtn').on('click', function() {
        $('#myWordListSection').addClass('hidden');
        $('#academicWordListsSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleMyWordListBtn').addClass('active');
    });

    // Click on word list card to expand/collapse
    $('.word-list-card').on('click', function(e) {
        if ($(this).hasClass('locked')) {
            alert('This word list is locked. Please purchase the corresponding IELTS course to unlock it.');
            return;
        }

        let listId = $(this).data('list-id');
        let listType = $(this).data('list-type');
        let expandedSection = $(this).find('.word-list-expanded');

        if (expandedSection.hasClass('show')) {
            expandedSection.removeClass('show');
        } else {
            // Collapse other expanded sections
            $('.word-list-expanded').removeClass('show');
            expandedSection.addClass('show');
            
            // Load words if not already loaded
            if (listType === 'academic') {
                loadAcademicWordList(listId);
            } else {
                loadMyWordList();
            }
        }
    });

    // Load Academic Word List
    function loadAcademicWordList(listId) {
        $.ajax({
            url: '/panel/dictionary/academic-word-lists/' + listId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWords(response.data.words, listId, 'academic');
                }
            },
            error: function(error) {
                console.error('Error loading word list:', error);
            }
        });
    }

    // Load My Word List
    function loadMyWordList() {
        $.ajax({
            url: '/panel/dictionary/my-word-list',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWords(response.data.flashcards, response.data.id, 'my');
                }
            },
            error: function(error) {
                console.error('Error loading my word list:', error);
            }
        });
    }

    // Render words in the list
    function renderWords(words, listId, listType) {
        let container = listType === 'academic' ? 
            $('#words-' + listId) : 
            $('#words-my-' + listId);
        
        container.empty();

        words.forEach(function(word) {
            let wordHtml = `
                <div class="word-item" data-word-id="${word.id}" data-word="${word.word}">
                    <input type="checkbox" class="word-checkbox" data-word-id="${word.id}">
                    <div class="word-content">
                        <div class="word-text">${word.word}</div>
                        ${word.pronunciation ? `<div class="word-pronunciation">${word.pronunciation}</div>` : ''}
                        <div class="word-definition">${word.definition}</div>
                    </div>
                    <div class="learned-badge ${word.is_learned ? 'show' : ''}" 
                         data-word-id="${word.id}" 
                         title="Đánh dấu đã học">
                        <i class="iconsax" data-icon="tick-circle"></i>
                    </div>
                </div>
            `;
            container.append(wordHtml);
        });

        currentWordListId = listId;
        currentWordListType = listType;
    }

    // Mark word as learned
    $(document).on('click', '.learned-badge', function(e) {
        e.stopPropagation();
        
        let wordId = $(this).data('word-id');
        
        $.ajax({
            url: '/panel/dictionary/academic-word-lists/mark-learned',
            method: 'POST',
            data: {
                word_id: wordId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`.learned-badge[data-word-id="${wordId}"]`).addClass('show');
                }
            }
        });
    });

    // Select All
    $(document).on('click', '.btn-select-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', true);
    });

    // Deselect All
    $(document).on('click', '.btn-deselect-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', false);
    });

    // Delete words from My Word List
    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        
        let selectedIds = [];
        $('.word-checkbox:checked').each(function() {
            selectedIds.push($(this).data('word-id'));
        });

        if (selectedIds.length === 0) {
            alert('Please select words to delete');
            return;
        }

        if (!confirm('Are you sure you want to delete these words?')) {
            return;
        }

        // TODO: Implement bulk delete API
        console.log('Deleting words:', selectedIds);
    });

    // Start Practice
    $(document).on('click', '.btn-practice', function(e) {
        e.stopPropagation();
        
        let selectedIds = [];
        $('.word-checkbox:checked').each(function() {
            selectedIds.push($(this).data('word-id'));
        });

        if (selectedIds.length === 0) {
            alert('Please select words to practice');
            return;
        }

        startPractice(selectedIds);
    });

    // Start Practice Session
    function startPractice(wordIds) {
        let url = currentWordListType === 'academic' ? 
            '/panel/dictionary/practice/start' : 
            '/panel/dictionary/practice/start-my-word-list';
        
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        if (currentWordListType === 'academic') {
            data.word_list_id = currentWordListId;
            data.word_ids = wordIds;
        } else {
            data.flashcard_ids = wordIds;
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    currentQuestions = response.data.questions;
                    currentQuestionIndex = 0;
                    correctAnswers = 0;
                    incorrectAnswers = 0;
                    
                    // Hide word lists and show practice mode
                    $('#academicWordListsSection, #myWordListSection').addClass('hidden');
                    $('#practiceModeContainer').removeClass('hidden');
                    
                    showQuestion();
                }
            },
            error: function(error) {
                console.error('Error starting practice:', error);
                alert('Failed to start practice session');
            }
        });
    }

    // Show Question
    function showQuestion() {
        if (currentQuestionIndex >= currentQuestions.length) {
            // Practice finished
            exitPractice();
            return;
        }

        let question = currentQuestions[currentQuestionIndex];
        selectedAnswer = null;
        
        $('#questionText').text(question.question);
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', true);
        
        let answersHtml = '';
        question.answers.forEach(function(answer) {
            answersHtml += `
                <div class="answer-card" data-answer="${answer}">
                    <div class="answer-word">${answer}</div>
                    <div class="answer-pronunciation">${question.correct_answer === answer ? '' : ''}</div>
                </div>
            `;
        });
        
        $('#answersGrid').html(answersHtml);
    }

    // Select Answer
    $(document).on('click', '.answer-card', function() {
        if (selectedAnswer !== null) return; // Already answered
        
        selectedAnswer = $(this).data('answer');
        let question = currentQuestions[currentQuestionIndex];
        let isCorrect = selectedAnswer === question.correct_answer;
        
        $(this).addClass(isCorrect ? 'correct' : 'incorrect');
        
        // Show correct answer if wrong
        if (!isCorrect) {
            $(`.answer-card[data-answer="${question.correct_answer}"]`).addClass('correct');
        }
        
        // Update scores
        if (isCorrect) {
            correctAnswers++;
        } else {
            incorrectAnswers++;
        }
        
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', false);
        
        // Submit answer to server
        let wordKey = currentWordListType === 'academic' ? 'word_id' : 'flashcard_id';
        let submitData = {
            [wordKey]: question[wordKey],
            selected_answer: selectedAnswer,
            correct_answer: question.correct_answer,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '/panel/dictionary/practice/submit-answer',
            method: 'POST',
            data: submitData
        });
    });

    // Next Question
    $('#nextQuestionBtn').on('click', function() {
        currentQuestionIndex++;
        showQuestion();
    });

    // Exit Practice
    $('#exitPracticeBtn').on('click', function() {
        exitPractice();
    });

    function exitPractice() {
        $('#practiceModeContainer').addClass('hidden');
        
        if (currentWordListType === 'academic') {
            $('#academicWordListsSection').removeClass('hidden');
        } else {
            $('#myWordListSection').removeClass('hidden');
        }
        
        // Reload the word list to show updated learned status
        if (currentWordListType === 'academic') {
            loadAcademicWordList(currentWordListId);
        } else {
            loadMyWordList();
        }
    }

    // Search functionality
    $('#searchBtn').on('click', function() {
        let searchTerm = $('#searchInput').val().trim();
        if (searchTerm) {
            // Implement dictionary search (existing functionality)
            console.log('Searching for:', searchTerm);
        }
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });

    // Filter functionality
    $(document).on('click', '.filter-btn', function(e) {
        e.stopPropagation();
        
        let filter = $(this).data('filter');
        let container = $(this).closest('.word-list-expanded').find('.words-container');
        let words = container.find('.word-item');
        
        if (filter === 'alphabet') {
            // Sort alphabetically
            words.sort(function(a, b) {
                let aText = $(a).find('.word-text').text();
                let bText = $(b).find('.word-text').text();
                return aText.localeCompare(bText);
            });
            container.html(words);
        } else if (filter === 'learned') {
            // Show only learned words
            words.each(function() {
                if ($(this).find('.learned-badge').hasClass('show')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        $(this).toggleClass('active');
    });

    // Search within word list
    $(document).on('keyup', '.filter-input', function(e) {
        e.stopPropagation();
        
        let searchTerm = $(this).val().toLowerCase();
        let container = $(this).closest('.word-list-expanded').find('.words-container');
        
        container.find('.word-item').each(function() {
            let word = $(this).find('.word-text').text().toLowerCase();
            if (word.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

})(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dictionary/index_new.blade.php ENDPATH**/ ?>