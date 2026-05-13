# IELTS Test Creation Guide - Hierarchical Structure

## 📋 Overview

The new IELTS test creation system is organized in a **three-level hierarchy** to make it easy to create comprehensive mock and practice tests:

```
Test (Mock/Practice)
  ↓
Sections (Skills: Listening, Reading, Writing, Speaking)
  ↓
Parts/Question Groups (with descriptions, media, question type info)
  ↓
Individual Questions
```

---

## 🎯 Creating a New Test

### Step 1: Initiate Test Creation

1. Go to **Admin Panel** → **IELTS Tests** → **Create** button
2. You'll see two options:
   - **Wizard (Recommended)** - Step-by-step guided process
   - **Direct Form** - All fields on one page

### Step 2: Use the Wizard (Recommended)

#### **Wizard Step 1: Choose Test Type**

- **Mock Test**: Full 4-skill exam in official order
  - Listening (30 min, 40 questions)
  - Reading (60 min, 40 questions)  
  - Writing (60 min, 2 tasks)
  - Speaking (15 min, 3 parts)

- **Practice Test**: Focus on ONE skill for targeted practice
  - Choose: Listening, Reading, Writing, or Speaking

#### **Wizard Step 2: Enter Test Information**

Fill in:
- **Test Title** (e.g., "Academic Reading Practice - Passage 1")
- **Format**: Academic or General Training
- **Difficulty Level**: Beginner, Intermediate, Advanced
- **Target Band**: Minimum and Maximum (optional)
- **Description**: Brief notes about this test

For **Practice Tests**, also set:
- **Duration**: Pre-filled based on skill
- **Practice Mode**: Untimed, Timed, or Exam Mode
- **Show answers immediately**: Yes/No
- **Allow retakes**: Yes/No

#### **Wizard Step 3: Review Sections**

See a preview of sections that will be created:
- For **Mock**: All 4 skills will be auto-created
- For **Practice**: Single skill section will be created

#### **Wizard Step 4: Review & Create**

Confirm all details and click **"Create Test & Add Content"**

✅ **Test created!** You'll be redirected to add question groups/parts.

---

## 🏗️ Managing Section Hierarchy

### View Test Structure

After creating a test, click **"View Sections"** to see:
- All sections (Listening, Reading, etc.)
- All parts/question groups within each section
- Question completion progress
- Overall test completion percentage

### Example: Listening Test Structure

```
Section: Listening (Questions 1-40, 30 minutes)
├── Part 1 (Questions 1-10): Conversation - Form Completion
│   └── [7/10 questions added]
├── Part 2 (Questions 11-20): Monologue - Multiple Choice
│   └── [10/10 questions added] ✓
├── Part 3 (Questions 21-30): Conversation - Matching
│   └── [8/10 questions added]
└── Part 4 (Questions 31-40): Academic Lecture - Short Answer
    └── [5/10 questions added]
```

---

## 📝 Adding Parts/Question Groups

### To Add a New Part:

1. In section view, click **"Manage Parts"** button
2. Click **"Add New Part"** button
3. Fill in the Question Group Form:

#### **Part Information**

| Field | Description | Example |
|-------|-------------|---------|
| **Title/Prompt** | What students will see | "The housing officer takes some details from the girl. Complete the form with NO MORE THAN THREE WORDS AND/OR A NUMBER." |
| **Question Type** | Type of questions | Multiple Choice, Table Completion, Fill in Blanks, etc. |
| **Question Range** | Which questions | 1-5, or 11-15, or 31-40 |
| **Max Words** | Word limit (if applicable) | 3, 2, etc. |
| **Target Band** | Target ability level | 5.5, 6.0, 6.5, etc. |
| **Instructions** | Additional notes | "Write no more than TWO words..." |

#### **Media Files (Optional)**

Upload media for this part:

- **Audio File** (MP3, WAV, OGG, M4A)
  - For listening comprehension
  - Click to upload or drag-drop

- **Image File** (JPG, PNG, GIF, WebP)
  - Diagrams, charts, or task images
  - Shows preview after upload

- **Video File** (MP4, AVI, MOV, WebM)
  - Video-based comprehension tasks
  - Optional for most question types

- **Passage/Transcript**
  - Full text for reading comprehension
  - Transcript for listening tests
  - Written essays for writing tasks

### Example Part Entry: Listening Part 1

```
Title: "The housing officer takes details from the girl. 
Complete the form with NO MORE THAN THREE WORDS AND/OR A NUMBER for each answer."

Question Type: Table Completion
Question Range: 1-5
Max Words: 3
Audio File: [part1-conversation.mp3]
Instructions: Fill in the gaps in the form. Do not exceed 3 words/numbers.
```

---

## ❓ Adding Questions to Parts

After creating a part, click **"Add Questions"** to enter individual questions:

1. Enter **Question Text** (or leave blank if using audio/images)
2. Select **Question Type** (if different from part default)
3. Enter **Correct Answer**(s)
4. (Optional) Add **Question Image** or **Audio**
5. Save each question

### Question Input Examples

#### **Listening: Fill in the Blanks**
- Q1: The office opens at __________
- Answer: 9 AM

#### **Reading: True/False/Not Given**
- Question: "The author supports renewable energy."
- Answer: True

#### **Writing: Essay**
- Question text: (shown in part title)
- Students write full essay response
- Teacher grades manually

#### **Speaking: Free Response**
- Question: "Describe your favourite hobby."
- Students record audio response

---

## 📊 Monitoring Progress

### Progress Tracking

Each section shows:
- **Progress Bar**: Visual % completion
- **Question Counter**: "7/10 questions added"
- **Status**: Draft, Ready, or Approved

### Overall Test Status

Main test view shows:
- **Sections**: Number created
- **Parts**: Number of groups
- **Questions**: Added vs. Required
- **Completion %**: Overall progress

Color-coded status:
- 🟢 **Green** (100%): Section complete
- 🟡 **Yellow** (50-99%): Section in progress
- 🔴 **Red** (<50%): Just started

---

## 🎨 Best Practices

### Organizing Listening Tests

```
Part 1: Conversation (2-3 speakers)
├── Questions 1-10: Form/Note Completion or Tables
│   Media: Conversation audio (3-5 min)

Part 2: Monologue (single speaker)
├── Questions 11-20: Multiple Choice or Matching
│   Media: Lecture/speech audio (4-6 min)

Part 3: Academic Conversation
├── Questions 21-30: Discussion/Interview questions
│   Media: Multi-speaker conversation audio

Part 4: Academic Monologue
├── Questions 31-40: Summary/completion
│   Media: Technical lecture audio
```

### Organizing Reading Tests

```
Passage 1
├── Questions 1-13: Multiple Choice + Matching
│   Media: Long academic text (800-1000 words)

Passage 2
├── Questions 14-26: Heading matching + True/False
│   Media: Article or report

Passage 3
├── Questions 27-40: Summary completion + Diagram
│   Media: Technical or scientific text
```

### Organizing Writing Tests

```
Task 1 (20 minutes)
├── Question 1: Letter or Formal Writing
│   Prompt shown in part title

Task 2 (40 minutes)
├── Question 2: Essay Writing
│   Prompt shown in part title
```

### Organizing Speaking Tests

```
Part 1 (4-5 minutes)
├── Questions 1-3: Personal questions

Part 2 (3-4 minutes)
├── Question 4: Topic card description

Part 3 (4-5 minutes)
├── Questions 5-?: Discussion questions
```

---

## 🔄 Workflow Tips

### Efficient Test Creation

1. **Create test via Wizard** (2 minutes)
2. **Add Parts/Groups** (10-15 minutes)
   - Title and description
   - Upload media
   - Set question ranges
3. **Add Questions** (30-60 minutes depending on test size)
   - Bulk import from CSV (if using)
   - Or manual entry
4. **Review & Validate** (5 minutes)
   - Check completion %
   - Verify question counts match
   - Submit for approval

### Reusing Content

- **Clone Test**: Duplicate entire test with all content
- **Import from Bank**: Use pre-made question groups
- **Copy from Another Test**: Reference existing similar tests

---

## ✅ Validation Checklist

Before submitting test for approval:

- [ ] All sections created
- [ ] All parts/groups have:
  - [ ] Title/description
  - [ ] Question type specified
  - [ ] Question range (start-end)
  - [ ] Media uploaded (if needed)
- [ ] All required questions added
  - [ ] Listening: 40 questions
  - [ ] Reading: 40 questions
  - [ ] Writing: 2 tasks
  - [ ] Speaking: 3 parts (flexible)
- [ ] No duplicate question numbers
- [ ] All questions have answers
- [ ] Completion: 100%

---

## 🆘 Troubleshooting

### Issue: Can't add questions to a part
**Solution**: Ensure part is created first with proper question range (e.g., 1-5)

### Issue: Media file not uploading
**Solution**: Check file size (<50MB) and format (MP3, PNG, MP4, etc.)

### Issue: Test shows incomplete despite adding questions
**Solution**: Check that question numbers match the part's range (e.g., Q1-5 for Part 1)

### Issue: Can't delete a part
**Solution**: Delete all questions in that part first, then delete the empty part

---

## 📁 Files Structure

- **Test Creation**: Admin → IELTS Tests → Create Wizard
- **Section Management**: Test Edit → Manage Sections
- **Part Management**: Section View → Manage Parts
- **Question Management**: Part View → Add Questions

---

## 💡 Advanced Features (Coming Soon)

- Bulk CSV import for questions
- Drag-drop question reordering
- Question bank sharing
- Test templates
- AI-powered grading suggestions
- Performance analytics

---

**Last Updated**: May 2026
**Version**: 1.0
