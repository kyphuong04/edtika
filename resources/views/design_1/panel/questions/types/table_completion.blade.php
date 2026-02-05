{{-- Table Completion (Table Builder) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="purple">
    <h4>
        <x-iconsax-lin-grid-1 class="icons text-purple mr-8" width="20px" height="20px"/>
        Table Completion
        <span class="badge badge-purple ml-8">Table Builder</span>
    </h4>
    
    <div class="info-box purple">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Table Builder:</strong> Tạo bảng với nhiều cột và hàng. Đánh dấu ô cần điền bằng số câu hỏi (ví dụ: <code>[11]</code>). Các ô khác nhập nội dung thông thường.</span>
    </div>
    
    {{-- Table Configuration --}}
    <div class="row mb-20">
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit</label>
                <select id="tcWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN TWO WORDS AND/OR A NUMBER">NO MORE THAN TWO WORDS AND/OR A NUMBER</option>
                    <option value="NO MORE THAN THREE WORDS">NO MORE THAN THREE WORDS</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-group mb-0">
                <label class="form-group-label">Số cột</label>
                <input type="number" id="tcNumCols" class="form-control" value="3" min="2" max="6">
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-group mb-0">
                <label class="form-group-label">Số hàng</label>
                <input type="number" id="tcNumRows" class="form-control" value="5" min="2" max="12">
            </div>
        </div>
        <div class="col-12 col-md-5 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-primary" id="tcGenerateTable" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); border: none;">
                <x-iconsax-lin-grid-1 class="icons mr-4" width="16px" height="16px"/>Tạo bảng
            </button>
            <div class="batch-progress flex-fill ml-16">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div id="tcProgress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);"></div>
                </div>
                <small class="text-muted mt-4 d-block"><span id="tcValidCount">0</span> câu hỏi đã tạo</small>
            </div>
        </div>
    </div>
    
    {{-- Table Builder Container --}}
    <div class="tc-table-builder-container" id="tcTableContainer" style="display: none;">
        <div class="tc-builder-header mb-12">
            <span class="text-muted">Nhập tiêu đề cột và nội dung ô. Dùng <code>[11]</code>, <code>[12]</code>... để đánh dấu ô cần điền câu trả lời.</span>
        </div>
        
        <div class="tc-table-scroll" style="overflow-x: auto;">
            <table class="tc-builder-table" id="tcBuilderTable">
                <thead id="tcTableHead"></thead>
                <tbody id="tcTableBody"></tbody>
            </table>
        </div>
    </div>
    
    {{-- Question List (extracted from table) --}}
    <div id="tcQuestionsList" style="display: none;">
        <div class="info-box purple mt-20">
            <x-iconsax-bul-tick-circle class="icons" width="16px" height="16px"/>
            <span><strong id="tcQuestionsCount">0</strong> câu hỏi được trích xuất từ bảng</span>
        </div>
        <div id="tcExtractedQuestions" class="mt-12"></div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="tcBatchData" value="[]">
</div>

<style>
.badge-purple { background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: #fff; }
.text-purple { color: #6f42c1 !important; }
.info-box.purple { background: rgba(111, 66, 193, 0.08); border-left-color: #6f42c1; }

/* Table Builder Styles */
.tc-builder-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tc-builder-table th {
    background: #e8e8e8;
    border: 1px solid #c0c0c0;
    padding: 8px;
    min-width: 150px;
}

.tc-builder-table th input {
    width: 100%;
    border: 1px solid #d9d9d9;
    padding: 6px;
    font-weight: 600;
    background: #ffffff;
}

.tc-builder-table td {
    border: 1px solid #c0c0c0;
    padding: 4px;
    min-width: 150px;
    position: relative;
}

.tc-builder-table td input, 
.tc-builder-table td textarea {
    width: 100%;
    border: 1px solid #d9d9d9;
    padding: 6px;
    min-height: 40px;
    resize: vertical;
}

.tc-builder-table td.has-question {
    background: rgba(0, 180, 216, 0.08);
}

.tc-builder-table td.has-question input,
.tc-builder-table td.has-question textarea {
    background: rgba(0, 180, 216, 0.05);
    border-color: #00b4d8;
    font-weight: 500;
}

/* Extracted Questions */
.tc-extracted-question {
    background: #f8f9fa;
    border-left: 3px solid #6f42c1;
    padding: 12px;
    margin-bottom: 8px;
    border-radius: 4px;
}

.tc-extracted-question .q-number {
    display: inline-block;
    background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);
    color: #fff;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    margin-right: 8px;
}

.tc-extracted-question .q-answer {
    margin-top: 8px;
    padding: 6px;
    background: rgba(0, 180, 216, 0.1);
    border-radius: 3px;
    font-weight: 600;
}

.tc-extracted-question input {
    border: 1px solid #d9d9d9;
    padding: 4px 8px;
    border-radius: 3px;
}
</style>

<script>
setTimeout(function() {
    let tableData = { headers: [], rows: [] };
    let extractedQuestions = [];
    const startQNum = parseInt($('#question_number').val()) || 1;
    
    // Generate table structure
    function generateTable() {
        const numCols = parseInt($('#tcNumCols').val()) || 3;
        const numRows = parseInt($('#tcNumRows').val()) || 5;
        
        tableData = {
            headers: Array(numCols).fill(''),
            rows: Array(numRows).fill(null).map(() => Array(numCols).fill(''))
        };
        
        renderTable();
        $('#tcTableContainer').slideDown(300);
    }
    
    function renderTable() {
        // Render headers
        let headHtml = '<tr>';
        tableData.headers.forEach((h, idx) => {
            headHtml += `<th><input type="text" value="${h}" placeholder="Column ${idx + 1}" data-col="${idx}" class="tc-header-input"></th>`;
        });
        headHtml += '</tr>';
        $('#tcTableHead').html(headHtml);
        
        // Render body
        let bodyHtml = '';
        tableData.rows.forEach((row, rowIdx) => {
            bodyHtml += '<tr>';
            row.forEach((cell, colIdx) => {
                const hasQuestion = cell.match(/\[(\d+)\]/);
                const cssClass = hasQuestion ? 'has-question' : '';
                bodyHtml += `<td class="${cssClass}"><textarea data-row="${rowIdx}" data-col="${colIdx}" class="tc-cell-input">${cell}</textarea></td>`;
            });
            bodyHtml += '</tr>';
        });
        $('#tcTableBody').html(bodyHtml);
    }
    
    function extractQuestions() {
        extractedQuestions = [];
        let qCounter = 0;
        
        tableData.rows.forEach((row, rowIdx) => {
            row.forEach((cell, colIdx) => {
                // Find ALL question markers in this cell using matchAll
                const matches = cell.matchAll(/\[(\d+)\]/g);
                
                for (const match of matches) {
                    const qNum = parseInt(match[1]);
                    const columnHeader = tableData.headers[colIdx] || `Column ${colIdx + 1}`;
                    
                    extractedQuestions.push({
                        number: qNum,
                        row: rowIdx,
                        col: colIdx,
                        column_header: columnHeader,
                        cell_text: cell.replace(/\[(\d+)\]/g, '___').trim(), // Replace all markers with ___
                        answer: ''
                    });
                    qCounter++;
                }
            });
        });
        
        // Sort by question number
        extractedQuestions.sort((a, b) => a.number - b.number);
        
        if (extractedQuestions.length > 0) {
            renderExtractedQuestions();
            $('#tcQuestionsList').slideDown(300);
        } else {
            $('#tcQuestionsList').slideUp(300);
        }
        
        updateProgress();
    }
    
    function renderExtractedQuestions() {
        let html = '';
        extractedQuestions.forEach((q, idx) => {
            html += `
                <div class="tc-extracted-question">
                    <div>
                        <span class="q-number">Q${q.number}</span>
                        <strong>${q.column_header}</strong>: ${q.cell_text || '(Trống)'}
                        <span class="text-muted ml-8">[R${q.row + 1}, C${q.col + 1}]</span>
                    </div>
                    <div class="q-answer">
                        <label class="mb-4" style="font-size: 12px; color: #666;">Đáp án đúng:</label>
                        <input type="text" class="tc-q-answer" data-idx="${idx}" value="${q.answer}" placeholder="Nhập đáp án...">
                    </div>
                </div>
            `;
        });
        $('#tcExtractedQuestions').html(html);
        $('#tcQuestionsCount').text(extractedQuestions.length);
    }
    
    function updateProgress() {
        const validCount = extractedQuestions.filter(q => q.answer.trim().length > 0).length;
        const total = extractedQuestions.length;
        const percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#tcProgress').css('width', percent + '%');
        $('#tcValidCount').text(validCount);
    }
    
    function generateBatchData() {
        const wordLimit = $('#tcWordLimit').val();
        const questions = [];
        
        extractedQuestions.forEach(q => {
            if (q.answer.trim().length > 0) {
                questions.push({
                    question_number: q.number,
                    question_text: `${q.column_header}: ${q.cell_text}`,
                    correct_answer: q.answer.trim(),
                    question_type: 'table_completion',
                    question_type_label: 'Table Completion',
                    word_limit: wordLimit,
                    row_position: q.row,
                    col_position: q.col,
                    column_header: q.column_header,
                    table_headers: tableData.headers.filter(h => h.trim().length > 0),
                    table_structure: {
                        headers: tableData.headers,
                        rows: tableData.rows,
                        num_cols: tableData.headers.length,
                        num_rows: tableData.rows.length
                    }
                });
            }
        });
        
        $('#tcBatchData').val(JSON.stringify(questions));
    }
    
    // Event handlers
    $('#tcGenerateTable').on('click', generateTable);
    
    $(document).on('input', '.tc-header-input', function() {
        const colIdx = $(this).data('col');
        tableData.headers[colIdx] = $(this).val();
    });
    
    $(document).on('input', '.tc-cell-input', function() {
        const rowIdx = $(this).data('row');
        const colIdx = $(this).data('col');
        tableData.rows[rowIdx][colIdx] = $(this).val();
        
        // Check if this cell has a question marker
        const hasQuestion = $(this).val().match(/\[(\d+)\]/);
        $(this).closest('td').toggleClass('has-question', !!hasQuestion);
        
        // Debounced extract
        clearTimeout(window.tcExtractTimeout);
        window.tcExtractTimeout = setTimeout(extractQuestions, 500);
    });
    
    $(document).on('input', '.tc-q-answer', function() {
        const idx = $(this).data('idx');
        extractedQuestions[idx].answer = $(this).val();
        updateProgress();
        generateBatchData();
    });
    
    $('#tcWordLimit').on('change', generateBatchData);
    
}, 100);
</script>
