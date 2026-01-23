<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Studio Code - Web</title>

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.3.0/css/xterm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            /* Enhanced Color Palette */
            --vscode-bg: #1a1a1a;
            --vscode-sidebar-bg: #1f1f1f;
            --vscode-activitybar-bg: #181818;
            --vscode-titlebar-bg: #2d2d2d;
            --vscode-statusbar-bg: #0078d4;
            --vscode-panel-bg: #1a1a1a;
            --vscode-border: #3e3e42;
            --vscode-text: #e1e1e6;
            --vscode-text-secondary: #9d9d9d;
            --vscode-hover: #2a2d30;
            --vscode-selection: #264f78;
            --vscode-activitybar-active: #ffffff;
            --vscode-activitybar-inactive: #969696;
            --vscode-tab-bg: #252526;
            --vscode-tab-active-bg: #1a1a1a;
            --vscode-menu-bg: #2d2d30;
            --vscode-menu-hover: #094771;

            /* Modern UI Variables */
            --vscode-accent: #0078d4;
            --vscode-accent-hover: #106ebe;
            --vscode-success: #14ca3c;
            --vscode-warning: #ffcc02;
            --vscode-error: #f14c4c;
            --vscode-shadow: rgba(0, 0, 0, 0.3);
            --vscode-border-radius: 6px;
            --vscode-transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --vscode-font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            --vscode-mono-font: 'Cascadia Code', 'Consolas', 'Monaco', monospace;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--vscode-font-family);
            background-color: var(--vscode-bg);
            color: var(--vscode-text);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            font-size: 13px;
            line-height: 1.4;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Title Bar */
        #titlebar {
            height: 35px;
            background-color: var(--vscode-titlebar-bg);
            display: flex;
            align-items: center;
            padding: 0 8px 0 12px;
            user-select: none;
            justify-content: space-between;
            border-bottom: 1px solid var(--vscode-border);
            backdrop-filter: blur(10px);
        }

        .menubar {
            display: flex;
            gap: 2px;
            position: relative;
        }

        .menu-item {
            cursor: pointer;
            padding: 4px 10px;
            border-radius: var(--vscode-border-radius);
            font-size: 12px;
            font-weight: 500;
            transition: var(--vscode-transition);
            border: 1px solid transparent;
        }

        .menu-item:hover {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
        }

        .menu-item:active {
            background-color: var(--vscode-selection);
        }

        .window-title {
            color: var(--vscode-text-secondary);
            font-size: 12px;
            font-weight: 500;
        }

        .window-controls {
            display: flex;
            gap: 0;
        }

        .window-controls i {
            padding: 8px 12px;
            cursor: pointer;
            transition: var(--vscode-transition);
            border-radius: 0;
        }

        .window-controls i:hover {
            background-color: var(--vscode-hover);
        }

        .window-controls i:first-child:hover {
            background-color: var(--vscode-warning);
            color: var(--vscode-bg);
        }

        .window-controls i:last-child:hover {
            background-color: var(--vscode-error);
            color: white;
        }

        /* Dropdown Menus */
        .dropdown {
            position: absolute;
            top: 35px;
            left: 0;
            background-color: var(--vscode-menu-bg);
            border: 1px solid var(--vscode-border);
            box-shadow: 0 8px 32px var(--vscode-shadow);
            z-index: 1000;
            display: none;
            min-width: 240px;
            padding: 6px 0;
            border-radius: var(--vscode-border-radius);
            backdrop-filter: blur(10px);
            animation: dropdownFadeIn 0.15s ease-out;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 6px 16px 6px 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            transition: var(--vscode-transition);
            border-radius: 3px;
            margin: 0 4px;
        }

        .dropdown-item:hover {
            background-color: var(--vscode-selection);
            color: var(--vscode-text);
        }

        .dropdown-item:active {
            background-color: var(--vscode-hover);
        }

        .shortcut {
            color: var(--vscode-text-secondary);
            font-size: 10px;
            font-family: var(--vscode-mono-font);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 1px 4px;
            border-radius: 3px;
        }

        .separator {
            height: 1px;
            background-color: var(--vscode-border);
            margin: 6px 8px;
            opacity: 0.5;
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
            padding: 8px 0;
            gap: 8px;
            border-right: 1px solid var(--vscode-border);
        }

        .activity-icon {
            font-size: 20px;
            color: var(--vscode-activitybar-inactive);
            cursor: pointer;
            padding: 12px 0;
            width: 100%;
            text-align: center;
            border-left: 3px solid transparent;
            transition: var(--vscode-transition);
            position: relative;
            border-radius: 0 var(--vscode-border-radius) var(--vscode-border-radius) 0;
        }

        .activity-icon:hover {
            color: var(--vscode-activitybar-active);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .activity-icon.active {
            color: var(--vscode-activitybar-active);
            border-left-color: var(--vscode-accent);
            background-color: rgba(0, 120, 212, 0.1);
        }

        .activity-icon::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            background-color: var(--vscode-accent);
            transition: var(--vscode-transition);
            border-radius: 0 2px 2px 0;
        }

        .activity-icon.active::before {
            width: 3px;
            height: 24px;
        }

        /* Sidebar */
        #sidebar {
            width: 280px;
            background-color: var(--vscode-sidebar-bg);
            border-right: 1px solid var(--vscode-border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-title {
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--vscode-text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--vscode-border);
        }

        .sidebar-actions {
            opacity: 0;
            transition: var(--vscode-transition);
            display: flex;
            gap: 4px;
        }

        .sidebar-title:hover .sidebar-actions {
            opacity: 1;
        }

        .action-btn {
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 3px;
            transition: var(--vscode-transition);
            color: var(--vscode-text-secondary);
        }

        .action-btn:hover {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
        }

        #file-explorer {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        #file-explorer::-webkit-scrollbar {
            width: 6px;
        }

        #file-explorer::-webkit-scrollbar-track {
            background: transparent;
        }

        #file-explorer::-webkit-scrollbar-thumb {
            background: var(--vscode-border);
            border-radius: 3px;
        }

        #file-explorer::-webkit-scrollbar-thumb:hover {
            background: var(--vscode-text-secondary);
        }

        .explorer-item {
            padding: 6px 12px 6px 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            font-size: 13px;
            transition: var(--vscode-transition);
            border-radius: 3px;
            margin: 1px 8px;
        }

        .explorer-item:hover {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
        }

        .explorer-item.active {
            background-color: var(--vscode-selection);
            color: var(--vscode-text);
        }

        .explorer-item i {
            width: 16px;
            text-align: center;
            font-size: 14px;
        }

        .fa-folder {
            color: #dcb67a;
        }

        .fa-folder-open {
            color: #e8c887;
        }

        .fa-file-code {
            color: #519ab9;
        }

        .fa-file {
            color: var(--vscode-text-secondary);
        }

        .fa-php {
            color: #8993be;
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

        .fa-json {
            color: #cb4a32;
        }

        .fa-md {
            color: #083fa1;
        }

        .fa-terminal {
            color: #4d9375;
        }

        .fa-python {
            color: #3572A5;
        }

        .fa-java {
            color: #f89820;
        }

        .fa-rust {
            color: #dea584;
        }

        .fa-go {
            color: #00ADD8;
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
            scrollbar-width: thin;
        }

        #tabs-container::-webkit-scrollbar {
            height: 4px;
        }

        #tabs-container::-webkit-scrollbar-track {
            background: transparent;
        }

        #tabs-container::-webkit-scrollbar-thumb {
            background: var(--vscode-border);
            border-radius: 2px;
        }

        .tab {
            padding: 6px 12px;
            background-color: var(--vscode-tab-bg);
            border-right: 1px solid var(--vscode-border);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            color: var(--vscode-text-secondary);
            border-top: 2px solid transparent;
            transition: var(--vscode-transition);
            position: relative;
            font-size: 13px;
        }

        .tab:hover {
            background-color: var(--vscode-hover);
        }

        .tab.active {
            background-color: var(--vscode-tab-active-bg);
            color: var(--vscode-text);
            border-top-color: var(--vscode-accent);
        }

        .tab-close {
            opacity: 0;
            font-size: 11px;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--vscode-transition);
        }

        .tab:hover .tab-close {
            opacity: 1;
        }

        .tab-close:hover {
            background-color: var(--vscode-hover);
            color: var(--vscode-error);
        }

        .tab.dirty::before {
            content: '';
            position: absolute;
            top: 6px;
            right: 8px;
            width: 4px;
            height: 4px;
            background-color: var(--vscode-warning);
            border-radius: 50%;
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
            transition: var(--vscode-transition);
        }

        .panel-header {
            display: flex;
            gap: 24px;
            padding: 6px 16px;
            border-bottom: 1px solid var(--vscode-border);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: var(--vscode-sidebar-bg);
        }

        .panel-tab {
            cursor: pointer;
            color: var(--vscode-text-secondary);
            padding: 4px 0;
            border-bottom: 2px solid transparent;
            transition: var(--vscode-transition);
            position: relative;
        }

        .panel-tab:hover {
            color: var(--vscode-text);
        }

        .panel-tab.active {
            color: var(--vscode-accent);
            border-bottom-color: var(--vscode-accent);
        }

        #terminal-container {
            flex: 1;
            padding: 12px;
            overflow: hidden;
            background: #0c0c0c;
            border-radius: var(--vscode-border-radius);
            margin: 4px;
            border: 1px solid var(--vscode-border);
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        #terminal-container::before {
            content: '';
            position: absolute;
            top: 8px;
            right: 12px;
            color: var(--vscode-text-secondary);
            font-size: 10px;
            font-family: var(--vscode-mono-font);
            background: rgba(0, 0, 0, 0.6);
            padding: 2px 6px;
            border-radius: 3px;
            opacity: 0.7;
            transition: var(--vscode-transition);
        }

        #terminal-container.active::before {
            content: '● RUNNING';
            color: var(--vscode-success);
            opacity: 1;
        }

        #terminal-container.error::before {
            content: '⚠ ERROR';
            color: var(--vscode-error);
            opacity: 1;
        }

        /* Terminal scrollbar styling */
        .xterm {
            height: 100% !important;
            font-family: var(--vscode-mono-font) !important;
            font-size: 13px !important;
            line-height: 1.4 !important;
        }

        .xterm-viewport {
            background: transparent !important;
        }

        .xterm-screen {
            background: transparent !important;
            color: #0f0f0f !important;
        }

        .xterm-rows {
            padding: 8px !important;
        }

        .xterm-cursor {
            background: #0f0f0f !important;
            color: #0f0f0f !important;
            border: 1px solid #ffffff !important;
            animation: terminalCursor 1s infinite !important;
        }

        @keyframes terminalCursor {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0;
            }
        }

        /* Status Bar */
        #statusbar {
            height: 24px;
            background-color: var(--vscode-statusbar-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 12px;
            color: white;
            font-size: 12px;
            font-weight: 500;
            border-top: 1px solid var(--vscode-border);
            backdrop-filter: blur(10px);
        }

        .statusbar-item {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 2px 8px;
            border-radius: 3px;
            transition: var(--vscode-transition);
            font-size: 11px;
        }

        .statusbar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .statusbar-item i {
            font-size: 10px;
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
            top: 35px;
            bottom: 24px;
            width: 400px;
            min-width: 300px;
            max-width: 800px;
            background-color: var(--vscode-sidebar-bg);
            border-left: 1px solid var(--vscode-border);
            box-shadow: -8px 0 32px var(--vscode-shadow);
            display: flex;
            flex-direction: column;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            resize: horizontal;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .ai-resize-handle {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            cursor: ew-resize;
            background-color: transparent;
            z-index: 1001;
            transition: var(--vscode-transition);
            border-radius: 3px 0 0 3px;
        }

        .ai-resize-handle:hover {
            background-color: var(--vscode-accent);
            width: 8px;
        }

        .ai-resize-handle:active {
            background-color: var(--vscode-accent-hover);
        }

        .ai-header {
            padding: 12px 16px;
            background-color: var(--vscode-titlebar-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--vscode-border);
            backdrop-filter: blur(10px);
        }

        .ai-header h3 {
            margin: 0;
            font-size: 13px;
            color: var(--vscode-text);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ai-header h3::before {
            content: '🤖';
            font-size: 16px;
        }

        .ai-close {
            cursor: pointer;
            color: var(--vscode-text-secondary);
            padding: 4px 8px;
            border-radius: var(--vscode-border-radius);
            transition: var(--vscode-transition);
            font-size: 14px;
        }

        .ai-close:hover {
            color: var(--vscode-text);
            background-color: var(--vscode-hover);
        }

        .ai-resize-handle {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            cursor: ew-resize;
            background-color: transparent;
            z-index: 1001;
        }

        .ai-resize-handle:hover {
            background-color: var(--vscode-statusbar-bg);
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
            padding: 12px;
            scrollbar-width: thin;
        }

        .ai-messages::-webkit-scrollbar {
            width: 6px;
        }

        .ai-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .ai-messages::-webkit-scrollbar-thumb {
            background: var(--vscode-border);
            border-radius: 3px;
        }

        .ai-messages::-webkit-scrollbar-thumb:hover {
            background: var(--vscode-text-secondary);
        }

        .ai-message,
        .chat-message {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: var(--vscode-border-radius);
            line-height: 1.5;
            word-wrap: break-word;
            user-select: text;
            animation: messageSlideIn 0.3s ease-out;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ai-message.user,
        .chat-message.user {
            background: linear-gradient(135deg, #094771 0%, #063661 100%);
            color: white;
            border-left: 3px solid var(--vscode-accent);
            margin-right: 20px;
        }

        .ai-message.assistant,
        .chat-message.ai {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
            border-left: 3px solid var(--vscode-accent);
            margin-left: 20px;
        }

        .ai-messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .ai-message,
        .chat-message {
            margin-bottom: 15px;
            padding: 10px 12px;
            border-radius: 6px;
            line-height: 1.5;
            word-wrap: break-word;
            user-select: text;
        }

        .ai-message.user,
        .chat-message.user {
            background-color: #094771;
            text-align: left;
            color: white;
            border-left: 3px solid #007acc;
        }

        .ai-message.assistant,
        .chat-message.ai {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
            border-left: 3px solid #666;
        }

        .ai-message pre,
        .chat-message pre {
            background-color: #1e1e1e;
            padding: 12px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 8px 0;
            border: 1px solid var(--vscode-border);
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.4;
            white-space: pre;
            user-select: text;
        }

        .ai-message code,
        .chat-message code {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 2px 4px;
            border-radius: 3px;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 11px;
        }

        .ai-message strong,
        .chat-message strong {
            color: #007acc;
            font-weight: bold;
        }

        .ai-message em,
        .chat-message em {
            font-style: italic;
        }

        .ai-message ul,
        .ai-message ol,
        .chat-message ul,
        .chat-message ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .ai-message li,
        .chat-message li {
            margin: 4px 0;
        }

        .text-danger {
            color: #ff6b6b;
        }

        .text-muted {
            color: var(--vscode-text-secondary);
        }

        /* Copy button for code blocks */
        .ai-message pre {
            position: relative;
        }

        .copy-code-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: var(--vscode-bg);
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text);
            padding: 4px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }

        .ai-message pre:hover .copy-code-btn {
            opacity: 1;
        }

        .copy-code-btn:hover {
            background-color: var(--vscode-hover);
            color: #007acc;
        }

        .copy-code-btn.copied {
            background-color: #007acc;
            color: white;
        }

        .ai-input-area {
            padding: 16px;
            border-top: 1px solid var(--vscode-border);
            background-color: var(--vscode-sidebar-bg);
        }

        .input-group {
            display: flex;
            gap: 8px;
            align-items: flex-end;
        }

        .ai-input {
            flex: 1;
            background-color: var(--vscode-bg);
            border: 1px solid var(--vscode-border);
            color: var(--vscode-text);
            padding: 10px 12px;
            font-size: 13px;
            resize: vertical;
            min-height: 40px;
            max-height: 120px;
            font-family: var(--vscode-font-family);
            border-radius: var(--vscode-border-radius);
            transition: var(--vscode-transition);
            line-height: 1.4;
        }

        .ai-input:focus {
            outline: none;
            border-color: var(--vscode-accent);
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
        }

        .ai-input::placeholder {
            color: var(--vscode-text-secondary);
            font-style: italic;
        }

        .ai-send-btn {
            margin-top: 8px;
            background-color: var(--vscode-accent);
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--vscode-border-radius);
            transition: var(--vscode-transition);
            box-shadow: 0 2px 4px rgba(0, 120, 212, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ai-send-btn:hover:not(:disabled) {
            background-color: var(--vscode-accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 120, 212, 0.4);
        }

        .ai-send-btn:active:not(:disabled) {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 120, 212, 0.3);
        }

        .ai-send-btn:disabled {
            background-color: var(--vscode-text-secondary);
            cursor: not-allowed;
            opacity: 0.6;
            box-shadow: none;
        }

        .ai-loading {
            text-align: center;
            padding: 12px;
            color: var(--vscode-text-secondary);
            font-style: italic;
        }

        /* Modern button styles */
        .btn-vscode {
            background-color: var(--vscode-accent);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: var(--vscode-border-radius);
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            transition: var(--vscode-transition);
        }

        .btn-vscode:hover {
            background-color: var(--vscode-accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--vscode-hover);
            color: var(--vscode-text);
        }

        .btn-secondary:hover {
            background-color: var(--vscode-selection);
        }

        .btn-danger {
            background-color: var(--vscode-error);
        }

        .btn-danger:hover {
            background-color: #d9363e;
        }

        /* Context Menu */
        #context-menu {
            position: fixed;
            background-color: var(--vscode-menu-bg);
            border: 1px solid var(--vscode-border);
            box-shadow: 0 8px 32px var(--vscode-shadow);
            z-index: 10000;
            display: none;
            min-width: 200px;
            padding: 6px 0;
            border-radius: var(--vscode-border-radius);
            backdrop-filter: blur(10px);
            animation: contextMenuFadeIn 0.15s ease-out;
        }

        @keyframes contextMenuFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .context-menu-item {
            padding: 6px 16px 6px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: var(--vscode-text);
            transition: var(--vscode-transition);
            border-radius: 3px;
            margin: 0 4px;
        }

        .context-menu-item:hover {
            background-color: var(--vscode-selection);
            color: var(--vscode-text);
        }

        .context-menu-item i {
            width: 16px;
            text-align: center;
            font-size: 13px;
        }

        .context-menu-separator {
            height: 1px;
            background-color: var(--vscode-border);
            margin: 6px 8px;
            opacity: 0.5;
        }

        .hidden {
            display: none !important;
        }

        /* Modern utilities */
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        /* Loading states */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* AI Enhanced Features */
        .ai-timestamp {
            font-size: 10px;
            color: var(--vscode-text-secondary);
            font-weight: normal;
            margin-left: 8px;
        }

        .ai-cached-badge {
            background-color: var(--vscode-success);
            color: white;
            font-size: 9px;
            padding: 1px 4px;
            border-radius: 3px;
            margin-left: 8px;
            font-weight: 500;
        }

        .ai-loading-message {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ai-loading-text {
            flex: 1;
        }

        .ai-loading-dots {
            display: flex;
            gap: 2px;
        }

        .ai-loading-dots span {
            animation: loadingDot 1.4s infinite ease-in-out both;
        }

        .ai-loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .ai-loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        .ai-loading-dots span:nth-child(3) {
            animation-delay: 0;
        }

        @keyframes loadingDot {

            0%,
            80%,
            100% {
                opacity: 0.3;
                transform: scale(0.8);
            }

            40% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .ai-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ai-action-btn {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--vscode-text-secondary);
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10px;
            transition: var(--vscode-transition);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ai-action-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--vscode-text);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .ai-error-message {
            border-left-color: var(--vscode-error);
            background: linear-gradient(135deg, rgba(241, 76, 76, 0.1) 0%, rgba(241, 76, 76, 0.05) 100%);
        }

        .ai-error-content {
            color: #ff9999;
            margin: 4px 0;
        }

        .ai-error-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
        }

        /* Notifications */
        .ai-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            border-radius: var(--vscode-border-radius);
            color: white;
            font-size: 12px;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: notificationSlide 0.3s ease-out;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            max-width: 300px;
        }

        .ai-notification-success {
            background-color: var(--vscode-success);
        }

        .ai-notification-error {
            background-color: var(--vscode-error);
        }

        .ai-notification-info {
            background-color: var(--vscode-accent);
        }

        .ai-notification.fade-out {
            animation: notificationFade 0.3s ease-out;
        }

        @keyframes notificationSlide {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes notificationFade {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        .context-menu-item {
            padding: 4px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--vscode-text);
        }

        .context-menu-item:hover {
            background-color: var(--vscode-menu-hover);
            color: white;
        }

        .context-menu-item i {
            width: 16px;
            text-align: center;
        }

        .context-menu-separator {
            height: 1px;
            background-color: var(--vscode-border);
            margin: 4px 0;
        }

        /* Chat UI Enhancements */
        .ai-messages {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .chat-message {
            max-width: 85%;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
            position: relative;
        }

        .chat-message.user {
            align-self: flex-end;
            background-color: #007acc;
            /* VS Code Blue */
            color: white;
            border-bottom-right-radius: 2px;
        }

        .chat-message.ai {
            align-self: flex-start;
            background-color: #2d2d2d;
            /* Darker gray */
            color: #cccccc;
            border-bottom-left-radius: 2px;
            border: 1px solid #3e3e42;
        }

        .chat-message.ai strong {
            color: #569cd6;
            /* Blue for strong text */
        }

        .chat-message.ai code {
            background-color: #1e1e1e;
            padding: 2px 4px;
            border-radius: 4px;
            font-family: 'Consolas', 'Courier New', monospace;
            color: #d4d4d4;
        }

        .chat-message.ai pre {
            background-color: #1e1e1e;
            padding: 10px;
            border-radius: 6px;
            margin-top: 8px;
            overflow-x: auto;
            border: 1px solid #474747;
        }

        .ai-input-area {
            padding: 15px;
            border-top: 1px solid var(--vscode-border);
            background-color: var(--vscode-sidebar-bg);
            /* Bootstrap overrides for this section */
        }

        .ai-input-area textarea.form-control {
            background-color: #3c3c3c;
            border: 1px solid #3c3c3c;
            color: #cccccc;
            resize: none;
            font-size: 13px;
        }

        .ai-input-area textarea.form-control:focus {
            background-color: #3c3c3c;
            border-color: #007acc;
            color: #cccccc;
            box-shadow: 0 0 0 0.2rem rgba(0, 122, 204, 0.25);
        }

        .btn-vscode {
            background-color: #007acc;
            color: white;
            border: none;
        }

        .btn-vscode:hover {
            background-color: #005a9e;
            color: white;
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
                <div class="dropdown-item" onclick="fileMenuNewFile()">New Text File <span
                        class="shortcut">Ctrl+N</span>
                </div>
                <div class="dropdown-item" onclick="fileMenuNewFile('window')">New Window</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="document.getElementById('file-upload').click()">Open File...</div>
                <div class="dropdown-item" onclick="fileMenuOpenFolder()">Open Folder...</div>
                <div class="dropdown-item" onclick="fileMenuOpenRecent()">Open Recent...</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="saveFile()">Save <span class="shortcut">Ctrl+S</span></div>
                <div class="dropdown-item" onclick="saveFileAs()">Save As... <span class="shortcut">Ctrl+Shift+S</span>
                </div>
                <div class="dropdown-item" onclick="saveAll()">Save All</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="toggleAutoSave()">Auto Save <span id="autosave-indicator"></span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="closeFile()">Close Editor <span class="shortcut">Ctrl+F4</span>
                </div>
                <div class="dropdown-item" onclick="closeAllEditors()">Close All Editors</div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="exitApp()">Exit</div>
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
                <div class="dropdown-item" onclick="selectLine()">Select Line <span class="shortcut">Ctrl+L</span></div>
                <div class="dropdown-item" onclick="deleteLine()">Delete Line <span class="shortcut">Ctrl+Shift+K</span>
                </div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('find')">Find <span class="shortcut">Ctrl+F</span></div>
                <div class="dropdown-item" onclick="triggerEdit('replace')">Replace <span class="shortcut">Ctrl+H</span>
                </div>
                <div class="dropdown-item" onclick="findInFiles()">Find in Files <span
                        class="shortcut">Ctrl+Shift+F</span></div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('comment')">Toggle Line Comment <span
                        class="shortcut">Ctrl+/</span></div>
                <div class="dropdown-item" onclick="toggleBlockComment()">Toggle Block Comment <span
                        class="shortcut">Ctrl+Shift+A</span></div>
                <div class="separator"></div>
                <div class="dropdown-item" onclick="triggerEdit('format')">Format Document <span
                        class="shortcut">Shift+Alt+F</span></div>
                <div class="dropdown-item" onclick="formatSelection()">Format Selection <span class="shortcut">Ctrl+K
                        Ctrl+F</span></div>
            </div>
            Ctrl+F</span>
        </div>
    </div>

    <!-- Selection Menu Dropdown -->
    <div class="dropdown" id="menu-selection">
        <div class="dropdown-item" onclick="triggerSelection('all')">Select All <span class="shortcut">Ctrl+A</span>
        </div>
        <div class="dropdown-item" onclick="triggerSelection('expand')">Expand Selection <span
                class="shortcut">Shift+Alt+Right</span></div>
    </div>

    <!-- View Menu Dropdown -->
    <div class="dropdown" id="menu-view">
        <div class="dropdown-item" onclick="toggleSidebar()">Appearance</div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="togglePanel()">Toggle Terminal <span class="shortcut">Ctrl+`</span>
        </div>
        <div class="dropdown-item" onclick="toggleSidebar()">Toggle Sidebar <span class="shortcut">Ctrl+B</span>
        </div>
        <div class="dropdown-item" onclick="toggleActivityBar()">Toggle Activity Bar</div>
        <div class="dropdown-item" onclick="togglePanel(true)">Toggle Panel <span class="shortcut">Ctrl+J</span>
        </div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="triggerView('minimap')">Toggle Minimap</div>
        <div class="dropdown-item" onclick="triggerView('wordwrap')">Toggle Word Wrap <span
                class="shortcut">Alt+Z</span></div>
        <div class="dropdown-item" onclick="toggleRenderWhitespace()">Toggle Render Whitespace</div>
        <div class="dropdown-item" onclick="toggleLineNumbers()">Toggle Line Numbers</div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="triggerView('zoomin')">Zoom In <span class="shortcut">Ctrl+=</span>
        </div>
        <div class="dropdown-item" onclick="triggerView('zoomout')">Zoom Out <span class="shortcut">Ctrl+-</span></div>
        <div class="dropdown-item" onclick="triggerView('zoomreset')">Reset Zoom</div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="toggleFullscreen()">Toggle Fullscreen <span class="shortcut">F11</span>
        </div>
    </div>

    <!-- Go Menu Dropdown -->
    <div class="dropdown" id="menu-go">
        <div class="dropdown-item" onclick="triggerGo('file')">Go to File... <span class="shortcut">Ctrl+P</span></div>
        <div class="dropdown-item" onclick="triggerGo('line')">Go to Line... <span class="shortcut">Ctrl+G</span></div>
    </div>

    <!-- Run Menu Dropdown -->
    <div class="dropdown" id="menu-run">
        <div class="dropdown-item" onclick="executeRun()">Start Debugging <span class="shortcut">F5</span></div>
        <div class="dropdown-item" onclick="executeRun()">Run Without Debugging <span class="shortcut">Ctrl+F5</span>
        </div>
    </div>

    <!-- Terminal Menu Dropdown -->
    <div class="dropdown" id="menu-terminal">
        <div class="dropdown-item" onclick="togglePanel()">New Terminal <span class="shortcut">Ctrl+Shift+`</span></div>
        <div class="dropdown-item" onclick="executeCommand('cls')">Clear Terminal</div>
    </div>

    <!-- Help Menu Dropdown -->
    <div class="dropdown" id="menu-help">
        <div class="dropdown-item" onclick="showWelcome()">Welcome</div>
        <div class="dropdown-item" onclick="showDocumentation()">Documentation</div>
        <div class="dropdown-item" onclick="showKeyboardShortcuts()">Keyboard Shortcuts <span class="shortcut">Ctrl+K
                Ctrl+S</span>
        </div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="toggleDeveloperTools()">Toggle Developer Tools <span
                class="shortcut">F12</span>
        </div>
        <div class="dropdown-item" onclick="showProcessExplorer()">Open Process Explorer</div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="showAccessibilityOptions()">Accessibility Options</div>
        <div class="separator"></div>
        <div class="dropdown-item" onclick="triggerHelp()">About</div>
        <div class="dropdown-item" onclick="checkUpdates()">Check for Updates...</div>
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
            <div class="activity-icon" title="AI Assistant" onclick="toggleAIPanel()"><i class="fas fa-robot"></i></div>
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

            // Menu Bindings - Event Delegation (Robust)
            $(document).on('click', '.menu-item', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Hide other dropdowns
                $('.dropdown').not($(this).next('.dropdown')).hide();

                let menu = $(this).data('menu');
                let offset = $(this).offset();
                let $dropdown = $('#menu-' + menu);



                if ($dropdown.is(':visible')) {
                    $dropdown.hide();
                } else {
                    // Hide all other dropdowns first
                    $('.dropdown').hide();

                    $dropdown.css({
                        top: (offset.top + 35) + 'px',
                        left: offset.left + 'px',
                        display: 'block',
                        zIndex: 10001
                    }).addClass('fade-in');
                }
            });

            // Close all menus on outside click
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.menu-item').length && !$(e.target).closest('.dropdown').length) {
                    $('.dropdown').hide();
                }
                $('#context-menu').hide();
            });

            // AI Input keyboard shortcuts
            $('#ai-input').on('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendAIQuery();
                }
            });

            // Initialize AI query tracking
            initAIQueryTracking();
        });

        // Global Keyboard Shortcuts (Native Window Listener with Capture)
        window.addEventListener('keydown', function (e) {
            // Ctrl+0 for AI Assistant
            if (e.ctrlKey && e.key === '0') {
                e.preventDefault();
                e.stopPropagation();
                toggleAIPanel();
                return false;
            }
            // Ctrl+S for Save
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                if (currentFile) saveFile();
                return false;
            }
            // Ctrl+N for New File
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                createNewFile();
                return false;
            }
            // Ctrl+B for Toggle Sidebar
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                toggleSidebar();
                return false;
            }
            // F11 for Fullscreen
            if (e.key === 'F11') {
                e.preventDefault();
                toggleFullscreen();
                return false;
            }
            // Ctrl+` for Terminal
            if (e.ctrlKey && e.key === '`') {
                e.preventDefault();
                togglePanel();
                return false;
            }
            // Ctrl+L for Select Line
            if (e.ctrlKey && e.key === 'l') {
                e.preventDefault();
                selectLine();
                return false;
            }
            // F12 for Developer Tools
            if (e.key === 'F12') {
                e.preventDefault();
                toggleDeveloperTools();
                return false;
            }
            // Ctrl+Enter for AI Chat send
            if (e.ctrlKey && e.key === 'Enter') {
                const aiInput = document.getElementById('ai-input');
                if (aiInput === document.activeElement) {
                    e.preventDefault();
                    sendAIQuery();
                    return false;
                }
            }
            // Ctrl+Z for Undo
            if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                triggerEdit('undo');
                return false;
            }
            // Ctrl+Y for Redo
            if (e.ctrlKey && e.key === 'y') {
                e.preventDefault();
                triggerEdit('redo');
                return false;
            }
            // Ctrl+X for Cut
            if (e.ctrlKey && e.key === 'x') {
                e.preventDefault();
                triggerEdit('cut');
                return false;
            }
            // Ctrl+C for Copy
            if (e.ctrlKey && e.key === 'c') {
                e.preventDefault();
                triggerEdit('copy');
                return false;
            }
            // Ctrl+V for Paste
            if (e.ctrlKey && e.key === 'v') {
                e.preventDefault();
                triggerEdit('paste');
                return false;
            }
            // Ctrl+F for Find
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                triggerEdit('find');
                return false;
            }
            // Ctrl+H for Replace
            if (e.ctrlKey && e.key === 'h') {
                e.preventDefault();
                triggerEdit('replace');
                return false;
            }
            // Ctrl+/ for Toggle Comment
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                triggerEdit('comment');
                return false;
            }
            // Ctrl+Shift+A for Toggle Block Comment
            if (e.ctrlKey && e.shiftKey && e.key === 'A') {
                e.preventDefault();
                triggerEdit('blockcomment');
                return false;
            }
            // Ctrl+Shift+F for Format Document
            if (e.ctrlKey && e.shiftKey && e.key === 'F') {
                e.preventDefault();
                triggerEdit('format');
                return false;
            }
        }, { capture: true });

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
            const terminalContainer = document.getElementById('terminal-container');
            terminalContainer.classList.add('active');

            term = new Terminal({
                cursorBlink: true,
                cursorStyle: 'block',
                fontFamily: 'var(--vscode-mono-font)',
                fontSize: 13,
                fontWeight: '400',
                lineHeight: 1.4,
                letterSpacing: 0,
                theme: {
                    background: 'transparent',
                    foreground: '#0f0f0f',
                    cursor: '#ffffff',
                    cursorAccent: '#000000',
                    selection: 'rgba(255, 255, 255, 0.3)',
                    black: '#000000',
                    red: '#ff5555',
                    green: '#50fa7b',
                    yellow: '#f1fa8c',
                    blue: '#57c7ff',
                    magenta: '#ff79c6',
                    cyan: '#8be9fd',
                    white: '#bfbfbf',
                    brightBlack: '#4d4d4d',
                    brightRed: '#ff6e6e',
                    brightGreen: '#69ff94',
                    brightYellow: '#ffffa5',
                    brightBlue: '#d6acff',
                    brightMagenta: '#ff92df',
                    brightCyan: '#a4ffff',
                    brightWhite: '#ffffff'
                },
                allowTransparency: true,
                scrollback: 1000,
                cols: 80,
                rows: 24
            });

            // Load add-ons
            const fitAddon = new FitAddon.FitAddon();
            const webLinksAddon = new WebLinksAddon.WebLinksAddon();
            const searchAddon = new SearchAddon.SearchAddon();

            term.loadAddon(fitAddon);
            term.loadAddon(webLinksAddon);
            term.loadAddon(searchAddon);

            term.open(terminalContainer);
            fitAddon.fit();

            // Welcome message
            term.writeln('');
            term.writeln('\x1b[38;5;208m┌──────────────────────────────────────────────┐\x1b[0m');
            term.writeln('\x1b[38;5;208m│                                              │\x1b[0m');
            term.writeln('\x1b[38;5;208m│  \x1b[38;5;40mPHP WEB TERMINAL\x1b[0m\x1b[38;5;208m                 │\x1b[0m');
            term.writeln('\x1b[38;5;208m│  \x1b[38;5;46m● Ready\x1b[0m\x1b[38;5;208m                    │\x1b[0m');
            term.writeln('\x1b[38;5;208m│  \x1b[38;5;40mhelp\x1b[0m\x1b[38;5;208m                     \x1b[38;5;40mls\x1b[0m\x1b[38;5;208m                     \x1b[38;5;40mcd\x1b[0m\x1b[38;5;208m                     \x1b[38;5;40mphp\x1b[0m\x1b[38;5;208m                     │\x1b[0m');
            term.writeln('\x1b[38;5;208m└──────────────────────────────────────────────┘\x1b[0m');
            term.writeln('');

            // Show current working directory
            const cwdDisplay = currentCwd.length > 25 ? '../' + basename(currentCwd) : currentCwd;
            term.write('\x1b[38;5;34m' + cwdDisplay + '\x1b[0m\x1b[38;5;46m$ \x1b[0m');

            // Resize listener
            window.addEventListener('resize', () => fitAddon.fit());

            // Terminal command history
            let commandHistory = [];
            let historyIndex = -1;

            // Handle Input
            let currentLine = '';
            term.onData(e => {
                // Update terminal status
                const container = document.getElementById('terminal-container');
                container.classList.remove('error');
                container.classList.add('active');

                switch (e) {
                    case '\r': // Enter
                        term.write('\r\n');

                        // Add to history
                        if (currentLine.trim()) {
                            commandHistory.push(currentLine.trim());
                            if (commandHistory.length > 50) {
                                commandHistory.shift();
                            }
                            historyIndex = -1;
                        }

                        executeCommand(currentLine);
                        currentLine = '';
                        break;

                    case '\u007F': // Backspace
                        if (currentLine.length > 0) {
                            currentLine = currentLine.substring(0, currentLine.length - 1);
                            term.write('\b \b');
                        }
                        break;

                    case '\u001B': // Arrow keys
                        // Handle arrow up/down for history
                        return; // Let arrow keys be handled separately

                    default:
                        // Basic printable character check
                        if (e >= ' ' && e <= '~') {
                            currentLine += e;
                            term.write(e);
                            historyIndex = -1; // Reset history index when typing
                        }
                }
            });

            // Add keyboard support for history navigation
            document.addEventListener('keydown', function (e) {
                if (document.activeElement !== term.textarea) return;

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (historyIndex === -1) {
                        historyIndex = commandHistory.length - 1;
                    } else if (historyIndex > 0) {
                        historyIndex--;
                    }

                    if (historyIndex >= 0 && historyIndex < commandHistory.length) {
                        // Clear current line
                        for (let i = 0; i < currentLine.length; i++) {
                            term.write('\b \b');
                        }
                        currentLine = commandHistory[historyIndex];
                        term.write(currentLine);
                    }
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (historyIndex === -1) {
                        historyIndex = 0;
                    } else if (historyIndex < commandHistory.length - 1) {
                        historyIndex++;
                    }

                    if (historyIndex >= 0 && historyIndex < commandHistory.length) {
                        // Clear current line
                        for (let i = 0; i < currentLine.length; i++) {
                            term.write('\b \b');
                        }
                        currentLine = commandHistory[historyIndex];
                        term.write(currentLine);
                    } else if (historyIndex === commandHistory.length) {
                        // Clear to empty prompt
                        for (let i = 0; i < currentLine.length; i++) {
                            term.write('\b \b');
                        }
                        currentLine = '';
                    }
                }

                if (e.key === 'Tab') {
                    e.preventDefault();
                    // Simple autocomplete for common commands
                    const commands = ['ls', 'cd ', 'php ', 'git ', 'help', 'clear', 'exit'];
                    const matches = commands.filter(cmd => cmd.startsWith(currentLine));

                    if (matches.length === 1) {
                        const completion = matches[0];
                        const remainder = completion.substring(currentLine.length);
                        term.write(remainder);
                        currentLine = completion;
                    } else if (matches.length > 1) {
                        // Show suggestions
                        term.writeln('\r\nSuggestions: ' + matches.slice(0, 5).join(', '));
                        term.write('\x1b[38;5;34m' + currentCwd + '\x1b[0m\x1b[38;5;46m $ \x1b[0m');
                        currentLine = '';
                    }
                }
            });
        }

        function executeCommand(cmd) {
            if (!cmd.trim()) {
                showPrompt();
                return;
            }

            // Handle built-in commands first
            const trimmedCmd = cmd.trim();

            if (trimmedCmd === 'help') {
                showHelp();
                return;
            }

            if (trimmedCmd === 'exit' || trimmedCmd === 'quit') {
                term.writeln('\r\nGoodbye! Type \x1b[38;5;34mhelp\x1b[0m to return to the terminal.');
                return;
            }

            if (trimmedCmd === 'cls' || trimmedCmd === 'clear') {
                term.clear();
                showPrompt();
                return;
            }

            if (trimmedCmd === 'history') {
                term.writeln('\r\nCommand History:');
                commandHistory.forEach((cmd, index) => {
                    term.writeln(`  ${index + 1}: ${cmd}`);
                });
                showPrompt();
                return;
            }

            // Show loading indicator for long commands
            const container = document.getElementById('terminal-container');
            container.classList.remove('error');
            container.classList.add('active');

            // Call backend
            $.ajax({
                url: 'terminal.php',
                method: 'POST',
                timeout: 30000,
                data: JSON.stringify({ command: cmd, cwd: currentCwd }),
                contentType: 'application/json',
                beforeSend: function () {
                    term.write('\x1b[38;5;46m⏳ Executing...\x1b[0m');
                },
                success: function (res) {
                    if (res.output) {
                        // Clear loading indicator
                        term.write('\r\x1b[K');

                        // Fix line endings for xterm
                        let out = res.output;
                        if (out.endsWith('\n') || out.endsWith('\r\n')) {
                            out = out.replace(/\r?\n/g, '\r\n');
                        } else {
                            out = '\r\n' + out;
                        }
                        term.write(out);
                    }
                    if (res.cwd) {
                        currentCwd = res.cwd;
                    }
                    showPrompt();
                },
                error: function (xhr, status, error) {
                    // Clear loading indicator
                    term.write('\r\x1b[K');

                    let errorMsg = 'Command failed';
                    if (status === 'timeout') {
                        errorMsg = 'Command timed out';
                    } else if (status >= 500) {
                        errorMsg = 'Server error';
                    }

                    container.classList.remove('active');
                    container.classList.add('error');
                    term.writeln(`\r\n\x1b[38;5;196m✗ ${errorMsg}\x1b[0m`);
                    showPrompt();
                }
            });
        }

        function showPrompt() {
            const cwdDisplay = currentCwd.length > 25 ? '../' + basename(currentCwd) : currentCwd;
            term.write('\x1b[38;5;34m' + cwdDisplay + '\x1b[0m\x1b[38;5;46m$ \x1b[0m');
        }

        function showHelp() {
            term.writeln('');
            term.writeln('\x1b[38;5;208m╭─────────────────────────────────────────╮\x1b[0m');
            term.writeln('\x1b[38;5;208m│ AVAILABLE COMMANDS                   │\x1b[0m');
            term.writeln('\x1b[38;5;208m├─────────────────────────────────────────┤\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mls\x1b[0m        \x1b[38;5;208m- List directory contents  │\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mcd [path]\x1b[0m\x1b[38;5;208m- Change directory      │\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mphp file.php\x1b[0m\x1b[38;5;208m- Execute PHP script   │\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mclear/help/cls\x1b[0m\x1b[38;5;208m- Clear screen/help    │\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mhistory\x1b[0m\x1b[38;5;208m- Show command history  │\x1b[0m');
            term.writeln('\x1b[38;5;208m│ \x1b[38;5;40mexit/quit\x1b[0m\x1b[38;5;208m- Exit terminal         │\x1b[0m');
            term.writeln('\x1b[38;5;208m╰─────────────────────────────────────────╯\x1b[0m');
            term.writeln('');
            term.writeln('\x1b[38;5;46mTIP:\x1b[0m Use \x1b[38;5;34mTab\x1b[0m\x1b[38;5;46m for autocomplete!');
            term.writeln('');
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
                item.data('node', node); // Store node data

                // Click handler
                item.click(function (e) {
                    e.stopPropagation();
                    $('.explorer-item').css('background-color', '');
                    $(this).css('background-color', '#37373d');

                    if (node.type === 'file') {
                        openFile(node.path);
                    }
                });

                // Right-click context menu
                item.on('contextmenu', function (e) {
                    e.preventDefault();
                    showContextMenu(e.pageX, e.pageY, node);
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
                    addToRecentFiles(path);
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
                    addToRecentFiles(currentFile);
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
            console.log('TriggerEdit called with action:', action);

            if (!editor) {
                showNotification('No file is currently open. Please create or open a file first.', 'error');
                return;
            }

            editor.focus();

            try {
                switch (action) {
                    case 'undo':
                        editor.trigger('menu', 'undo');
                        showNotification('Undo action performed', 'info');
                        break;
                    case 'redo':
                        editor.trigger('menu', 'redo');
                        showNotification('Redo action performed', 'info');
                        break;
                    case 'cut':
                        editor.trigger('menu', 'editor.action.clipboardCutAction');
                        showNotification('Cut action performed', 'info');
                        break;
                    case 'copy':
                        editor.trigger('menu', 'editor.action.clipboardCopyAction');
                        showNotification('Copy action performed', 'info');
                        break;
                    case 'paste':
                        editor.trigger('menu', 'editor.action.clipboardPasteAction');
                        showNotification('Paste action performed', 'info');
                        break;
                    case 'find':
                        editor.trigger('menu', 'actions.find');
                        showNotification('Find dialog opened', 'info');
                        break;
                    case 'replace':
                        editor.trigger('menu', 'editor.action.startFindReplaceAction');
                        showNotification('Replace dialog opened', 'info');
                        break;
                    case 'comment':
                        editor.trigger('menu', 'editor.action.commentLine');
                        showNotification('Toggle line comment', 'info');
                        break;
                    case 'blockcomment':
                        editor.trigger('menu', 'editor.action.blockComment');
                        showNotification('Toggle block comment', 'info');
                        break;
                    case 'format':
                        editor.trigger('menu', 'editor.action.formatDocument');
                        showNotification('Document formatted', 'info');
                        break;
                    default:
                        showNotification('Edit action not implemented: ' + action, 'error');
                }
            } catch (error) {
                console.error('Edit action failed:', action, error);
                showNotification('Action failed. Please use keyboard shortcuts instead.', 'error');
            }
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

        // Enhanced File Menu Functions
        function fileMenuNewFile(type = 'file') {
            if (type === 'window') {
                window.open(window.location.href, '_blank');
            } else {
                createNewFile();
            }
        }

        function fileMenuOpenFolder() {
            alert('Open Folder functionality would open a folder browser.\nIn this web environment, you can use the file explorer to navigate folders.');
        }

        function fileMenuOpenRecent() {
            const recentFiles = JSON.parse(localStorage.getItem('recentFiles') || '[]');
            if (recentFiles.length === 0) {
                alert('No recent files found.');
                return;
            }

            let fileList = recentFiles.map((file, index) => `${index + 1}. ${file}`).join('\n');
            const choice = prompt(`Recent Files:\n${fileList}\n\nEnter file number to open:`);

            if (choice && !isNaN(choice)) {
                const index = parseInt(choice) - 1;
                if (index >= 0 && index < recentFiles.length) {
                    openFile(recentFiles[index]);
                }
            }
        }

        function saveAll() {
            if (!currentFile || !editor) {
                alert('No files to save.');
                return;
            }
            saveFile();
            addToRecentFiles(currentFile);
        }

        function closeAllEditors() {
            if (confirm('Close all editors? Unsaved changes will be lost.')) {
                if (editor) {
                    editor.setValue('');
                    currentFile = '';
                    $('.tab span:first-child').next().text('No File');
                    $('.window-title').text('Web Code Workspace');
                }
            }
        }

        function exitApp() {
            if (confirm('Exit application? Unsaved changes will be lost.')) {
                if (confirm('This will close the browser tab. Continue?')) {
                    window.close();
                }
            }
        }

        // Enhanced Edit Menu Functions
        function selectLine() {
            if (!editor) return;
            const position = editor.getPosition();
            const line = position.lineNumber;
            editor.setSelection(new monaco.Selection(line, 1, line, 9999));
        }

        function deleteLine() {
            if (!editor) return;
            editor.trigger('menu', 'editor.action.deleteLines');
        }

        function findInFiles() {
            const searchTerm = prompt('Search in files:');
            if (searchTerm) {
                alert(`Search for "${searchTerm}" in files would open a search results panel.\nThis feature requires a file indexing system.`);
            }
        }

        function toggleBlockComment() {
            if (!editor) return;
            editor.trigger('menu', 'editor.action.blockComment');
        }

        function formatSelection() {
            if (!editor) return;
            editor.trigger('menu', 'editor.action.formatSelection');
        }

        // Enhanced View Menu Functions
        function toggleActivityBar() {
            const activityBar = $('#activitybar');
            if (activityBar.css('display') === 'none') {
                activityBar.css('display', 'flex');
            } else {
                activityBar.css('display', 'none');
            }
        }

        function toggleRenderWhitespace() {
            if (!editor) return;
            const currentOption = editor.getOption(monaco.editor.EditorOption.renderWhitespace);
            const newOption = currentOption === 'none' ? 'all' : 'none';
            editor.updateOptions({ renderWhitespace: newOption });
        }

        function toggleLineNumbers() {
            if (!editor) return;
            const currentOption = editor.getOption(monaco.editor.EditorOption.lineNumbers);
            const newOption = currentOption === 'on' ? 'off' : 'on';
            editor.updateOptions({ lineNumbers: newOption });
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Enhanced Help Menu Functions
        function showWelcome() {
            alert('Welcome to VS Code Web Clone!\n\nThis is a lightweight, browser-based code editor that mimics Visual Studio Code.\n\nFeatures:\n• Syntax highlighting for multiple languages\n• AI Assistant integration\n• Terminal access\n• File management\n• Keyboard shortcuts');
        }

        function showDocumentation() {
            window.open('https://code.visualstudio.com/docs', '_blank');
        }

        function showKeyboardShortcuts() {
            var shortcuts = "Keyboard Shortcuts:\n\nFile:\nCtrl+N    New File\nCtrl+S    Save\nCtrl+Shift+S  Save As\nCtrl+W    Close File\n\nEdit:\nCtrl+Z    Undo\nCtrl+Y    Redo\nCtrl+X    Cut\nCtrl+C    Copy\nCtrl+V    Paste\nCtrl+F    Find\nCtrl+H    Replace\nCtrl+/    Toggle Line Comment\nCtrl+A    Select All\n\nView:\nCtrl+B    Toggle Sidebar\nCtrl+`    Toggle Terminal\nF11       Fullscreen\nCtrl+=    Zoom In\nCtrl+-    Zoom Out\n\nNavigation:\nCtrl+P    Go to File\nCtrl+G    Go to Line\nCtrl+0    Toggle AI Assistant\n\nRun:\nF5        Start Debugging\nCtrl+F5   Run Without Debugging";
            alert(shortcuts);
        }

        function toggleDeveloperTools() {
            if (typeof window.devtools === 'undefined') {
                alert('Developer Tools: Use browser shortcut F12 or right-click and select "Inspect"');
            }
        }

        function showProcessExplorer() {
            alert('Process Explorer would show running processes and resource usage.\nThis feature is not available in the browser environment.');
        }

        function showAccessibilityOptions() {
            alert('Accessibility Options:\n\n• Use Alt+F1 for Screen Reader Access\n• High Contrast Theme is available in settings\n• Keyboard navigation is enabled\n• Font size can be adjusted via View menu\n• Zoom functions are available');
        }

        function checkUpdates() {
            alert('Check for Updates:\n\nThis is a web-based application.\nUpdates are deployed on the server automatically.\nVersion: 1.0.0\n\nNo manual update required.');
        }

        // Helper function to track recent files
        function addToRecentFiles(filename) {
            let recentFiles = JSON.parse(localStorage.getItem('recentFiles') || '[]');
            recentFiles = recentFiles.filter(file => file !== filename);
            recentFiles.unshift(filename);
            recentFiles = recentFiles.slice(0, 10); // Keep only 10 most recent
            localStorage.setItem('recentFiles', JSON.stringify(recentFiles));
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Helper function to format AI response
        function formatAIResponse(response) {
            if (!response) return '';

            let formatted = response;

            // Escape HTML first to prevent XSS
            formatted = escapeHtml(formatted);

            // Format code blocks with language detection and copy button
            formatted = formatted.replace(/```(\w+)?\n([\s\S]*?)```/g, function (match, lang, code) {
                const copyId = 'copy-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                return `<pre><button class="copy-code-btn" onclick="copyCode('${copyId}', this)" data-id="${copyId}">Copy</button><code id="${copyId}" class="language-${lang || 'text'}">${code}</code></pre>`;
            });

            // Format inline code
            formatted = formatted.replace(/`([^`]+)`/g, '<code>$1</code>');

            // Format bold text
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Format italic text
            formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');

            // Format lists
            formatted = formatted.replace(/^(\s*)[-*+]\s+(.+)$/gm, '$1<li>$2</li>');
            formatted = formatted.replace(/^(\s*)\d+\.\s+(.+)$/gm, '$1<li>$2</li>');

            // Wrap consecutive list items
            formatted = formatted.replace(/(<li>.*<\/li>\s*)+/gs, '<ul>$&</ul>');

            // Format line breaks
            formatted = formatted.replace(/\n\n/g, '</p><p>');
            formatted = formatted.replace(/\n/g, '<br>');

            // Add paragraph wrapper if there are multiple paragraphs
            if (formatted.includes('</p><p>')) {
                formatted = '<p>' + formatted + '</p>';
            }

            return formatted;
        }

        // Function to copy code to clipboard
        function copyCode(elementId, button) {
            const codeElement = document.getElementById(elementId);
            if (codeElement) {
                navigator.clipboard.writeText(codeElement.textContent).then(function () {
                    // Visual feedback
                    const originalText = button.textContent;
                    button.textContent = 'Copied!';
                    button.classList.add('copied');

                    setTimeout(function () {
                        button.textContent = originalText;
                        button.classList.remove('copied');
                    }, 2000);
                }).catch(function (err) {
                    console.error('Failed to copy code: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = codeElement.textContent;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);

                    button.textContent = 'Copied!';
                    button.classList.add('copied');
                    setTimeout(function () {
                        button.textContent = 'Copy';
                        button.classList.remove('copied');
                    }, 2000);
                });
            }
        }

        // AI Assistant Functions
        var autoSaveEnabled = false;
        var autoSaveInterval;

        function toggleAIPanel() {
            $('#ai-panel').toggleClass('open');
        }

        // AI Panel Resize Functionality
        var isResizingAI = false;
        var aiPanelStartWidth = 0;
        var aiPanelStartX = 0;

        $(document).ready(function () {
            // AI Panel resize handlers
            $('.ai-resize-handle').on('mousedown', function (e) {
                isResizingAI = true;
                aiPanelStartWidth = $('#ai-panel').width();
                aiPanelStartX = e.clientX;
                e.preventDefault();
            });

            $(document).on('mousemove', function (e) {
                if (!isResizingAI) return;

                var deltaX = aiPanelStartX - e.clientX;
                var newWidth = aiPanelStartWidth + deltaX;

                // Constrain to min and max width
                newWidth = Math.max(300, Math.min(800, newWidth));

                $('#ai-panel').width(newWidth);

                // Update the right position for closed state
                if (!$('#ai-panel').hasClass('open')) {
                    $('#ai-panel').css('right', -newWidth + 'px');
                }
            });

            $(document).on('mouseup', function () {
                isResizingAI = false;
            });
        });

        // Conversation Management
        let conversationId = localStorage.getItem('ai_conversation_id') || generateConversationId();

        function generateConversationId() {
            const id = 'conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('ai_conversation_id', id);
            return id;
        }

        function resetConversation() {
            conversationId = generateConversationId();
            $('#ai-messages').empty().append(`
                <div class="ai-message assistant">
                    <strong>AI:</strong><br>
                    🔄 Conversation reset. I'm ready to help with a fresh start!<br><br>
                    <em>I'll remember our conversation within this session.</em>
                </div>
            `);
        }

        function sendAIQuery() {
            const prompt = $('#ai-input').val().trim();
            if (!prompt) return;

            const selection = editor.getSelection();
            const codeContext = editor.getModel().getValueInRange(selection) || '';
            const fullContext = codeContext || editor.getValue();
            const language = $('#status-lang').text().toLowerCase();

            // Add user message with timestamp
            const timestamp = new Date().toLocaleTimeString();
            $('#ai-messages').append(`
                <div class="ai-message user">
                    <strong>You:</strong> 
                    <span class="ai-timestamp">${timestamp}</span>
                    <div class="ai-prompt-content">${escapeHtml(prompt)}</div>
                </div>
            `);

            $('#ai-input').val('');
            $('#ai-send-btn').prop('disabled', true);

            // Enhanced loading indicator
            const loadingId = 'loading-' + Date.now();
            $('#ai-messages').append(`
                <div id="${loadingId}" class="ai-message assistant ai-loading-message">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    <span class="ai-loading-text">Analyzing your request...</span>
                    <div class="ai-loading-dots">
                        <span>.</span><span>.</span><span>.</span>
                    </div>
                </div>
            `);

            // Auto-scroll
            const messagesContainer = document.getElementById('ai-messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Simulate loading states for better UX
            let loadingTexts = [
                'Analyzing your request...',
                'Processing context...',
                'Generating response...',
                'Finalizing answer...'
            ];
            let loadingIndex = 0;
            const loadingInterval = setInterval(() => {
                const textElement = $(`#${loadingId} .ai-loading-text`);
                if (textElement.length) {
                    loadingIndex = (loadingIndex + 1) % loadingTexts.length;
                    textElement.text(loadingTexts[loadingIndex]);
                }
            }, 1500);

            // Clear welcome message if this is the first query
            if ($('#ai-messages .ai-message').length === 2) {
                $('#ai-messages').empty();
            }

            $.ajax({
                url: 'ai_assistant.php',
                method: 'POST',
                timeout: 30000, // 30 second timeout
                data: JSON.stringify({
                    prompt,
                    context: fullContext,
                    language,
                    conversation_id: conversationId
                }),
                contentType: 'application/json',
                success: function (res) {
                    clearInterval(loadingInterval);
                    $('#' + loadingId).remove();

                    if (res.success) {
                        const responseTimestamp = new Date().toLocaleTimeString();
                        const formatted = formatAIResponse(res.response);

                        $('#ai-messages').append(`
                            <div class="ai-message assistant">
                                <strong>AI:</strong>
                                <span class="ai-timestamp">${responseTimestamp}</span>
                                ${res.cached ? '<span class="ai-cached-badge">⚡ Cached</span>' : ''}
                                <div class="ai-response-content">${formatted}</div>
                                <div class="ai-actions">
                                    <button class="ai-action-btn" onclick="copyToClipboard(decodeURIComponent('${encodeURIComponent(res.response)}'))" title="Copy response">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button class="ai-action-btn" onclick="regenerateResponse()" title="Regenerate response">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button class="ai-action-btn" onclick="resetConversation()" title="Start new conversation">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                    } else {
                        const errorMessage = res.error || 'Unknown error occurred';
                        $('#ai-messages').append(`
                            <div class="ai-message assistant ai-error-message">
                                <strong>⚠️ AI Error:</strong>
                                <div class="ai-error-content">${escapeHtml(errorMessage)}</div>
                                <div class="ai-error-actions">
                                    <button class="ai-action-btn" onclick="retryLastQuery()" title="Retry">
                                        <i class="fas fa-redo"></i> Retry
                                    </button>
                                    <button class="ai-action-btn" onclick="reportError('${escapeHtml(errorMessage)}')" title="Report issue">
                                        <i class="fas fa-exclamation-triangle"></i> Report
                                    </button>
                                </div>
                            </div>
                        `);
                    }

                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    $('#ai-send-btn').prop('disabled', false);
                    $('#ai-input').focus();
                }
            });
        }

        // Store last query for retry functionality
        let lastQuery = {
            prompt: '',
            context: '',
            language: ''
        };

        // Helper functions for AI actions
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                showNotification('Response copied to clipboard!', 'success');
            }).catch(function (err) {
                // Fallback
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Response copied!', 'success');
            });
        }

        function regenerateResponse() {
            if (lastQuery.prompt) {
                sendAIQuery();
            }
        }

        function retryLastQuery() {
            $('#ai-input').val(lastQuery.prompt);
            sendAIQuery();
        }

        function reportError(errorMessage) {
            showNotification('Error reported: ' + errorMessage, 'info');
            // In a real app, this would send to error tracking service
        }

        function checkAIStatus() {
            showNotification('Checking AI service status...', 'info');
            // Check if AI service is available
            $.get('ai_assistant.php', function (data) {
                if (data && !data.error) {
                    showNotification('AI service is operational', 'success');
                } else {
                    showNotification('AI service is experiencing issues', 'error');
                }
            }).fail(function () {
                showNotification('Cannot connect to AI service', 'error');
            });
        }

        function showNotification(message, type = 'info') {
            const notification = $(`
                <div class="ai-notification ai-notification-${type}">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle"></i>
                    ${message}
                </div>
            `);

            $('body').append(notification);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                notification.addClass('fade-out');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }



        // Update sendAIQuery to store last query
        function initAIQueryTracking() {
            const originalSendAIQuery = sendAIQuery;

            sendAIQuery = function () {
                const prompt = $('#ai-input').val().trim();
                if (!prompt) return;

                const selection = editor.getSelection();
                const codeContext = editor.getModel().getValueInRange(selection) || '';
                const fullContext = codeContext || editor.getValue();
                const language = $('#status-lang').text().toLowerCase();

                // Store for retry
                lastQuery = { prompt, context: fullContext, language };

                // Call original function
                return originalSendAIQuery.call(this);
            };
        }

        // File Operations
        function handleFileUpload(event) {
            const files = event.target.files;
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $.post('file_manager.php', { action: 'write', path: file.name, content: e.target.result }, function (res) {
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
                $.post('file_manager.php', { action: 'write', path: newName, content }, function (res) {
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

        // Add Ctrl+I keybinding for AI (removed nested ready)
        // Moved to main $(document).ready() above

        // Context Menu Functions
        var contextMenuNode = null;

        function showContextMenu(x, y, node) {
            contextMenuNode = node;
            $('#context-menu').css({
                left: x + 'px',
                top: y + 'px',
                display: 'block'
            });
        }

        function contextMenuAction(action) {
            $('#context-menu').hide();
            if (!contextMenuNode) return;

            // Determine target path
            let targetPath = contextMenuNode.path;
            if (contextMenuNode.type === 'file') {
                // If it's a file, get its directory
                targetPath = targetPath.substring(0, targetPath.lastIndexOf('/'));
            }
            if (!targetPath) targetPath = '';

            switch (action) {
                case 'new-file':
                    createNewFile(targetPath);
                    break;
                case 'new-folder':
                    createNewFolder(targetPath);
                    break;
                case 'open':
                    if (contextMenuNode.type === 'file') {
                        openFile(contextMenuNode.path);
                    }
                    break;
                case 'rename':
                    renameItem(contextMenuNode);
                    break;
                case 'delete':
                    deleteItem(contextMenuNode);
                    break;
                case 'copy-path':
                    navigator.clipboard.writeText(contextMenuNode.path);
                    break;
            }
            contextMenuNode = null;
        }

        function createNewFile(basePath = '') {
            let fileName = prompt("Enter file name:");
            if (fileName) {
                let fullPath = basePath ? basePath + '/' + fileName : fileName;
                // Handle duplicate slashes
                fullPath = fullPath.replace('//', '/');

                $.post('file_manager.php', { action: 'create', file: fullPath }, function (res) {
                    if (res.success) {
                        loadExplorer();
                        openFile(fullPath);
                    } else {
                        alert('Error creating file: ' + res.error);
                    }
                }, 'json');
            }
        }

        function createNewFolder(basePath = '') {
            let folderName = prompt("Enter folder name:");
            if (folderName) {
                let fullPath = basePath ? basePath + '/' + folderName : folderName;
                fullPath = fullPath.replace('//', '/');

                $.post('file_manager.php', { action: 'create_folder', folder: fullPath }, function (res) {
                    if (res.success) {
                        loadExplorer();
                    } else {
                        alert('Error creating folder: ' + res.error);
                    }
                }, 'json');
            }
        }

        function renameItem(node) {
            const newName = prompt('Rename to:', node.name);
            if (!newName || newName === node.name) return;

            const pathParts = node.path.split('/');
            pathParts[pathParts.length - 1] = newName;
            const newPath = pathParts.join('/');

            $.post('file_operations_enhanced.php', {
                action: 'rename',
                old_path: node.path,
                new_path: newPath
            }, function (res) {
                if (res.success) {
                    loadExplorer();
                    if (currentFile === node.path) {
                        currentFile = newPath;
                    }
                } else {
                    alert('Error: ' + res.error);
                }
            }, 'json');
        }

        function deleteItem(node) {
            const confirmMsg = node.type === 'folder'
                ? 'Delete folder "' + node.name + '" and all its contents?'
                : 'Delete file "' + node.name + '"?';

            if (!confirm(confirmMsg)) return;

            $.post('file_operations_enhanced.php', {
                action: 'delete_recursive',
                path: node.path
            }, function (res) {
                if (res.success) {
                    loadExplorer();
                    if (currentFile === node.path) {
                        closeFile();
                    }
                } else {
                    alert('Error: ' + res.error);
                }
            }, 'json');
        }
    </script>

    <!-- Context Menu -->
    <div id="context-menu">
        <div class="context-menu-item" onclick="contextMenuAction('new-file')">
            <i class="fas fa-file-plus"></i> New File
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('new-folder')">
            <i class="fas fa-folder-plus"></i> New Folder
        </div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="contextMenuAction('open')">
            <i class="fas fa-folder-open"></i> Open
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('rename')">
            <i class="fas fa-edit"></i> Rename
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('delete')">
            <i class="fas fa-trash"></i> Delete
        </div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="contextMenuAction('copy-path')">
            <i class="fas fa-copy"></i> Copy Path
        </div>
    </div>

    <!-- AI Assistant Panel -->
    <div id="ai-panel">
        <div class="ai-resize-handle"></div>
        <div class="ai-header d-flex justify-content-between align-items-center">
            <h3 class="m-0"><i class="fas fa-robot me-2"></i>AI Assistant</h3>
            <span class="ai-close" onclick="toggleAIPanel()"><i class="fas fa-times"></i></span>
        </div>
        <div class="ai-content d-flex flex-column">
            <div class="ai-messages flex-grow-1" id="ai-messages">
                <div class="ai-message assistant">
                    <strong>AI Assistant:</strong><br>
                    👋 Hello! I'm here to help you with your coding tasks.<br><br>
                    You can ask me to:<br>
                    • Explain code and suggest improvements<br>
                    • Debug issues and fix errors<br>
                    • Generate code snippets<br>
                    • Help with best practices<br><br>
                    <em>Press Enter to send, Shift+Enter for new line, or use the Send button.</em>
                </div>
            </div>
            <div class="ai-input-area">
                <div class="input-group">
                    <textarea id="ai-input" class="form-control" rows="1"
                        placeholder="Type a message... (Ctrl+Enter to send)"></textarea>
                    <button class="btn btn-vscode" type="button" onclick="sendAIQuery()" id="ai-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>