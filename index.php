<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Studio Code - Web</title>

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.3.0/css/xterm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --vscode-bg: #1e1e1e;
            --vscode-sidebar-bg: #252526;
            --vscode-activitybar-bg: #333333;
            --vscode-titlebar-bg: #3c3c3c;
            --vscode-statusbar-bg: #007acc;
            --vscode-panel-bg: #1e1e1e;
            --vscode-border: #474747;
            --vscode-text: #cccccc;
            --vscode-text-secondary: #969696;
            --vscode-hover: #2a2d2e;
            --vscode-selection: #37373d;
            --vscode-activitybar-active: #ffffff;
            --vscode-activitybar-inactive: #808080;
            --vscode-tab-bg: #2d2d2d;
            --vscode-tab-active-bg: #1e1e1e;
            --vscode-menu-bg: #3c3c3c;
            --vscode-menu-hover: #094771;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--vscode-bg);
            color: var(--vscode-text);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            font-size: 13px;
        }

        /* Title Bar */
        #titlebar {
            height: 30px;
            background-color: var(--vscode-titlebar-bg);
            display: flex;
            align-items: center;
            padding: 0 10px;
            user-select: none;
            justify-content: space-between;
        }

        .menubar {
            display: flex;
            gap: 4px;
            position: relative;
        }

        .menu-item {
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .window-title {
            color: var(--vscode-text-secondary);
            font-size: 12px;
        }

        /* Dropdown Menus */
        .dropdown {
            position: absolute;
            top: 25px;
            left: 0;
            background-color: var(--vscode-menu-bg);
            border: 1px solid var(--vscode-border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            display: none;
            min-width: 220px;
            padding: 4px 0;
        }

        .dropdown-item {
            padding: 4px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: var(--vscode-menu-hover);
            color: white;
        }

        .shortcut {
            color: var(--vscode-text-secondary);
            font-size: 11px;
            margin-left: 10px;
        }

        .separator {
            height: 1px;
            background-color: var(--vscode-border);
            margin: 4px 0;
        }

        /* Main Workspace */
        #workbench {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        /* Activity Bar */
        #activitybar {
            width: 48px;
            background-color: var(--vscode-activitybar-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 10px;
            gap: 15px;
        }

        .activity-icon {
            font-size: 24px;
            color: var(--vscode-activitybar-inactive);
            cursor: pointer;
            padding: 10px 0;
            width: 100%;
            text-align: center;
            border-left: 2px solid transparent;
        }

        .activity-icon:hover {
            color: var(--vscode-activitybar-active);
        }

        .activity-icon.active {
            color: var(--vscode-activitybar-active);
            border-left-color: var(--vscode-activitybar-active);
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            background-color: var(--vscode-sidebar-bg);
            border-right: 1px solid var(--vscode-border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-title {
            padding: 10px 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: var(--vscode-text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .sidebar-title:hover .sidebar-actions {
            opacity: 1;
        }

        .action-btn {
            cursor: pointer;
            margin-left: 5px;
        }

        #file-explorer {
            flex: 1;
            overflow-y: auto;
        }

        .explorer-item {
            padding: 4px 10px 4px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .explorer-item:hover {
            background-color: var(--vscode-hover);
        }

        .explorer-item i {
            width: 16px;
            text-align: center;
        }

        .fa-folder {
            color: #dcb67a;
        }

        .fa-file-code {
            color: #4d9375;
        }

        .fa-php {
            color: #777bb3;
        }

        .fa-js {
            color: #f1e05a;
        }

        .fa-html5 {
            color: #e34c26;
        }

        .fa-css3-alt {
            color: #563d7c;
        }

        .fa-terminal {
            color: #cccccc;
        }

        /* Editor Area */
        #editor-groups {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--vscode-bg);
        }

        /* Tabs */
        #tabs-container {
            height: 35px;
            background-color: var(--vscode-sidebar-bg);
            display: flex;
            align-items: flex-end;
            overflow-x: auto;
        }

        .tab {
            padding: 8px 15px;
            background-color: var(--vscode-tab-bg);
            border-right: 1px solid var(--vscode-border);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            color: var(--vscode-text-secondary);
            border-top: 2px solid transparent;
        }

        .tab.active {
            background-color: var(--vscode-tab-active-bg);
            color: var(--vscode-text);
            border-top-color: var(--vscode-statusbar-bg);
        }

        .tab-close {
            opacity: 0;
            font-size: 11px;
        }

        .tab:hover .tab-close {
            opacity: 1;
        }

        /* Monaco Editor Container */
        #monaco-editor-container {
            flex: 1;
            position: relative;
        }

        /* Panel (Terminal) */
        #panel {
            height: 200px;
            border-top: 1px solid var(--vscode-border);
            background-color: var(--vscode-panel-bg);
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            display: flex;
            gap: 20px;
            padding: 8px 15px;
            border-bottom: 1px solid var(--vscode-border);
            font-size: 11px;
            text-transform: uppercase;
        }

        .panel-tab {
            cursor: pointer;
            color: var(--vscode-text-secondary);
        }

        .panel-tab.active {
            color: var(--vscode-text);
            border-bottom: 1px solid var(--vscode-text);
        }

        #terminal-container {
            flex: 1;
            padding: 5px;
            overflow: hidden;
            background: #1e1e1e;
        }

        /* Status Bar */
        #statusbar {
            height: 22px;
            background-color: var(--vscode-statusbar-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
            color: white;
            font-size: 12px;
        }

        .statusbar-item {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            padding: 0 5px;
        }

        .statusbar-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Utilities */
        .hidden {
            display: none !important;
        }

        /* Sidebar Views */
        .sidebar-view {
            display: none;
            flex: 1;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
        }

        .sidebar-view.active {
            display: flex;
        }

        .sidebar-input-container {
            padding: 10px;
        }

        .sidebar-input {
            width: 100%;
            background-color: #3c3c3c;
            border: 1px solid #3c3c3c;
            color: #cccccc;
            padding: 4px;
            font-size: 12px;
            outline: none;
        }

        .sidebar-input:focus {
            border-color: #007acc;
        }

        .sidebar-section-title {
            padding: 5px 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .sidebar-section-title:hover {
            background-color: #2a2d2e;
        }

        /* AI Assistant Panel */
        #ai-panel {
            position: fixed;
            right: -400px;
            top: 30px;
            bottom: 22px;
            width: 400px;
            background-color: var(--vscode-sidebar-bg);
            border-left: 1px solid var(--vscode-border);
            display: flex;
            flex-direction: column;
            transition: right 0.3s ease;
            z-index: 1000;
        }

        #ai-panel.open {
            right: 0;
        }

        .ai-header {
            padding: 10px 15px;
            background-color: var(--vscode-titlebar-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--vscode-border);
        }

        .ai-header h3 {
            margin: 0;
            font-size: 13px;
            color: var(--vscode-text);
        }

        .ai-close {
            cursor: pointer;
            color: var(--vscode-text-secondary);
        }

        .ai-close:hover {
            color: var(--vscode-text);
        }

        .ai-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .ai-messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .ai-message {
            margin-bottom: 15px;
            padding: 8px;
            border-radius: 4px;
        }

        .ai-message.user {
            background-color: #094771;
            text-align: right;
        }

        .ai-message.assistant {
            background-color: var(--vscode-hover);
        }

        .ai-message pre {
            background-color: #1e1e1e;
            padding: 8px;
            border-radius: 3px;
            overflow-x: auto;
            margin: 5px 0;
        }

        .ai-input-area {
            padding: 10px;
            border-top: 1px solid var(--vscode-border);
        }

        .ai-input {
            width: 100%;
            background-color: var(--vscode-bg);
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text);
            padding: 8px;
            font-size: 12px;
            resize: vertical;
            min-height: 60px;
            font-family: 'Segoe UI', sans-serif;
        }

        .ai-input:focus {
            outline: none;
            border-color: #007acc;
        }

        .ai-send-btn {
            margin-top: 5px;
            background-color: #007acc;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 12px;
            border-radius: 2px;
        }

        .ai-send-btn:hover {
            background-color: #005a9e;
        }

        .ai-send-btn:disabled {
            background-color: #555;
            cursor: not-allowed;
        }

        .ai-loading {
            text-align: center;
            padding: 10px;
            color: var(--vscode-text-secondary);
        }
    </style>
</head>

<body>

    <!-- Title Bar -->
    <div id="titlebar">
        <div class="menubar">
            <img src="logo/vscode.png" height="16" alt="Icon" style="margin-right: 5px;">
            <div class="menu-item" data-menu="file">File</div>
            <div class="menu-item" data-menu="edit">Edit</div>
            <div class="menu-item" data-menu="selection">Selection</div>
            <div class="menu-item" data-menu="view">View</div>
            <div class="menu-item" data-menu="go">Go</div>
            <div class="menu-item" data-menu="run">Run</div>
            <div class="menu-item" data-menu="terminal">Terminal</div>
            <div class="menu-item" data-menu="help">Help</div>

            <!-- File Menu Dropdown -->
            <div class="dropdown" id="menu-file">
                <div class="dropdown-item" onclick="createNewFile()">New Text File <span class="shortcut">Ctrl+N</span>
                </div>
                <div class="dropdown-item" onclick="createNewFolder()">New Folder</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="document.getElementById('file-upload').click()">Open File...</div>
                <div class="dropdown-item" onclick="alert('Open Folder: Not implemented in browser environment')">Open
                    Folder...</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="saveFile()">Save <span class="shortcut">Ctrl+S</span></div>
                <div class="dropdown-item" onclick="saveFileAs()">Save As... <span class="shortcut">Ctrl+Shift+S</span>
                </div>
                <div class="dropdown-item" onclick="toggleAutoSave()">Auto Save <span id="autosave-indicator"></span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="closeFile()">Close Editor <span class="shortcut">Ctrl+F4</span>
                </div>
                <div class="dropdown-item" onclick="closeWorkspace()">Close Workspace</div>
            </div>
            <input type="file" id="file-upload" style="display:none" onchange="handleFileUpload(event)" multiple>

            <!-- Edit Menu Dropdown -->
            <div class="dropdown" id="menu-edit">
                <div class="dropdown-item" onclick="triggerEdit('undo')">Undo <span class="shortcut">Ctrl+Z</span></div>
                <div class="dropdown-item" onclick="triggerEdit('redo')">Redo <span class="shortcut">Ctrl+Y</span></div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('cut')">Cut <span class="shortcut">Ctrl+X</span></div>
                <div class="dropdown-item" onclick="triggerEdit('copy')">Copy <span class="shortcut">Ctrl+C</span></div>
                <div class="dropdown-item" onclick="triggerEdit('paste')">Paste <span class="shortcut">Ctrl+V</span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('find')">Find <span class="shortcut">Ctrl+F</span></div>
                <div class="dropdown-item" onclick="triggerEdit('replace')">Replace <span class="shortcut">Ctrl+H</span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('comment')">Toggle Line Comment <span
                        class="shortcut">Ctrl+/</span></div>
                <div class="dropdown-item" onclick="triggerEdit('format')">Format Document <span
                        class="shortcut">Shift+Alt+F</span></div>
            </div>

            <!-- Selection Menu Dropdown -->
            <div class="dropdown" id="menu-selection">
                <div class="dropdown-item" onclick="triggerSelection('all')">Select All <span
                        class="shortcut">Ctrl+A</span></div>
                <div class="dropdown-item" onclick="triggerSelection('expand')">Expand Selection <span
                        class="shortcut">Shift+Alt+Right</span></div>
            </div>

            <!-- View Menu Dropdown -->
            <div class="dropdown" id="menu-view">
                <div class="dropdown-item" onclick="togglePanel()">Toggle Terminal <span class="shortcut">Ctrl+`</span>
                </div>
                <div class="dropdown-item" onclick="toggleSidebar()">Toggle Sidebar <span class="shortcut">Ctrl+B</span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerView('minimap')">Toggle Minimap</div>
                <div class="dropdown-item" onclick="triggerView('wordwrap')">Toggle Word Wrap <span
                        class="shortcut">Alt+Z</span></div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerView('zoomin')">Zoom In <span class="shortcut">Ctrl+=</span>
                </div>
                <div class="dropdown-item" onclick="triggerView('zoomout')">Zoom Out <span
                        class="shortcut">Ctrl+-</span></div>
                <div class="dropdown-item" onclick="triggerView('zoomreset')">Reset Zoom</div>
            </div>

            <!-- Go Menu Dropdown -->
            <div class="dropdown" id="menu-go">
                <div class="dropdown-item" onclick="triggerGo('file')">Go to File... <span
                        class="shortcut">Ctrl+P</span></div>
                <div class="dropdown-item" onclick="triggerGo('line')">Go to Line... <span
                        class="shortcut">Ctrl+G</span></div>
            </div>

            <!-- Run Menu Dropdown -->
            <div class="dropdown" id="menu-run">
                <div class="dropdown-item" onclick="executeRun()">Start Debugging <span class="shortcut">F5</span></div>
                <div class="dropdown-item" onclick="executeRun()">Run Without Debugging <span
                        class="shortcut">Ctrl+F5</span></div>
            </div>

            <!-- Terminal Menu Dropdown -->
            <div class="dropdown" id="menu-terminal">
                <div class="dropdown-item" onclick="togglePanel()">New Terminal <span
                        class="shortcut">Ctrl+Shift+`</span></div>
                <div class="dropdown-item" onclick="executeCommand('cls')">Clear Terminal</div>
            </div>

            <!-- Help Menu Dropdown -->
            <div class="dropdown" id="menu-help">
                <div class="dropdown-item" onclick="triggerHelp()">About</div>
                <div class="dropdown-item"
                    onclick="alert('Accessibility Options: \nUse Alt+F1 for Screen Reader Access.\nHigh Contrast Theme is available in settings.')">
                    Accessibility Options</div>
            </div>

        </div>
        <div class="window-title">Web Code Workspace</div>
        <div class="window-controls">
            <i class="fas fa-window-minimize" style="padding: 0 8px;"></i>
            <i class="fas fa-window-maximize" style="padding: 0 8px;"></i>
            <i class="fas fa-times" style="padding: 0 8px;"></i>
        </div>
    </div>

    <!-- Main Workbench -->
    <div id="workbench">
        <!-- Activity Bar -->
        <div id="activitybar">
            <div class="activity-icon active" title="Explorer" onclick="switchSidebar('explorer')"><i
                    class="far fa-copy"></i></div>
            <div class="activity-icon" title="Search" onclick="switchSidebar('search')"><i class="fas fa-search"></i>
            </div>
            <div class="activity-icon" title="Source Control" onclick="switchSidebar('scm')"><i
                    class="fas fa-code-branch"></i></div>
            <div class="activity-icon" title="Run and Debug" onclick="switchSidebar('run')"><i class="fas fa-play"></i>
            </div>
            <div class="activity-icon" title="Extensions" onclick="switchSidebar('extensions')"><i
                    class="fas fa-th-large"></i></div>
            <div style="flex: 1;"></div>
            <div class="activity-icon" title="Settings"><i class="fas fa-cog"></i></div>
        </div>

        <!-- Sidebar -->
        <div id="sidebar">
            <div class="sidebar-title">
                <span id="sidebar-title-text">Explorer</span>
                <div class="sidebar-actions">
                    <i class="fas fa-ellipsis-h action-btn"></i>
                </div>
            </div>

            <!-- Explorer View -->
            <div id="view-explorer" class="sidebar-view active">
                <div
                    style="padding: 0px 0px 10px 20px; font-weight: bold; font-size: 11px; display:flex; justify-content:space-between; align-items:center;">
                    <span>WORKSPACE</span>
                    <div style="padding-right:10px;">
                        <i class="fas fa-file-plus action-btn" title="New File" onclick="createNewFile()"></i>
                        <i class="fas fa-folder-plus action-btn" title="New Folder" onclick="createNewFolder()"></i>
                        <i class="fas fa-sync action-btn" title="Refresh" onclick="loadExplorer()"></i>
                    </div>
                </div>
                <div id="file-explorer" style="flex:1; overflow-y:auto;">
                    <div style="padding: 10px; color: #aaa; text-align: center;">Loading...</div>
                </div>
            </div>

            <!-- Search View -->
            <div id="view-search" class="sidebar-view">
                <div class="sidebar-input-container">
                    <input type="text" class="sidebar-input" placeholder="Search"
                        onkeyup="alert('Search logic to be implemented')">
                </div>
                <div style="padding: 10px; text-align: center; color: #777;">
                    No results found.
                </div>
            </div>

            <!-- Source Control View -->
            <div id="view-scm" class="sidebar-view">
                <div style="padding: 20px 10px; text-align: center;">
                    <p style="margin-bottom: 10px;">No source control providers registered.</p>
                </div>
            </div>

            <!-- Run View -->
            <div id="view-run" class="sidebar-view">
                <div style="padding: 10px;">
                    <button
                        style="width: 100%; background: #007acc; color: white; border: none; padding: 5px; cursor: pointer;"
                        onclick="executeRun()">Run and Debug</button>
                </div>
            </div>

            <!-- Extensions View -->
            <div id="view-extensions" class="sidebar-view">
                <div class="sidebar-input-container">
                    <input type="text" class="sidebar-input" placeholder="Search Extensions in Marketplace">
                </div>
            </div>
        </div>

        <!-- Editor Groups -->
        <div id="editor-groups">
            <div id="tabs-container">
                <div class="tab active">
                    <i class="fab fa-php" style="color: #777bb3;"></i>
                    <span>Welcome</span>
                    <span class="tab-close" onclick="closeFile(event)"><i class="fas fa-times"></i></span>
                </div>
            </div>
            <div id="monaco-editor-container"></div>

            <!-- Panel -->
            <div id="panel">
                <div class="panel-header">
                    <div class="panel-tab active">Terminal</div>
                    <div class="panel-tab" onclick="alert('Output channel empty')">Output</div>
                    <div class="panel-tab">Debug Console</div>
                </div>
                <div id="terminal-container"></div>
            </div>
        </div>
    </div>

    <!-- Status Bar -->
    <div id="statusbar">
        <div style="display: flex;">
            <div class="statusbar-item"><i class="fas fa-code-branch"></i> main*</div>
            <div class="statusbar-item"><i class="far fa-times-circle"></i> 0</div>
            <div class="statusbar-item"><i class="fas fa-exclamation-triangle"></i> 0</div>
        </div>
        <div style="display: flex;">
            <div class="statusbar-item" id="status-cursor">Ln 1, Col 1</div>
            <div class="statusbar-item">UTF-8</div>
            <div class="statusbar-item" id="status-lang">PHP</div>
            <div class="statusbar-item"><i class="fas fa-rss"></i> Go Live</div>
        </div>
    </div>

    <!-- Application Logic -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Postload Monaco -->
    <script>var require = { paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } };</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/editor/editor.main.nls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/editor/editor.main.js"></script>
    <!-- xterm.js -->
    <script src="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.3.0/lib/xterm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@xterm/addon-fit@0.8.0/lib/addon-fit.min.js"></script>

    <script>
        // Global variables
        var editor;
        var term;
        var currentFile = '';
        var currentCwd = '<?php echo str_replace("\\", "/", getcwd()); ?>';
        var editorFontSize = 14;
        var minimapEnabled = true;
        var wordWrapEnabled = false;

        $(document).ready(function () {
            initLayout();
            initMonaco();
            initTerminal();
            loadExplorer();

            // Menu Bindings
            $('.menu-item').click(function (e) {
                e.stopPropagation();
                $('.dropdown').hide();
                let menu = $(this).data('menu');
                // Calculate position relative to the clicked item
                let offset = $(this).offset();
                $('#menu-' + menu).css({
                    top: (offset.top + 30) + 'px',
                    left: offset.left + 'px'
                }).toggle();
            });

            $(document).click(function () {
                $('.dropdown').hide();
            });
        });

        function switchSidebar(viewName) {
            // Update Activity Bar
            $('.activity-icon').removeClass('active');
            // Assuming order: explorer, search, scm, run, extensions
            let index = 0;
            let title = 'Explorer';
            if (viewName === 'search') { index = 1; title = 'Search'; }
            if (viewName === 'scm') { index = 2; title = 'Source Control'; }
            if (viewName === 'run') { index = 3; title = 'Run and Debug'; }
            if (viewName === 'extensions') { index = 4; title = 'Extensions'; }

            $('.activity-icon').eq(index).addClass('active');

            // Update Sidebar Content
            $('.sidebar-view').removeClass('active');
            $('#view-' + viewName).addClass('active');

            // Update Title
            $('#sidebar-title-text').text(title.toUpperCase());
        }

        function initLayout() {
            // Basic resizing logic can be added here
        }

        function initMonaco() {
            require(['vs/editor/editor.main'], function () {
                editor = monaco.editor.create(document.getElementById('monaco-editor-container'), {
                    value: "<?php echo 'Welcome to Web Code Workspace\n\n// Create a new file or open one from the explorer'; ?>",
                    language: 'php',
                    theme: 'vs-dark',
                    automaticLayout: true,
                    minimap: { enabled: true },
                    fontSize: editorFontSize,
                    lineNumbers: 'on',
                    scrollBeyondLastLine: false,
                    roundedSelection: false,
                });

                // Add keybinding for Save
                editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, function () {
                    saveFile();
                });

                editor.onDidChangeCursorPosition((e) => {
                    $('#status-cursor').text('Ln ' + e.position.lineNumber + ', Col ' + e.position.column);
                });
            });
        }

        function initTerminal() {
            term = new Terminal({
                cursorBlink: true,
                fontFamily: 'Consolas, monospace',
                fontSize: 14,
                theme: {
                    background: '#1e1e1e'
                }
            });
            const fitAddon = new FitAddon.FitAddon();
            term.loadAddon(fitAddon);
            term.open(document.getElementById('terminal-container'));
            fitAddon.fit();

            term.writeln('\x1b[1;34mWelcome to PHP Web Terminal\x1b[0m');
            term.write('\r\n' + currentCwd + '> ');

            // Resize listener
            window.addEventListener('resize', () => fitAddon.fit());

            // Handle Input
            let currentLine = '';
            term.onData(e => {
                switch (e) {
                    case '\r': // Enter
                        term.write('\r\n');
                        executeCommand(currentLine);
                        currentLine = '';
                        break;
                    case '\u007F': // Backspace
                        if (currentLine.length > 0) {
                            term.write('\b \b');
                            currentLine = currentLine.substring(0, currentLine.length - 1);
                        }
                        break;
                    default:
                        // Basic printable character check
                        if (e >= ' ' && e <= '~') {
                            currentLine += e;
                            term.write(e);
                        }
                }
            });
        }

        function executeCommand(cmd) {
            if (!cmd.trim()) {
                term.write(currentCwd + '> ');
                return;
            }

            if (cmd.trim() === 'cls' || cmd.trim() === 'clear') {
                term.clear();
                term.write(currentCwd + '> ');
                return;
            }

            // Call backend
            $.ajax({
                url: 'terminal.php',
                method: 'POST',
                data: JSON.stringify({ command: cmd, cwd: currentCwd }),
                contentType: 'application/json',
                success: function (res) {
                    if (res.output) {
                        // Fix line endings for xterm
                        let out = res.output.replace(/\n/g, '\r\n');
                        term.write(out);
                    }
                    if (res.cwd) {
                        currentCwd = res.cwd;
                    }
                    term.write(currentCwd + '> ');
                },
                error: function (err) {
                    term.write('\r\nError communicating with server.\r\n' + currentCwd + '> ');
                }
            });
        }

        function executeRun() {
            if (currentFile && currentFile.endsWith('.php')) {
                // Since this is web, running PHP technically means visiting the page or running generic CLI.
                // Let's run CLI for now.
                term.write('php ' + currentFile + '\r\n');
                executeCommand('php ' + currentFile);
            } else {
                alert('Run is only supported for PHP files in this terminal context.');
            }
        }

        function togglePanel() {
            let p = $('#panel');
            if (p.hasClass('hidden')) {
                p.removeClass('hidden');
                p.css('display', 'flex');
            } else {
                p.addClass('hidden');
                p.css('display', 'none');
            }
        }

        function toggleSidebar() {
            let s = $('#sidebar');
            if (s.css('display') === 'none') {
                s.css('display', 'flex');
            } else {
                s.css('display', 'none');
            }
        }

        function loadExplorer() {
            $.get('file_manager.php', { action: 'list' }, function (data) {
                $('#file-explorer').empty();
                if (Array.isArray(data)) {
                    renderTree(data, $('#file-explorer'));
                } else {
                    console.error("Invalid data received from explorer:", data);
                    $('#file-explorer').html('<div style="color:red; margin:10px;">Error loading files</div>');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Explorer Request Failed:", textStatus, errorThrown);
                $('#file-explorer').html('<div style="color:red; margin:10px;">Connection Failed</div>');
            });
        }

        function renderTree(nodes, container, level = 0) {
            nodes.forEach(node => {
                let padding = level * 15 + 20;
                let item = $('<div class="explorer-item" style="padding-left:' + padding + 'px"></div>');

                let iconClass = node.type === 'folder' ? 'fa-folder' : 'fa-file-code';
                if (node.name.endsWith('.php')) iconClass = 'fa-php';
                if (node.name.endsWith('.js')) iconClass = 'fa-js';
                if (node.name.endsWith('.html')) iconClass = 'fa-html5';
                if (node.name.endsWith('.css')) iconClass = 'fa-css3-alt';
                if (node.name === 'terminal.php') iconClass = 'fa-terminal';

                item.html('<i class="fas ' + iconClass + '"></i> ' + node.name);

                // Add click handler
                item.click(function (e) {
                    e.stopPropagation();
                    // Basic selection visual
                    $('.explorer-item').css('background-color', '');
                    $(this).css('background-color', '#37373d');

                    if (node.type === 'file') {
                        openFile(node.path);
                    } else {
                        // Toggle folder logic would go here
                        // For now just expanding is not implemented in this simple recursor
                    }
                });

                container.append(item);

                if (node.type === 'folder' && node.children) {
                    renderTree(node.children, container, level + 1);
                }
            });
        }

        function createNewFile() {
            let name = prompt("Enter new file name (e.g. test.php):");
            if (name) {
                $.post('file_manager.php', { action: 'create_file', path: name }, function (res) {
                    if (res.success) {
                        loadExplorer();
                        openFile(name);
                    } else {
                        alert(res.error);
                    }
                }, 'json');
            }
        }

        function createNewFolder() {
            let name = prompt("Enter new folder name:");
            if (name) {
                $.post('file_manager.php', { action: 'create_folder', path: name }, function (res) {
                    if (res.success) {
                        loadExplorer();
                    } else {
                        alert(res.error);
                    }
                }, 'json');
            }
        }

        function openFile(path) {
            currentFile = path;
            // Update tabs
            $('.tab span:first-child').next().text(path);
            $('.window-title').text(path + ' - Web Code Workspace');

            $.post('file_manager.php', { action: 'read', path: path }, function (res) {
                if (res.error) {
                    alert(res.error);
                    return;
                }
                if (editor) {
                    let model = editor.getModel();
                    // Map extension to language
                    let ext = path.split('.').pop();
                    let lang = 'plaintext';
                    if (ext === 'php') lang = 'php';
                    if (ext === 'js') lang = 'javascript';
                    if (ext === 'html') lang = 'html';
                    if (ext === 'css') lang = 'css';
                    if (ext === 'json') lang = 'json';
                    if (ext === 'md') lang = 'markdown';

                    monaco.editor.setModelLanguage(model, lang);
                    editor.setValue(res.content);
                    $('#status-lang').text(lang.toUpperCase());
                }
            }, 'json');
        }

        function saveFile() {
            if (!editor || !currentFile) return;
            let content = editor.getValue();

            // Visual feedback
            $('.tab').css('border-top-color', '#eebb00'); // saving color

            $.post('file_manager.php', { action: 'write', path: currentFile, content: content }, function (res) {
                if (res.success) {
                    // Success feedback
                    $('.tab').css('border-top-color', '#007acc');
                } else {
                    alert('Error saving: ' + res.error);
                    $('.tab').css('border-top-color', 'red');
                }
            }, 'json');
        }

        function closeFile(e) {
            if (e) e.stopPropagation();
            if (editor) {
                editor.setValue('');
                currentFile = '';
                $('.tab span:first-child').next().text('No File');
                $('.window-title').text('Web Code Workspace');
            }
        }

        /* Menu Actions */
        function triggerEdit(action) {
            if (!editor) return;
            editor.focus();
            if (action === 'undo') editor.trigger('menu', 'undo');
            if (action === 'redo') editor.trigger('menu', 'redo');
            if (action === 'cut') {
                alert('For Cut/Copy/Paste, please use keyboard shortcuts (Ctrl+X/C/V) due to browser security.');
            }
            if (action === 'copy') alert('Use Ctrl+C');
            if (action === 'paste') alert('Use Ctrl+V');
            if (action === 'find') editor.trigger('menu', 'actions.find');
            if (action === 'replace') editor.trigger('menu', 'editor.action.startFindReplaceAction');
            if (action === 'comment') editor.trigger('menu', 'editor.action.commentLine');
            if (action === 'blockcomment') editor.trigger('menu', 'editor.action.blockComment');
            if (action === 'format') editor.trigger('menu', 'editor.action.formatDocument');
        }

        function triggerSelection(action) {
            if (!editor) return;
            editor.focus();
            if (action === 'all') editor.trigger('menu', 'editor.action.selectAll');
            if (action === 'expand') editor.trigger('menu', 'editor.action.smartSelect.expand');
        }

        function triggerView(action) {
            if (action === 'minimap') {
                minimapEnabled = !minimapEnabled;
                editor.updateOptions({ minimap: { enabled: minimapEnabled } });
            }
            if (action === 'wordwrap') {
                wordWrapEnabled = !wordWrapEnabled;
                editor.updateOptions({ wordWrap: wordWrapEnabled ? 'on' : 'off' });
            }
            if (action === 'zoomin') {
                editorFontSize += 1;
                editor.updateOptions({ fontSize: editorFontSize });
            }
            if (action === 'zoomout') {
                editorFontSize = Math.max(10, editorFontSize - 1);
                editor.updateOptions({ fontSize: editorFontSize });
            }
            if (action === 'zoomreset') {
                editorFontSize = 14;
                editor.updateOptions({ fontSize: editorFontSize });
            }
        }

        function triggerGo(action) {
            if (!editor) return;
            editor.focus();
            if (action === 'line') editor.trigger('menu', 'editor.action.gotoLine');
            if (action === 'file') alert('Quick Open (Ctrl+P) - File picker not yet implemented');
        }

        function triggerHelp() {
            alert('VS Code Web Clone\n\nA lightweight PHP-based cloud IDE mimicking Visual Studio Code.\n\nVersion 1.0.0');
        }

        // AI Assistant Functions
        var autoSaveEnabled = false;
        var autoSaveInterval;

        function toggleAIPanel() {
            $('#ai-panel').toggleClass('open');
        }

        function sendAIQuery() {
            const prompt = $('#ai-input').val().trim();
            if (!prompt) return;

            const selection = editor.getSelection();
            const codeContext = editor.getModel().getValueInRange(selection) || editor.getValue();
            const language = $('#status-lang').text().toLowerCase();

            // Add user message
            $('#ai-messages').append(`<div class="ai-message user">${prompt}</div>`);
            $('#ai-input').val('');
            $('#ai-send-btn').prop('disabled', true);
            $('#ai-messages').append('<div class="ai-loading"><i class="fas fa-spinner fa-spin"></i> Thinking...</div>');

            $.ajax({
                url: 'ai_assistant.php',
                method: 'POST',
                data: JSON.stringify({ prompt, context: codeContext, language }),
                contentType: 'application/json',
                success: function(res) {
                    $('.ai-loading').remove();
                    if (res.success) {
                        const formatted = res.response.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
                        $('#ai-messages').append(`<div class="ai-message assistant">${formatted}</div>`);
                    } else {
                        $('#ai-messages').append(`<div class="ai-message assistant" style="color:red;">Error: ${res.error}</div>`);
                    }
                    $('#ai-messages').scrollTop($('#ai-messages')[0].scrollHeight);
                    $('#ai-send-btn').prop('disabled', false);
                },
                error: function() {
                    $('.ai-loading').remove();
                    $('#ai-messages').append('<div class="ai-message assistant" style="color:red;">Failed to connect to AI service</div>');
                    $('#ai-send-btn').prop('disabled', false);
                }
            });
        }

        // File Operations
        function handleFileUpload(event) {
            const files = event.target.files;
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $.post('file_manager.php', { action: 'write', path: file.name, content: e.target.result }, function(res) {
                        if (res.success) {
                            loadExplorer();
                            openFile(file.name);
                        } else {
                            alert('Error uploading: ' + res.error);
                        }
                    }, 'json');
                };
                reader.readAsText(file);
            });
        }

        function saveFileAs() {
            if (!editor || !currentFile) return;
            const newName = prompt('Save as:', currentFile);
            if (newName) {
                const content = editor.getValue();
                $.post('file_manager.php', { action: 'write', path: newName, content }, function(res) {
                    if (res.success) {
                        currentFile = newName;
                        loadExplorer();
                        alert('Saved as: ' + newName);
                    } else {
                        alert('Error: ' + res.error);
                    }
                }, 'json');
            }
        }

        function toggleAutoSave() {
            autoSaveEnabled = !autoSaveEnabled;
            $('#autosave-indicator').text(autoSaveEnabled ? '✓' : '');
            if (autoSaveEnabled) {
                autoSaveInterval = setInterval(() => {
                    if (editor && currentFile) saveFile();
                }, 5000);
            } else {
                clearInterval(autoSaveInterval);
            }
        }

        function closeWorkspace() {
            if (confirm('Close workspace? Unsaved changes will be lost.')) {
                location.reload();
            }
        }

        // Add Ctrl+I keybinding for AI
        $(document).ready(function() {
            $(document).keydown(function(e) {
                if (e.ctrlKey && e.key === 'i') {
                    e.preventDefault();
                    toggleAIPanel();
                }
            });

            // Enter to send AI query
            $('#ai-input').keydown(function(e) {
                if (e.ctrlKey && e.key === 'Enter') {
                    sendAIQuery();
                }
            });
        });
    </script>

    <!-- AI Assistant Panel -->
    <div id="ai-panel">
        <div class="ai-header">
            <h3><i class="fas fa-robot"></i> AI Assistant (Ctrl+I)</h3>
            <span class="ai-close" onclick="toggleAIPanel()"><i class="fas fa-times"></i></span>
        </div>
        <div class="ai-content">
            <div class="ai-messages" id="ai-messages">
                <div style="padding: 20px; text-align: center; color: #777;">
                    <i class="fas fa-robot" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <p>Ask me anything about your code!</p>
                    <p style="font-size: 11px;">I can help with debugging, explanations, and suggestions.</p>
                </div>
            </div>
            <div class="ai-input-area">
                <textarea id="ai-input" class="ai-input" placeholder="Ask a question about your code..."></textarea>
                <button class="ai-send-btn" onclick="sendAIQuery()" id="ai-send-btn">Send</button>
            </div>
        </div>
    </div>
</body>

</html>