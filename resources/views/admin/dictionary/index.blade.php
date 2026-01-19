@extends('admin.layouts.app')

@push('styles_top')
<style>
    .dictionary-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .search-box {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .search-input-wrapper {
        position: relative;
    }
    
    .search-input {
        width: 100%;
        padding: 16px 48px 16px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #4CAF50;
    }
    
    .search-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: #4CAF50;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        color: white;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .search-btn:hover {
        background: #45a049;
    }
    
    .result-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: none;
    }
    
    .result-container.show {
        display: block;
    }
    
    .word-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .word-title {
        font-size: 32px;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
    }
    
    .word-pronunciation {
        color: #666;
        font-size: 18px;
        margin-bottom: 8px;
    }
    
    .word-type {
        display: inline-block;
        background: #4CAF50;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        margin-right: 8px;
    }
    
    .definition-section {
        margin-bottom: 24px;
    }
    
    .definition-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 12px;
    }
    
    .definition-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .definition-text {
        color: #444;
        line-height: 1.6;
        margin-bottom: 8px;
    }
    
    .example-text {
        color: #666;
        font-style: italic;
        padding-left: 16px;
        border-left: 3px solid #4CAF50;
        margin-top: 8px;
    }
    
    .audio-btn {
        background: none;
        border: none;
        color: #4CAF50;
        cursor: pointer;
        font-size: 24px;
        padding: 0;
        margin-left: 8px;
    }
    
    .audio-btn:hover {
        color: #45a049;
    }
    
    .suggestions-list {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-top: 8px;
        padding: 12px;
    }
    
    .suggestion-item {
        padding: 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: background 0.2s;
    }
    
    .suggestion-item:hover {
        background: #f5f5f5;
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    
    .error-message {
        background: #ffebee;
        color: #c62828;
        padding: 16px;
        border-radius: 8px;
        margin-top: 16px;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ trans('admin/main.dictionary_and_flashcard') }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">{{ trans('admin/main.dictionary_and_flashcard') }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="dictionary-container">
            @if(!$hasApiKey)
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">
                        <x-iconsax-bul-info-circle class="mr-2" width="24px" height="24px"/>
                        Cambridge Dictionary API Key Required
                    </h4>
                    <p>To use the dictionary feature, you need to configure the Cambridge Dictionary API key.</p>
                    <hr>
                    <p class="mb-0">
                        <strong>Steps to configure:</strong><br>
                        1. Visit <a href="https://dictionary.cambridge.org/api/" target="_blank">Cambridge Dictionary API</a> to get your API key<br>
                        2. Add <code>CAMBRIDGE_DICT_ACCESS_KEY=your_api_key_here</code> to your <code>.env</code> file<br>
                        3. Run <code>php artisan config:clear</code> to refresh the configuration<br>
                        4. Reload this page
                    </p>
                </div>
            @endif

            <!-- Search Box -->
            <div class="search-box">
                <h4 class="mb-3">{{ trans('admin/main.search_word') }}</h4>
                <div class="search-input-wrapper">
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="search-input" 
                        placeholder="{{ trans('admin/main.enter_word_to_search') }}"
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
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">{{ trans('admin/main.searching') }}...</p>
            </div>

            <!-- Error Message -->
            <div id="errorContainer" class="error-message" style="display: none;"></div>

            <!-- Result Container -->
            <div id="resultContainer" class="result-container">
                <div class="word-header">
                    <div class="d-flex align-items-center">
                        <h2 class="word-title" id="wordTitle"></h2>
                        <button class="audio-btn" id="audioBtn" style="display: none;">
                            <x-iconsax-bul-volume-high class="icons" width="24px" height="24px"/>
                        </button>
                    </div>
                    <div class="word-pronunciation" id="wordPronunciation"></div>
                    <div id="wordTypes"></div>
                </div>

                <div id="definitionsContainer"></div>

                <div id="nearbyWordsContainer" style="margin-top: 24px;"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts_bottom')
<script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const resultContainer = document.getElementById('resultContainer');
    const loadingContainer = document.getElementById('loadingContainer');
    const errorContainer = document.getElementById('errorContainer');
    const suggestionsContainer = document.getElementById('suggestionsContainer');
    
    let searchTimeout;

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

    function performSearch() {
        const query = searchInput.value.trim();
        
        if (!query) {
            showError('{{ trans("admin/main.please_enter_word") }}');
            return;
        }

        hideError();
        resultContainer.classList.remove('show');
        loadingContainer.style.display = 'block';
        suggestionsContainer.innerHTML = '';

        fetch(`{{ getAdminPanelUrl() }}/dictionary/search-first?q=${encodeURIComponent(query)}&dictionary=english-vietnamese&format=html`)
            .then(response => response.json())
            .then(data => {
                loadingContainer.style.display = 'none';
                
                if (data.success && data.data) {
                    displayResult(data.data);
                } else {
                    showError('{{ trans("admin/main.word_not_found") }}');
                    // Try to get suggestions
                    getSuggestions(query);
                }
            })
            .catch(error => {
                loadingContainer.style.display = 'none';
                showError('{{ trans("admin/main.search_error") }}: ' + error.message);
            });
    }

    function getSuggestions(query) {
        fetch(`{{ getAdminPanelUrl() }}/dictionary/did-you-mean?q=${encodeURIComponent(query)}&dictionary=english-vietnamese&max=5`)
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
        html += '<strong>{{ trans("admin/main.did_you_mean") }}:</strong><br>';
        
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
        fetch(`{{ getAdminPanelUrl() }}/dictionary/nearby-entries?entryId=${encodeURIComponent(entryId)}&dictionary=english-vietnamese&max=5`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    let html = '<div class="definition-section">';
                    html += '<div class="definition-title">{{ trans("admin/main.related_words") }}</div>';
                    html += '<div class="d-flex flex-wrap">';
                    
                    data.data.forEach(entry => {
                        html += `<span class="badge badge-primary mr-2 mb-2" style="cursor: pointer;" onclick="searchWord('${entry.headword}')">${entry.headword}</span>`;
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
