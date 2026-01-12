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
    
    .search-input-wrapper {
        position: relative;
        margin-bottom: 24px;
    }
    
    .search-input {
        width: 100%;
        padding: 16px 56px 16px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
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
        transition: background 0.3s;
    }
    
    .search-btn:hover {
        background: var(--primary-dark);
    }
    
    .result-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: none;
    }
    
    .result-card.show {
        display: block;
    }
    
    .word-header {
        padding-bottom: 24px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 24px;
    }
    
    .word-title {
        font-size: 40px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    
    .word-pronunciation {
        font-size: 20px;
        color: #666;
        margin-bottom: 12px;
        font-family: 'Courier New', monospace;
    }
    
    .word-type-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    .definition-section {
        margin-bottom: 32px;
    }
    
    .definition-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 16px;
    }
    
    .definition-item {
        padding: 16px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .definition-text {
        font-size: 16px;
        color: #333;
        line-height: 1.8;
        margin-bottom: 12px;
    }
    
    .example-text {
        color: #666;
        font-style: italic;
        padding-left: 20px;
        border-left: 4px solid var(--primary);
        margin-top: 12px;
        line-height: 1.6;
    }
    
    .audio-btn {
        background: none;
        border: none;
        color: var(--primary);
        cursor: pointer;
        padding: 8px;
        margin-left: 12px;
        transition: all 0.3s;
        border-radius: 50%;
    }
    
    .audio-btn:hover {
        background: rgba(var(--primary-rgb), 0.1);
        transform: scale(1.1);
    }
    
    .save-flashcard-btn {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 16px;
    }
    
    .save-flashcard-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }
    
    .suggestions-list {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-top: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .suggestion-item {
        padding: 12px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
        font-size: 15px;
    }
    
    .suggestion-item:hover {
        background: #f5f5f5;
        padding-left: 16px;
    }
    
    .loading {
        text-align: center;
        padding: 60px;
    }
    
    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .error-message {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #c62828;
        padding: 20px;
        border-radius: 12px;
        margin-top: 16px;
        border-left: 4px solid #c62828;
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
    }
    
    .nearby-word-badge:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="font-20 font-weight-bold">{{ trans('panel.dictionary_and_flashcard') }}</h2>
                    <a href="{{ url('/panel/dictionary/flashcards') }}" class="btn btn-primary btn-sm">
                        <x-iconsax-lin-bookmark-2 class="mr-1" width="16px" height="16px"/>
                        {{ trans('panel.my_flashcards') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-12">
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
                    <div class="d-flex align-items-center">
                        <h2 class="word-title" id="wordTitle"></h2>
                        <button class="audio-btn" id="audioBtn" style="display: none;">
                            <x-iconsax-bul-volume-high class="icons" width="28px" height="28px"/>
                        </button>
                    </div>
                    <div class="word-pronunciation" id="wordPronunciation"></div>
                    <div id="wordTypes"></div>
                    
                    <button class="save-flashcard-btn" id="saveFlashcardBtn">
                        <x-iconsax-lin-save-2 class="mr-1" width="18px" height="18px"/>
                        {{ trans('panel.save_to_flashcard') }}
                    </button>
                    
                    <div class="btn-group ml-10">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" id="addToListBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <x-iconsax-lin-add-circle class="mr-1" width="16px" height="16px"/>
                            {{ trans('panel.add_to_list') }}
                        </button>
                        <div class="dropdown-menu" id="wordListsDropdown">
                            <div class="dropdown-item text-center">
                                <small class="text-gray">{{ trans('panel.loading') }}...</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="definitionsContainer"></div>

                <div id="nearbyWordsContainer" style="margin-top: 32px;"></div>
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
        
        const formData = {
            word: currentWordData.headword || searchInput.value,
            pronunciation: document.getElementById('wordPronunciation').textContent,
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
                }
            })
            .catch(error => {
                console.error('Error loading word lists:', error);
            });
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
            const formData = {
                word: currentWordData.headword || searchInput.value,
                pronunciation: document.getElementById('wordPronunciation').textContent,
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
        
        // Display pronunciation
        if (data.pronunciations && data.pronunciations.length > 0) {
            const pron = data.pronunciations[0];
            document.getElementById('wordPronunciation').textContent = `/${pron.ipa || ''}/`;
            
            // Audio button
            if (pron.audio && pron.audio.length > 0) {
                const audioBtn = document.getElementById('audioBtn');
                audioBtn.style.display = 'inline-block';
                audioBtn.onclick = () => {
                    const audio = new Audio(pron.audio[0].url);
                    audio.play();
                };
            }
        }

        // Display definitions (HTML format from API)
        if (data.entryContent) {
            document.getElementById('definitionsContainer').innerHTML = data.entryContent;
        }

        resultContainer.classList.add('show');

        // Load nearby entries
        if (data.entryId) {
            loadNearbyEntries(data.entryId);
        }
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
@endpush
