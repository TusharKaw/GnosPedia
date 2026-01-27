# GnosPedia Frontend Architecture & Implementation Plan

## 1. Frontend Architecture
We will build a **Next.js** application serving as the custom frontend for the GnosPedia MediaWiki backend.

**Tech Stack:**
*   **Framework:** Next.js (App Router)
*   **Language:** TypeScript
*   **Styling:** Vanilla CSS (CSS Modules) with a Theme Variable System (Variables for Colors, Spacing, Typography).
*   **State Management:** React Context + Hooks (or lightweight library if needed).
*   **Editor:** Block-based editor (e.g., Tiptap or custom implementation mapping to JSON) that serializes to/from MediaWiki content.

## 2. Data Flow
MediaWiki acts as the "Headless CMS" and Revision Engine.

`[Client (Browser)]`  <--(JSON)-->  `[Next.js API Routes (BFF)]`  <--(HTTPS/API)-->  `[MediaWiki API]`

1.  **Read Operations (Page View):**
    *   Client requests page `/workspace/page-title`.
    *   Next.js fetches current revision content from MediaWiki API (`via action=parse` or `action=query`).
    *   MediaWiki returns HTML/Wikitext.
    *   Next.js parses Wikitext/HTML into a Block JSON structure (if strictly mimicking Notion) or renders sanitized HTML wrapped in custom components. *Decision: To support "Notion-like" editing, we likely need to store content as JSON in MediaWiki or parse Wikitext bi-directionally.*
    *   *Strategy Phase 1:* Use standard MediaWiki revisions. We will store a JSON representation in the page content or use a custom content model if possible. For simplest MVP, we might store JSON stringified in the page text.

2.  **Write Operations (Save/Edit):**
    *   User edits blocks in Frontend.
    *   Client sends generic "Save" request with JSON payload.
    *   Next.js converts JSON -> Wikitext (or stores JSON directly if using a JSON content handler extension).
    *   Next.js calls MediaWiki API `action=edit`.
    *   MediaWiki handles versioning, rollbackable history, and user attribution.

3.  **Files/Assets:**
    *   Uploads go to MediaWiki `action=upload`.
    *   File history is managed by MediaWiki.

4.  **Discussions:**
    *   Mapped to MediaWiki "Talk" namespaces or a custom Flow-like extension if available, but simplest is treating them as appended sections in "Talk:PageName".

## 3. Component Hierarchy

```
App
├── Layout (SidebarNavigation, MainContentArea, UserProfile)
│   ├── WorkspaceSelector (Subdomain handling)
│   ├── NavigationTree (Pages, Tasks, Files)
│   └── GlobalChatWidget (Overlay)
├── PageView
│   ├── PageHeader (Title, Breadcrumbs, Participants, "Discussion" Tab)
│   ├── EditorCanvas (The "Notion" part)
│   │   ├── BlockList
│   │   │   ├── TextBlock
│   │   │   ├── HeadingBlock
│   │   │   ├── ImageBlock
│   │   │   ├── TaskBlock (Checkbox + Metadata)
│   │   │   └── TableBlock
│   │   └── InlineCommentThreads
│   └── PageSidebar (Right - Info, History, TOC)
└── RevisionHistoryView
    ├── DiffViewer (Visual side-by-side or inline)
    └── RollbackControls
```

## 4. UI Wireframe Structure

**Layout Grid:**
*   **Left Sidebar (240px, collapsible):**
    *   [Workspace Icon/Name]
    *   [Search Bar]
    *   [Favorites]
    *   [Pages Tree]
    *   [Tasks]
    *   [Files]
    *   [Channels (Chat)]
*   **Main Stage (Flexible):**
    *   **Top Bar:** Breadcrumbs | Last Edited | [Share] [Menu]
    *   **Content:** Centered document container (max-width: 900px).
*   **Right Sidebar (Optional/Toggleable):**
    *   Threaded comments.
    *   Page Metadata.

## 5. Theme System (Light/Dark)
We will use CSS Variables rooted in `:root` and `[data-theme='dark']`.

```css
:root {
  --bg-primary: #ffffff;
  --bg-secondary: #f7f7f5; /* Notion-like light gray */
  --text-primary: #37352f;
  --text-secondary: #9b9a97;
  --border-subtle: #e1e1e0;
  --accent: #2383e2;
  --font-sans: 'Inter', sans-serif;
  --font-serif: 'Lyon-Text', serif; /* DNA of "seriousness" */
}

[data-theme='dark'] {
  --bg-primary: #191919;
  --bg-secondary: #202020;
  --text-primary: #d4d4d4;
  --text-secondary: #9b9a97;
  --border-subtle: #303030;
}
```

## 6. Suggested MediaWiki Extensions
To support this "Headless" and "SaaS" feel:
1.  **MediaWikiFarm** (Already installed? - implied by "Subdomain-based workspaces") - *Crucial for the multi-tenant requirement.*
2.  **OAuth / BotPasswords** - For API authentication from the Next.js app.
3.  **VisualEditor / Parsoid** (Optional) - If we want to leverage existing HTML conversion, though a custom JSON-to-Wikitext parser is cleaner for "Notion-like" blocks.
4.  **Echo** - For notifications.
5.  **PageImages** - For file previews.

## 7. Next Steps
1.  Initialize Next.js project in `frontend/`.
2.  Setup CSS Variables in `globals.css`.
3.  Build the Shell Layout.
