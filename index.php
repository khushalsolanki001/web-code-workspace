<!DOCTYPE html>
<html>
<head>
    <title>Visual Studio Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked@9.1.6/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
            overflow: hidden;
            background: #1e1e1e;
            color: #cccccc;
        }
        
        /* VS Code Color Scheme */
        :root {
            --vscode-bg: #1e1e1e;
            --vscode-sidebar: #252526;
            --vscode-activitybar: #2d2d30;
            --vscode-editor: #1e1e1e;
            --vscode-titlebar: #3c3c3c;
            --vscode-menubar: #2d2d30;
            --vscode-statusbar: #007acc;
            --vscode-text: #cccccc;
            --vscode-text-secondary: #858585;
            --vscode-border: #3e3e42;
            --vscode-hover: #2a2d2e;
            --vscode-active: #094771;
        }
        
        /* Title Bar */
        .titlebar {
            background: var(--vscode-titlebar);
            height: 30px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            -webkit-app-region: drag;
            user-select: none;
            border-bottom: 1px solid var(--vscode-border);
        }
        
        .titlebar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 12px;
            -webkit-app-region: no-drag;
        }
        
        .titlebar-logo img {
            width: 16px;
            height: 16px;
        }
        
        .titlebar-title {
            font-size: 12px;
            color: var(--vscode-text);
            margin-left: 8px;
        }
        
        /* Menu Bar */
        .menubar {
            background: var(--vscode-menubar);
            height: 30px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            border-bottom: 1px solid var(--vscode-border);
            font-size: 13px;
        }
        
        .menubar-item {
            padding: 4px 8px;
            cursor: pointer;
            color: var(--vscode-text);
            border-radius: 3px;
            transition: background 0.1s;
        }
        
        .menubar-item:hover {
            background: var(--vscode-hover);
        }
        
        /* Activity Bar */
        .activitybar {
            width: 48px;
            background: var(--vscode-activitybar);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px 0;
            border-right: 1px solid var(--vscode-border);
        }
        
        .activitybar-item {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--vscode-text-secondary);
            transition: all 0.1s;
            position: relative;
        }
        
        .activitybar-item:hover {
            color: var(--vscode-text);
        }
        
        .activitybar-item.active {
            color: var(--vscode-text);
        }
        
        .activitybar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--vscode-statusbar);
        }
        
        .activitybar-item svg {
            width: 24px;
            height: 24px;
        }
        
        /* Sidebar */
        .sidebar {
            width: 300px;
            background: var(--vscode-sidebar);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--vscode-border);
            position: relative;
        }
        
        .sidebar-resizer {
            width: 4px;
            background: transparent;
            cursor: col-resize;
            position: absolute;
            right: -2px;
            top: 0;
            bottom: 0;
            z-index: 10;
        }
        
        .sidebar-resizer:hover {
            background: var(--vscode-statusbar);
        }
        
        .sidebar-resizer.resizing {
            background: var(--vscode-statusbar);
        }
        
        .sidebar-header {
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--vscode-text-secondary);
            border-bottom: 1px solid var(--vscode-border);
            letter-spacing: 0.5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .workspace-selector {
            display: flex;
            gap: 4px;
            font-size: 10px;
        }
        
        .workspace-btn {
            padding: 2px 6px;
            background: var(--vscode-hover);
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text-secondary);
            cursor: pointer;
            border-radius: 2px;
        }
        
        .workspace-btn.active {
            background: var(--vscode-statusbar);
            color: white;
            border-color: var(--vscode-statusbar);
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }
        
        /* File Explorer */
        .file-explorer {
            padding: 4px 0;
        }
        
        .file-item {
            padding: 4px 8px 4px 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--vscode-text);
            position: relative;
        }
        
        .file-item:hover {
            background: var(--vscode-hover);
        }
        
        .file-item.selected {
            background: var(--vscode-active);
        }
        
        .file-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        
        .file-name {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .file-actions {
            display: none;
            gap: 4px;
        }
        
        .file-item:hover .file-actions {
            display: flex;
        }
        
        .file-action-btn {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            color: var(--vscode-text-secondary);
        }
        
        .file-action-btn:hover {
            background: var(--vscode-hover);
            color: var(--vscode-text);
        }
        
        .file-action-btn.delete:hover {
            background: #a1260d;
            color: white;
        }
        
        /* Editor Area */
        .editor-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--vscode-editor);
        }
        
        .editor-tabs {
            background: var(--vscode-titlebar);
            height: 35px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            border-bottom: 1px solid var(--vscode-border);
            overflow-x: auto;
        }
        
        .editor-tab {
            padding: 6px 16px 6px 8px;
            background: var(--vscode-titlebar);
            color: var(--vscode-text-secondary);
            cursor: pointer;
            border-right: 1px solid var(--vscode-border);
            font-size: 13px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
        }
        
        .editor-tab.active {
            background: var(--vscode-editor);
            color: var(--vscode-text);
        }
        
        .editor-tab:hover {
            background: var(--vscode-hover);
        }
        
        .editor-tab:hover .tab-close {
            opacity: 1;
        }
        
        .tab-close {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            opacity: 0;
            transition: opacity 0.2s, background 0.2s;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }
        
        .tab-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .editor-tab.active .tab-close {
            opacity: 1;
        }
        
        .editor-content {
            flex: 1;
            position: relative;
        }
        
        #code {
            width: 100%;
            height: 100%;
            background: var(--vscode-editor);
            color: #d4d4d4;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            padding: 20px;
            border: none;
            outline: none;
            resize: none;
        }
        
        /* Right Sidebar (AI Panel) */
        .right-sidebar {
            width: 350px;
            background: var(--vscode-sidebar);
            display: flex;
            flex-direction: column;
            border-left: 1px solid var(--vscode-border);
            position: relative;
        }
        
        .right-sidebar-resizer {
            width: 4px;
            background: transparent;
            cursor: col-resize;
            position: absolute;
            left: -2px;
            top: 0;
            bottom: 0;
            z-index: 10;
        }
        
        .right-sidebar-resizer:hover {
            background: var(--vscode-statusbar);
        }
        
        .right-sidebar-resizer.resizing {
            background: var(--vscode-statusbar);
        }
        
        /* Status Bar */
        .statusbar {
            height: 22px;
            background: var(--vscode-statusbar);
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 12px;
            color: white;
            justify-content: space-between;
        }
        
        .statusbar-left, .statusbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .statusbar-item {
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 2px;
        }
        
        .statusbar-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Chat Messages */
        .chat-message {
            padding: 8px 12px;
            margin-bottom: 8px;
            background: var(--vscode-hover);
            border-radius: 4px;
            font-size: 13px;
        }
        
        .chat-message-header {
            font-weight: 600;
            color: var(--vscode-statusbar);
            margin-bottom: 4px;
            font-size: 12px;
        }
        
        .chat-input-container {
            padding: 8px;
            border-top: 1px solid var(--vscode-border);
        }
        
        .chat-input {
            width: 100%;
            background: var(--vscode-editor);
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text);
            padding: 6px 8px;
            border-radius: 3px;
            font-size: 13px;
            margin-bottom: 4px;
        }
        
        .chat-input:focus {
            outline: 1px solid var(--vscode-statusbar);
        }
        
        .chat-button {
            background: var(--vscode-statusbar);
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
        }
        
        .chat-button:hover {
            background: #0066aa;
        }
        
        /* AI Chat */
        #aiChatMessages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            font-size: 13px;
        }
        
        .ai-message {
            margin-bottom: 12px;
            padding: 8px;
            background: var(--vscode-hover);
            border-radius: 4px;
        }
        
        .ai-message.user {
            background: var(--vscode-active);
            margin-left: 20px;
        }
        
        .ai-message.assistant {
            background: var(--vscode-hover);
            margin-right: 20px;
        }
        
        #aiChatMessages pre {
            background: #1e293b !important;
            border-radius: 4px;
            padding: 12px;
            overflow-x: auto;
            margin: 8px 0;
            border: 1px solid var(--vscode-border);
        }
        
        #aiChatMessages code {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 12px;
        }
        
        #aiChatMessages pre code {
            background: transparent !important;
            padding: 0;
            color: #d4d4d4;
        }
        
        /* Toast Notifications */
        .custom-toast { 
            position: fixed; 
            top: 60px; 
            right: 20px; 
            padding: 12px 20px; 
            border-radius: 4px; 
            color: white; 
            z-index: 10000; 
            display: none; 
            font-weight: 500;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .bg-success { background: #0e639c; }
        .bg-error { background: #a1260d; }
        .bg-info { background: #007acc; }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--vscode-sidebar);
        }
        
        ::-webkit-scrollbar-thumb {
            background: #424242;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #4e4e4e;
        }
        
        /* Toolbar */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 8px;
        }
        
        .toolbar-button {
            padding: 4px 8px;
            background: transparent;
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text);
            cursor: pointer;
            border-radius: 3px;
            font-size: 12px;
            transition: all 0.1s;
        }
        
        .toolbar-button:hover {
            background: var(--vscode-hover);
        }
        
        .toolbar-button.primary {
            background: var(--vscode-statusbar);
            color: white;
            border-color: var(--vscode-statusbar);
        }
        
        .toolbar-button.primary:hover {
            background: #0066aa;
        }
        
        .close-btn {
            background: transparent;
            border: none;
            color: var(--vscode-text-secondary);
            cursor: pointer;
            padding: 2px 6px;
            font-size: 16px;
            line-height: 1;
        }
        
        .close-btn:hover {
            color: var(--vscode-text);
            background: var(--vscode-hover);
        }
    </style>
</head>
<body>
    <div id="toast" class="custom-toast"></div>

    <!-- Title Bar -->
    <div class="titlebar">
        <div class="titlebar-logo">
            <img src="logo/vscode.png" alt="VS Code">
            <span class="titlebar-title">Visual Studio Code</span>
        </div>
        <div style="flex: 1;"></div>
        <div class="toolbar">
            <button class="toolbar-button primary" onclick="saveCode()" title="Save (Ctrl+S)">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                    <path d="M13.853 3.646l-2.5-2.5a.5.5 0 0 0-.707 0l-2.5 2.5a.5.5 0 0 0 .707.707L10.5 2.707V12.5a.5.5 0 0 1-1 0V2.707L8.854 4.354a.5.5 0 1 0 .707.707l2.5-2.5zm-7.5 2.5a.5.5 0 0 1 .707 0L9.293 7.5H1.5a.5.5 0 0 0 0 1h7.793L7.06 10.232a.5.5 0 1 0 .707.707l3.5-3.5a.5.5 0 0 0 0-.707l-3.5-3.5a.5.5 0 0 0-.707 0z"/>
                </svg>
                Save
            </button>
            <button class="toolbar-button" onclick="copyCode()" title="Copy">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                </svg>
                Copy
            </button>
            <button class="toolbar-button" onclick="refreshCode()" title="Refresh">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                </svg>
                Sync
            </button>
        </div>
    </div>

    <!-- Menu Bar -->
    <div class="menubar">
        <div class="menubar-item">File</div>
        <div class="menubar-item">Edit</div>
        <div class="menubar-item">Selection</div>
        <div class="menubar-item">View</div>
        <div class="menubar-item">Go</div>
        <div class="menubar-item">Run</div>
        <div class="menubar-item">Terminal</div>
        <div class="menubar-item">Help</div>
    </div>

    <!-- Main Container -->
    <div style="display: flex; height: calc(100vh - 82px);">
        <!-- Activity Bar -->
        <div class="activitybar">
            <div class="activitybar-item active" onclick="toggleSidebar('chat')" title="Chat">
                <svg viewBox="0 0 16 16" fill="currentColor">
                    <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                </svg>
            </div>
            <div class="activitybar-item" onclick="toggleSidebar('explorer')" title="Explorer">
                <svg viewBox="0 0 16 16" fill="currentColor">
                    <path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.553.69 2.301 1.191A1.5 1.5 0 0 0 9.796 3.5h4.204A1.5 1.5 0 0 1 15.5 5v7a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 12.5v-9zM2.5 3a.5.5 0 0 0-.5.5V6h12v-.5a.5.5 0 0 0-.5-.5H9.796a1.5 1.5 0 0 1-1.225-.58L7.964 2.426A.5.5 0 0 0 7.571 2H2.5zM14 7H2v5.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5V7z"/>
                </svg>
            </div>
            <div class="activitybar-item" onclick="toggleSidebar('search')" title="Search">
                <svg viewBox="0 0 16 16" fill="currentColor">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </div>
            <div class="activitybar-item" onclick="toggleSidebar('git')" title="Source Control">
                <svg viewBox="0 0 16 16" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                </svg>
            </div>
        </div>

        <!-- Left Sidebar -->
        <div class="sidebar" id="leftSidebar">
            <div class="sidebar-resizer" id="leftResizer"></div>
            <div class="sidebar-header">
                <span id="sidebarTitle">Chat</span>
                <div class="workspace-selector" id="workspaceSelector" style="display: none;">
                    <button class="workspace-btn active" onclick="switchWorkspace('SET-A')">SET-A</button>
                    <button class="workspace-btn" onclick="switchWorkspace('SET-B')">SET-B</button>
                </div>
            </div>
            <div class="sidebar-content" id="sidebarContent">
                <!-- Chat View -->
                <div id="chatView">
                    <div id="chatBox" style="min-height: 100%;"></div>
                </div>
                <!-- File Explorer View -->
                <div id="explorerView" style="display: none;">
                    <div class="file-explorer" id="fileExplorer"></div>
                </div>
            </div>
            <div class="chat-input-container" id="chatInputContainer">
                <form id="chatForm">
                    <input type="text" id="username" name="username" placeholder="Your Name" class="chat-input" style="margin-bottom: 4px;">
                    <textarea id="msgInput" name="message" placeholder="Type a message..." class="chat-input" style="height: 60px; resize: none;"></textarea>
                    <div style="display: flex; gap: 4px; margin-top: 4px;">
                        <input type="file" name="file" id="fileInput" style="flex: 1; font-size: 11px; padding: 4px;">
                        <button type="submit" class="chat-button">Send</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Editor Area -->
        <div class="editor-container">
            <div class="editor-tabs" id="editorTabs">
                <!-- Tabs will be dynamically added here -->
            </div>
            <div class="editor-content">
                <textarea id="code" spellcheck="false" placeholder="No file open. Click a file in the explorer to open it." style="display: none;"></textarea>
                <div id="noFileOpen" style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--vscode-text-secondary); font-size: 14px;">
                    No file open. Click a file in the explorer to open it.
                </div>
            </div>
        </div>

        <!-- Right Sidebar (AI) -->
        <div class="right-sidebar" id="rightSidebar">
            <div class="right-sidebar-resizer" id="rightResizer"></div>
            <div class="sidebar-header">AI Assistant</div>
            <div id="aiChatMessages" class="sidebar-content">
                <div class="ai-message assistant">
                    <div style="font-weight: 600; margin-bottom: 4px; color: #4ec9b0;">AI Assistant</div>
                    <div>👋 Hello! I'm your programming assistant. I can help you with:</div>
                    <ul style="margin: 8px 0; padding-left: 20px;">
                        <li>Writing and debugging code</li>
                        <li>Code reviews and optimizations</li>
                        <li>Explaining programming concepts</li>
                        <li>Best practices and patterns</li>
                    </ul>
                    <div style="margin-top: 8px; font-size: 11px; color: var(--vscode-text-secondary);">
                        💡 Tip: I can see your current code if you ask about it!
                    </div>
                </div>
            </div>
            <div class="chat-input-container">
                <form id="aiChatForm">
                    <input type="text" id="aiInput" class="chat-input" placeholder="Ask AI..." autocomplete="off">
                    <button type="submit" class="chat-button" style="width: 100%; margin-top: 4px;">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="statusbar">
        <div class="statusbar-left">
            <div class="statusbar-item">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                </svg>
                <span>Ready</span>
            </div>
            <div class="statusbar-item">
                <span>Ln 1, Col 1</span>
            </div>
            <div class="statusbar-item">
                <span>Spaces: 4</span>
            </div>
        </div>
        <div class="statusbar-right">
            <div class="statusbar-item">
                <span>UTF-8</span>
            </div>
            <div class="statusbar-item">
                <span>PHP</span>
            </div>
        </div>
    </div>

    <script>
        let currentWorkspace = 'SET-A';
        let currentSidebar = 'chat';
        let selectedFile = null;
        let openFiles = {}; // { filePath: { name, content, modified } }
        let activeTab = null;
        
        // Custom Notification Function
        function showNotify(msg, type='info') {
            const toast = $("#toast");
            toast.removeClass('bg-success bg-error bg-info').addClass('bg-'+type);
            toast.text(msg).fadeIn().delay(3000).fadeOut();
        }

        function loadMessages(){
            $.get("fetch.php?workspace=" + currentWorkspace, d => {
                $("#chatBox").html(d);
                $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
            });
        }

        function loadFiles(){
            $.get("get_files.php?workspace=" + currentWorkspace, function(files) {
                const explorer = $("#fileExplorer");
                explorer.empty();
                
                if (files.length === 0) {
                    explorer.html('<div style="padding: 12px; color: var(--vscode-text-secondary); font-size: 12px;">No files uploaded yet</div>');
                    return;
                }
                
                files.forEach(file => {
                    const fileItem = $(`
                        <div class="file-item" onclick="openFileInEditor('${file.path.replace(/'/g, "\\'")}', '${file.name.replace(/'/g, "\\'")}')">
                            <svg class="file-icon" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M9 1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8h-1v5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h5v1z"/>
                                <path d="M14.5 0L11 .5V3h3V.5z"/>
                            </svg>
                            <span class="file-name">${file.name}</span>
                            <div class="file-actions" onclick="event.stopPropagation();">
                                <div class="file-action-btn delete" onclick="deleteFile('${file.path.replace(/'/g, "\\'")}')" title="Delete file">
                                    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm5 0A.5.5 0 0 1 11 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM8 1.5a2.5 2.5 0 0 0-2.5 2.5v.5h-1a.5.5 0 0 0 0 1h1v7a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-7h1a.5.5 0 0 0 0-1h-1V4A2.5 2.5 0 0 0 8 1.5zM6.5 4a1.5 1.5 0 0 1 3 0v.5h-3V4z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `);
                    explorer.append(fileItem);
                });
            }, 'json');
        }

        function openFileInEditor(filePath, fileName) {
            // If file is already open, just switch to it
            if (openFiles[filePath]) {
                switchToTab(filePath);
                return;
            }
            
            // Load file content
            $.get("file_operations.php?action=read&file=" + encodeURIComponent(filePath), function(response) {
                if (response.error) {
                    showNotify("❌ " + response.error, "error");
                    return;
                }
                
                // Add to open files
                openFiles[filePath] = {
                    name: fileName,
                    content: response.content,
                    modified: false
                };
                
                // Create tab
                createTab(filePath, fileName);
                
                // Switch to this tab
                switchToTab(filePath);
                
                // Load content into editor
                $("#code").val(response.content);
                updateCursorPosition();
            }, 'json').fail(function() {
                showNotify("❌ Failed to load file", "error");
            });
        }

        function createTab(filePath, fileName) {
            const tab = $(`
                <div class="editor-tab" data-file="${filePath.replace(/"/g, '&quot;')}">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M9 1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8h-1v5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h5v1z"/>
                        <path d="M14.5 0L11 .5V3h3V.5z"/>
                    </svg>
                    <span class="tab-name">${fileName}</span>
                    <span class="tab-close" onclick="event.stopPropagation(); closeTab('${filePath.replace(/'/g, "\\'")}')">&times;</span>
                </div>
            `);
            
            tab.on('click', function(e) {
                if (!$(e.target).hasClass('tab-close')) {
                    switchToTab(filePath);
                }
            });
            
            $("#editorTabs").append(tab);
        }

        function switchToTab(filePath) {
            // Save current file content if modified
            if (activeTab && openFiles[activeTab] && openFiles[activeTab].modified) {
                openFiles[activeTab].content = $("#code").val();
            }
            
            // Update active tab
            $(".editor-tab").removeClass("active");
            const tab = $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`);
            if (tab.length === 0) return; // Tab doesn't exist
            tab.addClass("active");
            
            activeTab = filePath;
            
            // Load file content
            if (openFiles[filePath]) {
                $("#code").val(openFiles[filePath].content);
                $("#code").show();
                $("#noFileOpen").hide();
                
                // Update file name in status bar
                updateStatusBar(openFiles[filePath].name);
                updateCursorPosition();
            }
        }

        function closeTab(filePath) {
            // Check if file is modified
            if (openFiles[filePath] && openFiles[filePath].modified) {
                if (!confirm("File has unsaved changes. Close anyway?")) {
                    return;
                }
            }
            
            // Remove tab
            $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`).remove();
            
            // Remove from open files
            delete openFiles[filePath];
            
            // If this was the active tab, switch to another
            if (activeTab === filePath) {
                const remainingTabs = Object.keys(openFiles);
                if (remainingTabs.length > 0) {
                    switchToTab(remainingTabs[remainingTabs.length - 1]);
                } else {
                    // No files open
                    activeTab = null;
                    $("#code").hide();
                    $("#noFileOpen").show();
                    $("#code").val("");
                    updateStatusBar("");
                }
            }
        }

        function deleteFile(filePath) {
            if (!confirm("Are you sure you want to delete this file? This action cannot be undone.")) {
                return;
            }
            
            // Close tab first if open
            if (openFiles[filePath]) {
                closeTab(filePath);
            }
            
            $.post("file_operations.php", {
                action: 'delete',
                file: filePath,
                workspace: currentWorkspace
            }, function(response) {
                if (response.error) {
                    showNotify("❌ " + response.error, "error");
                } else {
                    showNotify("✅ File deleted successfully", "success");
                    
                    // Reload file list
                    loadFiles();
                }
            }, 'json').fail(function() {
                showNotify("❌ Failed to delete file", "error");
            });
        }

        function switchWorkspace(workspace) {
            // Close all open files
            const openFilePaths = Object.keys(openFiles);
            openFilePaths.forEach(filePath => {
                closeTab(filePath);
            });
            
            currentWorkspace = workspace;
            $(".workspace-btn").removeClass("active");
            $(`.workspace-btn:contains('${workspace}')`).addClass("active");
            loadMessages();
            loadFiles();
            showNotify(`Switched to ${workspace}`, "info");
        }

        function toggleSidebar(type) {
            currentSidebar = type;
            $(".activitybar-item").removeClass("active");
            event.currentTarget.classList.add("active");
            
            if (type === 'chat') {
                $("#sidebarTitle").text("Chat");
                $("#workspaceSelector").show();
                $("#chatView").show();
                $("#explorerView").hide();
                $("#chatInputContainer").show();
                $("#fileMessageView").hide();
            } else if (type === 'explorer') {
                $("#sidebarTitle").text("Explorer");
                $("#workspaceSelector").show();
                $("#chatView").hide();
                $("#explorerView").show();
                $("#chatInputContainer").hide();
                loadFiles();
            } else {
                $("#sidebarTitle").text(type.charAt(0).toUpperCase() + type.slice(1));
                $("#workspaceSelector").hide();
                $("#chatView").hide();
                $("#explorerView").hide();
                $("#chatInputContainer").hide();
            }
        }
        
        function updateStatusBar(fileName) {
            if (fileName) {
                $(".statusbar-item:contains('PHP')").html(fileName.split('.').pop().toUpperCase() || 'TEXT');
            }
        }
        
        // Track file modifications
        $("#code").on("input", function() {
            if (activeTab && openFiles[activeTab]) {
                openFiles[activeTab].modified = true;
                const tab = $(`.editor-tab[data-file="${activeTab.replace(/"/g, '&quot;')}"]`);
                const tabName = tab.find(".tab-name");
                if (!tabName.text().includes("*")) {
                    tabName.text(openFiles[activeTab].name + " *");
                }
            }
        });
        
        // Update tab name when file is saved
        function updateTabName(filePath) {
            if (openFiles[filePath]) {
                const tab = $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`);
                tab.find(".tab-name").text(openFiles[filePath].name);
            }
        }

        // Save current file
        function saveCode(){
            if (!activeTab) {
                showNotify("⚠️ No file open to save!", "error");
                return;
            }
            
            const content = $("#code").val();
            const filePath = activeTab;
            
            $.post("file_operations.php", {
                action: 'save',
                file: filePath,
                content: content,
                workspace: currentWorkspace
            }, function(response) {
                if (response.error) {
                    showNotify("❌ " + response.error, "error");
                } else {
                    showNotify("✅ File saved successfully!", "success");
                    if (openFiles[filePath]) {
                        openFiles[filePath].modified = false;
                        openFiles[filePath].content = content;
                        updateTabName(filePath);
                    }
                }
            }, 'json').fail(function() {
                showNotify("❌ Failed to save file", "error");
            });
        }

        function refreshCode(){
            // Refresh is not needed for file-based system
            // But keep for backward compatibility
        }

        function copyCode(){
            navigator.clipboard.writeText($("#code").val());
            showNotify("📋 Code copied to clipboard", "info");
        }

        function deleteMsg(id){
            if(confirm("Confirm deletion of this record?")){
                $.get("delete.php?id="+id, () => {
                    loadMessages();
                    loadFiles();
                    showNotify("🗑️ Message removed", "info");
                });
            }
        }

        // Chat Validation
        $("#chatForm").submit(function(e){
            e.preventDefault();
            let name = $("#username").val().trim();
            let msg = $("#msgInput").val().trim();
            let file = $("#fileInput").val();

            if(name == ""){
                showNotify("❌ Please enter your name", "error");
                return;
            }
            if(msg == "" && file == ""){
                showNotify("❌ Message or File is required", "error");
                return;
            }

            let formData = new FormData(this);
            formData.append('workspace', currentWorkspace);

            $.ajax({
                url: "send.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: () => { 
                    this.reset(); 
                    loadMessages(); 
                    loadFiles();
                    showNotify("📩 Message Sent", "success");
                }
            });
        });


        // Resizable Sidebars
        function makeResizable(resizer, sidebar, isLeft) {
            let isResizing = false;
            
            resizer.addEventListener('mousedown', (e) => {
                isResizing = true;
                resizer.classList.add('resizing');
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', stopResize);
            });

            function handleMouseMove(e) {
                if (!isResizing) return;
                
                const rect = sidebar.getBoundingClientRect();
                let newWidth;
                
                if (isLeft) {
                    newWidth = e.clientX - rect.left;
                } else {
                    newWidth = rect.right - e.clientX;
                }
                
                // Min and max widths
                newWidth = Math.max(200, Math.min(600, newWidth));
                sidebar.style.width = newWidth + 'px';
            }

            function stopResize() {
                isResizing = false;
                resizer.classList.remove('resizing');
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', stopResize);
            }
        }

        // Initialize resizers
        makeResizable(document.getElementById('leftResizer'), document.getElementById('leftSidebar'), true);
        makeResizable(document.getElementById('rightResizer'), document.getElementById('rightSidebar'), false);

        // AI Chat Functions
        function formatCodeBlocks(text) {
            marked.setOptions({
                highlight: function(code, lang) {
                    if (lang && hljs.getLanguage(lang)) {
                        try {
                            return hljs.highlight(code, { language: lang }).value;
                        } catch (err) {}
                    }
                    return hljs.highlightAuto(code).value;
                },
                breaks: true
            });
            
            let html = marked.parse(text);
            
            html = html.replace(/<pre><code class="language-(\w+)">/g, function(match, lang) {
                return `<div class="relative group"><button onclick="copyCodeBlock(this)" class="absolute top-2 right-2 bg-slate-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10">Copy</button><pre><code class="language-${lang}">`;
            });
            
            return html;
        }
        
        function copyCodeBlock(button) {
            const codeBlock = button.nextElementSibling;
            const text = codeBlock.textContent || codeBlock.innerText;
            navigator.clipboard.writeText(text).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy';
                }, 2000);
            });
        }
        
        window.copyCodeBlock = copyCodeBlock;
        
        function insertCodeIntoEditor(code) {
            const codeEditor = document.getElementById('code');
            const cursorPos = codeEditor.selectionStart;
            const textBefore = codeEditor.value.substring(0, cursorPos);
            const textAfter = codeEditor.value.substring(cursorPos);
            
            codeEditor.value = textBefore + code + textAfter;
            codeEditor.focus();
            codeEditor.setSelectionRange(cursorPos + code.length, cursorPos + code.length);
            codeEditor.dispatchEvent(new Event('input', { bubbles: true }));
            
            showNotify("✅ Code inserted into editor", "success");
        }

        window.insertCodeIntoEditor = insertCodeIntoEditor;

        $("#aiChatForm").submit(function(e) {
            e.preventDefault();
            let msg = $("#aiInput").val().trim();
            if (msg === "") return;

            let currentCode = $("#code").val();

            // Add user message
            $("#aiChatMessages").append(`
                <div class="ai-message user">
                    <div style="font-weight: 600; margin-bottom: 4px;">You</div>
                    <div>${$('<div>').text(msg).html()}</div>
                </div>
            `);
            
            $("#aiInput").val("");
            $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

            // Add loading indicator
            let loadingId = 'loading-' + Date.now();
            $("#aiChatMessages").append(`
                <div id="${loadingId}" class="ai-message assistant">
                    <div style="font-style: italic; opacity: 0.7;">
                        <span class="inline-block animate-pulse">💭 Analyzing your code...</span>
                    </div>
                </div>
            `);
            $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

            // Call API
            $.ajax({
                url: "ai_chat.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ 
                    message: msg,
                    currentCode: currentCode
                }),
                success: function(response) {
                    $(`#${loadingId}`).remove();
                    
                    let reply = "Sorry, I couldn't get a response.";
                    if (response.error) {
                        if (typeof response.error === 'object' && response.error.message) {
                            reply = "Error: " + response.error.message;
                        } else {
                            reply = "Error: " + response.error;
                        }
                    } else if (Array.isArray(response) && response.length > 0 && response[0].generated_text) {
                        reply = response[0].generated_text;
                    } else if (response.candidates && response.candidates.length > 0) {
                        reply = response.candidates[0].content.parts[0].text;
                    } else if (response.choices && response.choices.length > 0) {
                        reply = response.choices[0].message.content;
                    }

                    let formattedReply = formatCodeBlocks(reply);
                    
                    let codeBlocks = [];
                    const codeBlockRegex = /```[\s\S]*?```/g;
                    let match;
                    while ((match = codeBlockRegex.exec(reply)) !== null) {
                        let code = match[0].replace(/```\w*\n?/g, '').replace(/```/g, '').trim();
                        if (code.length > 0) {
                            codeBlocks.push(code);
                        }
                    }

                    let messageId = 'msg-' + Date.now();
                    let messageHtml = `
                        <div id="${messageId}" class="ai-message assistant">
                            <div style="font-weight: 600; margin-bottom: 4px; color: #4ec9b0;">AI Assistant</div>
                            <div class="prose prose-sm max-w-none" style="color: var(--vscode-text);">
                                ${formattedReply}
                            </div>
                        </div>
                    `;
                    
                    $("#aiChatMessages").append(messageHtml);
                    
                    if (codeBlocks.length > 0) {
                        codeBlocks.forEach((code, index) => {
                            let buttonId = 'insert-btn-' + messageId + '-' + index;
                            let buttonHtml = `
                                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--vscode-border);">
                                    <button id="${buttonId}" 
                                            class="chat-button" style="width: 100%;">
                                        📋 Insert Code into Editor
                                    </button>
                                </div>
                            `;
                            $(`#${messageId}`).append(buttonHtml);
                            
                            $(`#${buttonId}`).on('click', function() {
                                insertCodeIntoEditor(code);
                            });
                        });
                    }
                    
                    $(`#${messageId} pre code`).each(function() {
                        hljs.highlightElement(this);
                    });
                    
                    $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);
                },
                error: function() {
                    $(`#${loadingId}`).remove();
                    showNotify("❌ Failed to contact AI", "error");
                }
            });
        });

        // Update cursor position in status bar
        function updateCursorPosition() {
            const text = $("#code").val();
            const cursorPos = $("#code")[0].selectionStart;
            const textBeforeCursor = text.substring(0, cursorPos);
            const lines = textBeforeCursor.split('\n');
            const line = lines.length;
            const col = lines[lines.length - 1].length + 1;
            $(".statusbar-item:contains('Ln')").html(`Ln ${line}, Col ${col}`);
        }
        
        $("#code").on("input keyup click", function() {
            updateCursorPosition();
        });

        // Keyboard shortcuts
        $(document).on("keydown", function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                saveCode();
            }
        });

        // Initialize
        setInterval(loadMessages, 3000);
        setInterval(loadFiles, 5000);
        loadMessages();
        loadFiles();
        
        // Make deleteFile available globally
        window.deleteFile = deleteFile;
    </script>
</body>
</html>
