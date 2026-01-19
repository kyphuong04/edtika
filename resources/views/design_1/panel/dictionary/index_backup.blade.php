@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .dictionary-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }
    
    .dark-mode .dictionary-card {
        background: #1f2937;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    
    .search-input-wrapper {
        position: relative;
        margin-bottom: 24px;
    }
    
    .search-input {
        width: 100%;
        padding: 14px 56px 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.2s ease;
        background: white;
        color: #111827;
    }
    
    .dark-mode .search-input {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    .dark-mode .search-input::placeholder {
        color: #9ca3af;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
    }
    
    .dark-mode .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.2);
    }
    
    .search-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary);
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .search-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-50%) scale(1.02);
    }
    
    .result-card {
        background: white;
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        display: none;
    }
    
    .dark-mode .result-card {
        background: #1f2937;
        border-color: #374151;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    .result-card.show {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .word-header {
        padding-bottom: 20px;
        margin-bottom: 24px;
    }
    
    .dark-mode .word-header {
        border-color: #374151;
    }
    
    .word-title {
        font-size: 36px;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: #111827;
    }
    
    .dark-mode .word-title {
        color: #f9fafb;
    }
    
    .word-pronunciation {
        font-size: 16px;
        color: #6b7280;
        margin: 0 0 20px 0;
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .dark-mode .word-pronunciation {
        color: #9ca3af;
    }
    
    .pronunciation-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .pronunciation-label {
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }
    
    .dark-mode .pronunciation-label {
        color: #f9fafb;
    }
    
    .pronunciation-ipa {
        font-style: italic;
        color: #6b7280;
        margin-right: 4px;
    }
    
    .dark-mode .pronunciation-ipa {
        color: #9ca3af;
    }
    
    .pronunciation-audio {
        background: none;
        border: none;
        color: #3b82f6;
        cursor: pointer;
        padding: 4px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .dark-mode .pronunciation-audio {
        color: #60a5fa;
    }
    
    .pronunciation-audio:hover {
        color: #2563eb;
        transform: scale(1.1);
    }
    
    .dark-mode .pronunciation-audio:hover {
        color: #93c5fd;
    }
    
    .word-type-badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0369a1;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    
    .dark-mode .word-type-badge {
        background: #1e3a5f;
        color: #93c5fd;
    }
    
    .definition-section {
        margin-bottom: 32px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .dark-mode .definition-section {
        background: #1f2937;
    }
    
    .definition-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #111827;
        text-transform: capitalize;
    }
    
    .dark-mode .definition-title {
        color: #f9fafb;
    }
    
    .definition-item {
        margin-bottom: 16px;
        padding-left: 0;
        position: relative;
    }
    
    .dark-mode .definition-item {
        border-color: #4b5563;
    }
    
    .definition-text {
        font-size: 15px;
        line-height: 1.8;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .dark-mode .definition-text {
        color: #d1d5db;
    }
    
    .example-text {
        color: #6b7280;
        font-style: italic;
        padding-left: 16px;
        border-left: 3px solid #d1d5db;
        margin: 8px 0;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .dark-mode .example-text {
        color: #9ca3af;
        border-left-color: #4b5563;
    }
    
    .save-flashcard-btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        background: var(--primary);
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        margin-right: 10px;
    }
    
    .save-flashcard-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(var(--primary-rgb), 0.2);
    }
    
    .add-to-list-btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        background: var(--primary);
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }
    
    .add-to-list-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(var(--primary-rgb), 0.2);
    }
    
    .action-buttons {
        margin: 20px 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .btn-group {
        display: inline-block;
        position: relative;
    }
    
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        margin-top: 8px;
    }
    
    .dark-mode .dropdown-menu {
        background: #374151;
        border-color: #4b5563;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .dropdown-item {
        padding: 10px 16px;
        color: #374151;
        transition: all 0.2s ease;
    }
    
    .dark-mode .dropdown-item {
        color: #d1d5db;
    }
    
    .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .dark-mode .dropdown-item:hover {
        background: #4b5563;
    }
    
    .suggestions-list {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-top: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .dark-mode .suggestions-list {
        background: #1f2937;
        border-color: #4b5563;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .suggestion-item {
        padding: 12px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
        font-size: 15px;
        color: #333;
    }
    
    .dark-mode .suggestion-item {
        color: #d1d5db;
    }
    
    .suggestion-item:hover {
        background: #f5f5f5;
        padding-left: 16px;
    }
    
    .dark-mode .suggestion-item:hover {
        background: #374151;
    }
    
    .loading {
        text-align: center;
        padding: 60px;
        display: none;
    }
    
    .loading-spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px;
    }
    
    .dark-mode .loading-spinner {
        border-color: #374151;
        border-top-color: var(--primary);
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .error-message {
        background: #fee2e2;
        color: #991b1b;
        padding: 16px 20px;
        border-radius: 8px;
        margin-top: 16px;
        border-left: 4px solid #dc2626;
        display: none;
    }
    
    .dark-mode .error-message {
        background: #7f1d1d;
        color: #fecaca;
        border-left-color: #dc2626;
    }
    
    .nearby-words {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }
    
    .nearby-word-badge {
        background: #f5f5f5;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: #333;
    }
    
    .dark-mode .nearby-word-badge {
        background: #374151;
        color: #d1d5db;
    }
    
    .nearby-word-badge:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .dark-mode .nearby-word-badge:hover {
        background: var(--primary);
        color: white;
    }
    
    /* Dark mode styles for API content */
    .dark-mode #definitionsContainer {
        color: #d1d5db;
    }
    
    .dark-mode #definitionsContainer h3,
    .dark-mode #definitionsContainer h4,
    .dark-mode #definitionsContainer h5,
    .dark-mode #definitionsContainer strong,
    .dark-mode #definitionsContainer b {
        color: #f3f4f6;
    }
    
    .dark-mode #definitionsContainer .definition-body,
    .dark-mode #definitionsContainer .def,
    .dark-mode #definitionsContainer .ddef_d {
        color: #d1d5db;
    }
    
    .dark-mode #definitionsContainer .def-info,
    .dark-mode #definitionsContainer .dgram,
    .dark-mode #definitionsContainer .dpos {
        color: #9ca3af;
    }
    
    .dark-mode #definitionsContainer .examp,
    .dark-mode #definitionsContainer .eg {
        color: #9ca3af;
        border-left-color: var(--primary);
    }
    
    /* Button spacing */
    .gap-2 {
        gap: 0.5rem;
    }
    
    .gap-2 > * + * {
        margin-left: 0.5rem;
    }
    
    /* Streak badges */
    .streak-day-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #e0e0e0;
        font-weight: 600;
        font-size: 14px;
        color: #666;
        background: white;
    }
    
    .dark-mode .streak-day-badge {
        background: #374151;
        border-color: #4b5563;
        color: #9ca3af;
    }
    
    .streak-day-badge.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    /* Word list item */
    .word-list-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dark-mode .word-list-item {
        background: #374151;
        border-color: #4b5563;
    }
    
    .word-list-item:hover {
        border-color: var(--primary);
        transform: translateX(4px);
    }
    
    .word-list-item-title {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
    }
    
    .dark-mode .word-list-item-title {
        color: #f3f4f6;
    }
    
    .word-list-item-count {
        font-size: 12px;
        color: #666;
    }
    
    .dark-mode .word-list-item-count {
        color: #9ca3af;
    }
    
    /* Mini flashcard preview */
    .mini-flashcard {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .dark-mode .mini-flashcard {
        background: #374151;
        border-color: #4b5563;
    }
    
    .mini-flashcard:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .mini-flashcard-word {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .dark-mode .mini-flashcard-word {
        color: #f3f4f6;
    }
    
    .mini-flashcard-pronunciation {
        font-size: 12px;
        color: #666;
        font-family: 'Courier New', monospace;
    }
    
    .dark-mode .mini-flashcard-pronunciation {
        color: #9ca3af;
    }
    
    /* Empty state styling */
    .empty-state-icon {
        color: #999;
    }
    
    .dark-mode .empty-state-icon {
        color: #6b7280;
    }
    
    .empty-state-text {
        color: #666;
    }
    
    .dark-mode .empty-state-text {
        color: #9ca3af;
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .word-title {
            font-size: 28px;
        }
        
        .word-pronunciation {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-buttons button,
        .action-buttons .btn-group {
            width: 100%;
        }
        
        .save-flashcard-btn,
        .add-to-list-btn {
            justify-content: center;
        }
        
        .result-card {
            padding: 20px;
        }
        
        .definition-section {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mt-20">
        <!-- Left Column: Search + Word Lists -->
        <div class="col-12 col-lg-7">
            @if(!$hasApiKey)
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading d-flex align-items-center">
                        <x-iconsax-bul-info-circle class="mr-2" width="24px" height="24px"/>
                        Cambridge Dictionary API Key Required
                    </h4>
                    <p>{{ trans('panel.dictionary_api_key_required') }}</p>
                    <hr>
                    <p class="mb-0">
                        <strong>{{ trans('panel.steps_to_configure') }}:</strong><br>
                        1. Visit <a href="https://dictionary.cambridge.org/api/" target="_blank">Cambridge Dictionary API</a><br>
                        2. Add <code>CAMBRIDGE_DICT_ACCESS_KEY=your_api_key_here</code> to <code>.env</code> file<br>
                        3. Run <code>php artisan config:clear</code><br>
                        4. {{ trans('panel.reload_page') }}
                    </p>
                </div>
            @endif

            <!-- Search Box -->
            <div class="dictionary-card">
                <h4 class="font-16 font-weight-bold mb-3">{{ trans('panel.search_word') }}</h4>
                <div class="search-input-wrapper">
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="search-input" 
                        placeholder="{{ trans('panel.enter_word_to_search') }}"
                        autocomplete="off"
                    >
                    <button class="search-btn" id="searchBtn">
                        <x-iconsax-bul-search-normal class="icons" width="20px" height="20px"/>
                    </button>
                </div>
                <div id="suggestionsContainer"></div>
            </div>

            <!-- Loading -->
            <div id="loadingContainer" class="loading" style="display: none;">
                <div class="loading-spinner"></div>
                <p class="text-gray-500">{{ trans('panel.searching') }}...</p>
            </div>

            <!-- Error Message -->
            <div id="errorContainer" class="error-message" style="display: none;"></div>

            <!-- Result Container -->
            <div id="resultContainer" class="result-card">
                <div class="word-header">
                    <h2 class="word-title" id="wordTitle"></h2>
                    <div class="word-pronunciation" id="wordPronunciation">
                        <!-- Pronunciations will be inserted here -->
                    </div>
                    
                    <div class="action-buttons">
                        <button class="save-flashcard-btn" id="saveFlashcardBtn">
                            <x-iconsax-lin-save-2 class="mr-1" width="18px" height="18px"/>
                            Save to Flashcard
                        </button>
                        
                        <div class="btn-group">
                            <button type="button" class="add-to-list-btn dropdown-toggle" id="addToListBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <x-iconsax-lin-add-circle class="mr-1" width="18px" height="18px"/>
                                Add to List
                            </button>
                            <div class="dropdown-menu" id="wordListsDropdown">
                                <div class="dropdown-item text-center">
                                    <small class="text-gray">{{ trans('panel.loading') }}...</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="wordTypes" style="margin-top: 10px;"></div>
                </div>

                <div id="definitionsContainer"></div>

                <div id="nearbyWordsContainer" style="margin-top: 32px;"></div>
            </div>

            <!-- Word Lists Section -->
            <div class="mt-30">
                <h4 class="font-16 font-weight-bold mb-3">{{ trans('panel.word_lists') }}</h4>
                
                <!-- My Word Lists -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="font-14 font-weight-bold mb-0">{{ trans('panel.my_word_list') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createWordListModal">
                            <x-iconsax-lin-add-circle class="mr-1" width="14px" height="14px"/>
                            {{ trans('public.create') }}
                        </button>
                    </div>
                    
                    <div id="myWordListsContainer">
                        <div class="text-center py-3 text-gray">
                            <small>{{ trans('panel.loading') }}...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Flashcards + Stats -->
        <div class="col-12 col-lg-5 mt-20 mt-lg-0">
            <!-- Flashcards Section -->
            <div class="dictionary-card mb-4">
                <h4 class="font-16 font-weight-bold mb-3">{{ trans('panel.my_flashcards') }}</h4>
                
                <div id="flashcardsPreview">
                    <div class="text-center py-4">
                        <x-iconsax-bul-bookmark-2 width="48px" height="48px" class="mb-2 empty-state-icon"/>
                        <p class="mb-0 empty-state-text">{{ trans('panel.no_flashcards_yet') }}</p>
                    </div>
                </div>
            </div>

            <!-- Streak Section -->
            <div class="dictionary-card mb-4">
                <h4 class="font-16 font-weight-bold mb-3">STREAK</h4>
                
                <div class="d-flex justify-content-center gap-2 py-3">
                    <span class="streak-day-badge">1</span>
                    <span class="streak-day-badge">2</span>
                    <span class="streak-day-badge">3</span>
                    <span class="streak-day-badge">4</span>
                    <span class="streak-day-badge">5</span>
                    <span class="streak-day-badge">6</span>
                    <span class="streak-day-badge">7</span>
                </div>
            </div>

            <!-- Ranking Section -->
            <div class="dictionary-card">
                <h4 class="font-16 font-weight-bold mb-3">RANKING</h4>
                
                <div class="text-center py-4">
                    <p class="mb-0 empty-state-text">Coming Soon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const resultContainer = document.getElementById('resultContainer');
    const loadingContainer = document.getElementById('loadingContainer');
    const errorContainer = document.getElementById('errorContainer');
    const suggestionsContainer = document.getElementById('suggestionsContainer');
    const saveFlashcardBtn = document.getElementById('saveFlashcardBtn');
    
    let searchTimeout;
    let currentWordData = null;

    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Search on button click
    searchBtn.addEventListener('click', performSearch);

    // Auto-suggest on typing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                getSuggestions(query);
            }, 300);
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });

    // Save to flashcard
    saveFlashcardBtn.addEventListener('click', function() {
        if (!currentWordData) return;
        
        // Get pronunciations text
        let pronunciationText = '';
        if (currentWordData.pronunciations && currentWordData.pronunciations.length > 0) {
            pronunciationText = currentWordData.pronunciations
                .map(p => `${p.label ? p.label + ' ' : ''}${p.ipa}`)
                .join(' / ');
        }
        
        const formData = {
            word: currentWordData.headword || searchInput.value,
            pronunciation: pronunciationText,
            definition: document.getElementById('definitionsContainer').innerText.substring(0, 500),
            example: '',
            translation: ''
        };

        fetch('/panel/dictionary/flashcards/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ trans("panel.success") }}',
                    text: '{{ trans("panel.flashcard_saved_successfully") }}',
                    timer: 2000
                });
                
                // Store the flashcard ID for add to list functionality
                currentFlashcardId = data.data.id;
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ trans("panel.error") }}',
                text: '{{ trans("panel.something_went_wrong") }}'
            });
        });
    });

    // Load word lists for dropdown
    let wordLists = [];
    let currentFlashcardId = null;
    
    function loadWordLists() {
        fetch('/panel/dictionary/word-lists-dropdown')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    wordLists = data.data;
                    updateWordListsDropdown();
                    displayWordListsSidebar(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading word lists:', error);
            });
    }
    
    function displayWordListsSidebar(lists) {
        const container = document.getElementById('myWordListsContainer');
        
        if (lists.length === 0) {
            container.innerHTML = `
                <div class="text-center py-3 text-gray">
                    <small>{{ trans('panel.no_word_lists_yet') }}</small>
                </div>
            `;
            return;
        }
        
        let html = '';
        lists.slice(0, 5).forEach(list => {
            html += `
                <div class="word-list-item" onclick="window.location.href='/panel/dictionary/word-lists/${list.id}'">
                    <div>
                        <div class="word-list-item-title">${list.name}</div>
                        <div class="word-list-item-count">${list.word_count} {{ trans('panel.words') }}</div>
                    </div>
                    <i class="iconsax" data-icon="arrow-right-3"></i>
                </div>
            `;
        });
        
        if (lists.length > 5) {
            html += `
                <a href="/panel/dictionary/word-lists" class="btn btn-sm btn-outline-primary w-100 mt-2">
                    {{ trans('panel.view_all') }}
                </a>
            `;
        }
        
        container.innerHTML = html;
    }
    
    // Load flashcards preview
    function loadFlashcardsPreview() {
        fetch('/panel/dictionary/flashcards-preview')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    displayFlashcardsPreview(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading flashcards:', error);
            });
    }
    
    function displayFlashcardsPreview(flashcards) {
        const container = document.getElementById('flashcardsPreview');
        
        let html = '';
        flashcards.slice(0, 4).forEach(card => {
            html += `
                <div class="mini-flashcard">
                    <div class="mini-flashcard-word">${card.word}</div>
                    ${card.pronunciation ? `<div class="mini-flashcard-pronunciation">${card.pronunciation}</div>` : ''}
                </div>
            `;
        });
        
        html += `
            <a href="/panel/dictionary/flashcards" class="btn btn-sm btn-outline-primary w-100 mt-2">
                {{ trans('panel.view_all_flashcards') }}
            </a>
        `;
        
        container.innerHTML = html;
    }
    
    function updateWordListsDropdown() {
        const dropdown = document.getElementById('wordListsDropdown');
        
        if (wordLists.length === 0) {
            dropdown.innerHTML = `
                <div class="dropdown-item text-center">
                    <small class="text-gray">{{ trans('panel.no_word_lists_yet') }}</small>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/panel/dictionary/word-lists">
                    <i class="iconsax" data-icon="add-circle"></i>
                    {{ trans('panel.create_word_list') }}
                </a>
            `;
        } else {
            let html = '';
            wordLists.forEach(list => {
                html += `
                    <button class="dropdown-item add-to-word-list" data-list-id="${list.id}">
                        ${list.name} (${list.word_count} words)
                    </button>
                `;
            });
            html += `
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/panel/dictionary/word-lists">
                    <i class="iconsax" data-icon="add-circle"></i>
                    {{ trans('panel.create_word_list') }}
                </a>
            `;
            dropdown.innerHTML = html;
            
            // Add event listeners to add-to-list buttons
            document.querySelectorAll('.add-to-word-list').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addWordToList(this.dataset.listId);
                });
            });
        }
    }
    
    function addWordToList(wordListId) {
        // First save flashcard if not already saved
        if (!currentFlashcardId) {
            // Get pronunciations text
            let pronunciationText = '';
            if (currentWordData.pronunciations && currentWordData.pronunciations.length > 0) {
                pronunciationText = currentWordData.pronunciations
                    .map(p => `${p.label ? p.label + ' ' : ''}${p.ipa}`)
                    .join(' / ');
            }
            
            const formData = {
                word: currentWordData.headword || searchInput.value,
                pronunciation: pronunciationText,
                definition: document.getElementById('definitionsContainer').innerText.substring(0, 500),
                example: '',
                translation: ''
            };

            fetch('/panel/dictionary/flashcards/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentFlashcardId = data.data.id;
                    proceedAddToList(wordListId);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ trans("panel.error") }}',
                    text: '{{ trans("panel.something_went_wrong") }}'
                });
            });
        } else {
            proceedAddToList(wordListId);
        }
    }
    
    function proceedAddToList(wordListId) {
        fetch('/panel/dictionary/word-lists/add-word', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                word_list_id: wordListId,
                flashcard_id: currentFlashcardId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ trans("panel.success") }}',
                    text: data.message,
                    timer: 2000
                });
                loadWordLists(); // Reload to update word counts
            } else {
                Swal.fire({
                    icon: 'info',
                    title: '{{ trans("panel.error") }}',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ trans("panel.error") }}',
                text: '{{ trans("panel.something_went_wrong") }}'
            });
        });
    }
    
    // Load word lists on page load
    loadWordLists();
    loadFlashcardsPreview();

    function performSearch() {
        const query = searchInput.value.trim();
        
        if (!query) {
            showError('{{ trans("panel.please_enter_word") }}');
            return;
        }

        hideError();
        resultContainer.classList.remove('show');
        loadingContainer.style.display = 'block';
        suggestionsContainer.innerHTML = '';

        fetch(`/panel/dictionary/search-first?q=${encodeURIComponent(query)}&dictionary=english-vietnamese&format=html`)
            .then(response => response.json())
            .then(data => {
                loadingContainer.style.display = 'none';
                
                if (data.success && data.data) {
                    currentWordData = data.data;
                    displayResult(data.data);
                } else {
                    showError('{{ trans("panel.word_not_found") }}');
                    getSuggestions(query);
                }
            })
            .catch(error => {
                loadingContainer.style.display = 'none';
                showError('{{ trans("panel.search_error") }}: ' + error.message);
            });
    }

    function getSuggestions(query) {
        fetch(`/panel/dictionary/did-you-mean?q=${encodeURIComponent(query)}&dictionary=english-vietnamese&max=5`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    displaySuggestions(data.data);
                }
            })
            .catch(error => {
                console.error('Suggestions error:', error);
            });
    }

    function displaySuggestions(suggestions) {
        let html = '<div class="suggestions-list">';
        html += '<strong class="d-block mb-2">{{ trans("panel.did_you_mean") }}:</strong>';
        
        suggestions.forEach(suggestion => {
            html += `<div class="suggestion-item" onclick="selectSuggestion('${suggestion}')">${suggestion}</div>`;
        });
        
        html += '</div>';
        suggestionsContainer.innerHTML = html;
    }

    function selectSuggestion(word) {
        searchInput.value = word;
        performSearch();
    }

    function displayResult(data) {
        // Display word title
        document.getElementById('wordTitle').textContent = data.headword || searchInput.value;
        
        // Display pronunciations (UK and US)
        const pronunciationContainer = document.getElementById('wordPronunciation');
        pronunciationContainer.innerHTML = '';
        
        if (data.pronunciations && data.pronunciations.length > 0) {
            data.pronunciations.forEach(pron => {
                if (pron.ipa) {
                    const pronItem = document.createElement('div');
                    pronItem.className = 'pronunciation-item';
                    
                    // Label (UK or US)
                    const label = document.createElement('span');
                    label.className = 'pronunciation-label';
                    label.textContent = pron.label || '';
                    
                    // IPA
                    const ipa = document.createElement('span');
                    ipa.className = 'pronunciation-ipa';
                    ipa.textContent = pron.ipa;
                    
                    pronItem.appendChild(label);
                    pronItem.appendChild(ipa);
                    
                    // Audio button
                    if (pron.audio) {
                        const audioBtn = document.createElement('button');
                        audioBtn.className = 'pronunciation-audio';
                        audioBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"></path><path d="M15.54 8.46a5 5 0 010 7.07"></path><path d="M19.07 4.93a10 10 0 010 14.14"></path></svg>';
                        audioBtn.onclick = () => {
                            const audio = new Audio(pron.audio);
                            audio.play();
                        };
                        pronItem.appendChild(audioBtn);
                    }
                    
                    pronunciationContainer.appendChild(pronItem);
                }
            });
        }

        // Display definitions (HTML format from API)
        if (data.htmlContent) {
            document.getElementById('definitionsContainer').innerHTML = data.htmlContent;
        } else if (data.meanings && data.meanings.length > 0) {
            // Fallback: generate HTML from meanings if htmlContent is not available
            let html = '';
            data.meanings.forEach(meaning => {
                html += '<div class="definition-section">';
                html += `<h3 class="definition-title">${meaning.partOfSpeech}</h3>`;
                
                if (meaning.definitions && meaning.definitions.length > 0) {
                    meaning.definitions.forEach((def, index) => {
                        html += '<div class="definition-item">';
                        html += `<div class="definition-text"><strong>${index + 1}.</strong> ${def.definition}</div>`;
                        if (def.example) {
                            html += `<div class="example-text">"${def.example}"</div>`;
                        }
                        html += '</div>';
                    });
                }
                
                html += '</div>';
            });
            document.getElementById('definitionsContainer').innerHTML = html;
        }

        resultContainer.classList.add('show');

        // No longer loading nearby entries since Free Dictionary API doesn't support it
    }

    function loadNearbyEntries(entryId) {
        fetch(`/panel/dictionary/nearby-entries?entryId=${encodeURIComponent(entryId)}&dictionary=english-vietnamese&max=5`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    let html = '<div class="definition-section">';
                    html += '<div class="definition-title">{{ trans("panel.related_words") }}</div>';
                    html += '<div class="nearby-words">';
                    
                    data.data.forEach(entry => {
                        html += `<span class="nearby-word-badge" onclick="searchWord('${entry.headword}')">${entry.headword}</span>`;
                    });
                    
                    html += '</div></div>';
                    document.getElementById('nearbyWordsContainer').innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Nearby entries error:', error);
            });
    }

    function searchWord(word) {
        searchInput.value = word;
        performSearch();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showError(message) {
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }

    function hideError() {
        errorContainer.style.display = 'none';
    }
</script>

<!-- Create Word List Modal -->
<div class="modal fade" id="createWordListModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('panel.create_word_list') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createWordListForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('panel.list_name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('panel.list_description') }} ({{ trans('public.optional') }})</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('panel.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('public.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle create word list form
    document.getElementById('createWordListForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            description: formData.get('description')
        };
        
        fetch('/panel/dictionary/word-lists/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ trans("panel.success") }}',
                    text: '{{ trans("panel.word_list_created_successfully") }}',
                    timer: 2000
                });
                
                $('#createWordListModal').modal('hide');
                this.reset();
                loadWordLists();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ trans("panel.error") }}',
                    text: data.message || '{{ trans("panel.something_went_wrong") }}'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ trans("panel.error") }}',
                text: '{{ trans("panel.something_went_wrong") }}'
            });
        });
    });
</script>
@endpush
