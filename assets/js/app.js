// Monaco Editor Configuration
require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });

// Global state
let editor = null;
let currentSidebar = 'chat';
let selectedFile = null;
let openFiles = {}; // { filePath: { name, content, modified, viewState } }
let activeTab = 'LIVE';

require(['vs/editor/editor.main'], function () {
    // Initialize Editor
    editor = monaco.editor.create(document.getElementById('container'), {
        value: "Loading...",
        language: 'php',
        theme: 'vs-dark',
        automaticLayout: true,
        fontSize: 14,
        minimap: { enabled: true },
        scrollBeyondLastLine: false,
        padding: { top: 10 }
    });

    // Editor Events
    editor.onDidChangeModelContent(() => {
        if (activeTab && openFiles[activeTab]) {
            openFiles[activeTab].modified = true;
            const tab = $(`.editor-tab[data-file="${activeTab.replace(/"/g, '&quot;')}"]`);
            const tabName = tab.find(".tab-name");
            if (!tabName.text().includes("*")) {
                tabName.text(openFiles[activeTab].name + " *");
            }
        }
    });

    editor.onDidChangeCursorPosition((e) => {
        updateCursorPosition(e.position);
    });

    // Initial Load
    loadMessages();
    loadFiles();
    switchToLiveTab();

    // Set Intervals
    setInterval(loadMessages, 3000);
    setInterval(loadFiles, 5000);
    setInterval(refreshCode, 4000); // Live sync
});

// --- Helper Functions ---

function getLanguageFromPath(path) {
    if (!path) return 'plaintext';
    const ext = path.split('.').pop().toLowerCase();
    const map = {
        'js': 'javascript', 'php': 'php', 'html': 'html', 'css': 'css',
        'sql': 'sql', 'json': 'json', 'md': 'markdown', 'py': 'python',
        'java': 'java', 'cpp': 'cpp', 'c': 'c', 'cs': 'csharp'
    };
    return map[ext] || 'plaintext';
}

function updateCursorPosition(position) {
    if (!position && editor) position = editor.getPosition();
    if (!position) return;
    $(".statusbar-item:contains('Ln')").html(`Ln ${position.lineNumber}, Col ${position.column}`);
}

function updateStatusBar(fileName, language) {
    if (fileName) {
        let lang = language || getLanguageFromPath(fileName);
        $(".statusbar-item:contains('PHP')").html(lang.toUpperCase());
    } else {
        $(".statusbar-item:contains('PHP')").html('LIVE');
    }
}

// --- Tab & File Management ---

function openFileInEditor(filePath, fileName) {
    if (openFiles[filePath]) {
        switchToTab(filePath);
        return;
    }

    $.get("file_operations.php?action=read&file=" + encodeURIComponent(filePath), function (response) {
        if (response.error) {
            showNotify("❌ " + response.error, "error");
            return;
        }

        openFiles[filePath] = {
            name: fileName,
            content: response.content,
            modified: false,
            model: null
        };

        createTab(filePath, fileName);
        switchToTab(filePath);

    }, 'json').fail(function () {
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

    tab.on('click', function (e) {
        if (!$(e.target).hasClass('tab-close')) {
            switchToTab(filePath);
        }
    });

    $("#editorTabs").append(tab);
}

function switchToTab(filePath) {
    if (!editor) return;

    // Save state of current tab
    if (activeTab && openFiles[activeTab]) {
        openFiles[activeTab].content = editor.getValue();
        openFiles[activeTab].viewState = editor.saveViewState();
    } else if (activeTab === 'LIVE') {
        // Live tab syncs separately
    }

    $(".editor-tab").removeClass("active");

    // Handle LIVE tab special case
    if (filePath === 'LIVE') {
        $("#liveTab").addClass("active");
        switchToLiveTab();
        return;
    }

    const tab = $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`);
    if (tab.length === 0) return;
    tab.addClass("active");

    activeTab = filePath;

    if (openFiles[filePath]) {
        const lang = getLanguageFromPath(filePath);
        const model = monaco.editor.createModel(openFiles[filePath].content, lang);
        editor.setModel(model);

        if (openFiles[filePath].viewState) {
            editor.restoreViewState(openFiles[filePath].viewState);
        }

        $("#container").show();
        $("#noFileOpen").hide();

        updateStatusBar(openFiles[filePath].name, lang);
    }
}

function switchToLiveTab() {
    if (activeTab !== 'LIVE' && openFiles[activeTab]) {
        openFiles[activeTab].content = editor.getValue();
        openFiles[activeTab].viewState = editor.saveViewState();
    }

    $(".editor-tab").removeClass("active");
    $("#liveTab").addClass("active");
    activeTab = 'LIVE';

    $.get("save_code.php", function (d) {
        if (!editor) return;
        const model = monaco.editor.createModel(d, 'php');
        editor.setModel(model);

        $("#container").show();
        $("#noFileOpen").hide();
        updateStatusBar('LIVE', 'php');
    });
}

function closeTab(filePath) {
    if (openFiles[filePath] && openFiles[filePath].modified) {
        if (!confirm("File has unsaved changes. Close anyway?")) {
            return;
        }
    }

    $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`).remove();
    delete openFiles[filePath];

    if (activeTab === filePath) {
        const remainingTabs = Object.keys(openFiles);
        if (remainingTabs.length > 0) {
            switchToTab(remainingTabs[remainingTabs.length - 1]);
        } else {
            switchToLiveTab();
        }
    }
}

function refreshCode() {
    if (activeTab !== 'LIVE') return;
    if (editor && editor.hasTextFocus()) return; // Don't interrupt typing

    $.get("save_code.php", function (d) {
        if (editor) {
            const currentVal = editor.getValue();
            if (currentVal !== d) {
                const pos = editor.getPosition();
                editor.setValue(d);
                editor.setPosition(pos);
            }
        }
    });
}

function saveCode() {
    if (!editor) return;
    const content = editor.getValue();

    if (activeTab === 'LIVE') {
        $.post("save_code.php", { code: content }, function () {
            showNotify("✅ Live editor synced!", "success");
        }).fail(() => showNotify("❌ Sync failed", "error"));
        return;
    }

    const filePath = activeTab;
$.post("file_operations.php", {
        action: 'save',
        file: filePath,
        content: content
    }, function (response) {
        if (response.error) {
            showNotify("❌ " + response.error, "error");
        } else {
            showNotify("✅ File saved!", "success");
            if (openFiles[filePath]) {
                openFiles[filePath].modified = false;
                openFiles[filePath].content = content;
                updateTabName(filePath);
            }
        }
    }, 'json').fail(() => showNotify("❌ Save failed", "error"));
}

function updateTabName(filePath) {
    if (openFiles[filePath]) {
        const tab = $(`.editor-tab[data-file="${filePath.replace(/"/g, '&quot;')}"]`);
        tab.find(".tab-name").text(openFiles[filePath].name);
    }
}

// --- UI Interaction ---

function showNotify(msg, type = 'info') {
    const toast = $("#toast");
    toast.removeClass('bg-success bg-error bg-info').addClass('bg-' + type);
    toast.text(msg).fadeIn().delay(3000).fadeOut();
}

function loadMessages() {
    $.get("fetch.php", d => {
        $("#chatBox").html(d);
    });
}

function loadFiles() {
    $.get("get_files.php", function (files) {
        const explorer = $("#fileExplorer");
        explorer.empty();

        // Add "New File" Header/Button
        explorer.append(`
            <div style="padding: 0 12px 8px; font-size: 11px; text-transform: uppercase; color: var(--vscode-text-secondary); display: flex; justify-content: space-between; align-items: center;">
                <span>Files</span>
                <span style="cursor: pointer; padding: 2px;" title="New File (Not implemented)" onclick="showNotify('New File feature coming soon!', 'info')">+</span>
            </div>
        `);

        if (files.length === 0) {
            explorer.html('<div style="padding: 12px; color: #858585; font-size: 12px;">No files uploaded yet</div>');
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

function deleteFile(filePath) {
    if (!confirm("Delete this file?")) return;
    const pwd = prompt("Enter password (203):");
    if (pwd !== '203') {
        showNotify("❌ Incorrect password", "error");
        return;
    }

    if (openFiles[filePath]) closeTab(filePath);

$.post("file_operations.php", {
        action: 'delete',
        file: filePath
    }, function (response) {
        if (response.error) showNotify("❌ " + response.error, "error");
        else {
            showNotify("✅ File deleted", "success");
            loadFiles();
        }
    }, 'json');
}

function toggleSidebar(type) {
    currentSidebar = type;
    $(".activitybar-item").removeClass("active");
    event.currentTarget.classList.add("active");

if (type === 'chat') {
        $("#sidebarTitle").text("Chat");
        $("#chatView").show();
        $("#explorerView").hide();
        $("#chatInputContainer").show();
    } else if (type === 'explorer') {
        $("#sidebarTitle").text("Explorer");
        $("#chatView").hide();
        $("#explorerView").show();
        $("#chatInputContainer").hide();
        loadFiles();
} else {
        $("#sidebarTitle").text("Search");
        $("#chatView").hide();
        $("#explorerView").hide();
        $("#chatInputContainer").hide();
    }
}

function copyCode() {
    if (!editor) return;
    navigator.clipboard.writeText(editor.getValue());
    showNotify("📋 Code copied to clipboard");
}

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
        let newWidth = isLeft ? e.clientX - rect.left : rect.right - e.clientX;
        sidebar.style.width = Math.max(200, Math.min(600, newWidth)) + 'px';
    }
    function stopResize() {
        isResizing = false;
        resizer.classList.remove('resizing');
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseup', stopResize);
    }
}

// AI Chat Integration
function insertCodeIntoEditor(code) {
    if (!editor) return;
    const contribution = editor.getContribution('snippetController2');
    if (contribution) {
        contribution.insert(code);
    } else {
        editor.trigger('keyboard', 'type', { text: code });
    }
    showNotify("✅ Code inserted");
    editor.focus();
}

// Menu Functions
function triggerMenuAction(action) {
    if (action === 'save') saveCode();
    if (action === 'copy') copyCode();
    if (action === 'new') showNotify("New File: feature coming soon", "info");
    if (action === 'open') toggleSidebar('explorer');
}

// Add these to window for global access
window.openFileInEditor = openFileInEditor;
window.deleteFile = deleteFile;
window.toggleSidebar = toggleSidebar;
window.saveCode = saveCode;
window.copyCode = copyCode;
window.refreshCode = refreshCode;
window.insertCodeIntoEditor = insertCodeIntoEditor;
window.switchToLiveTab = switchToLiveTab;
window.closeTab = closeTab;
window.triggerMenuAction = triggerMenuAction;

// Initialization
$(document).ready(function () {
    // Resizers
    makeResizable(document.getElementById('leftResizer'), document.getElementById('leftSidebar'), true);
    makeResizable(document.getElementById('rightResizer'), document.getElementById('rightSidebar'), false);

    // Keyboard Shortcuts
    $(document).on("keydown", function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveCode();
        }
    });

    // Chat Forms
    $("#chatForm").submit(function (e) {
        e.preventDefault();
        let name = $("#username").val().trim();
        let msg = $("#msgInput").val().trim();
        let file = $("#fileInput").val();

        if (name == "") return showNotify("❌ Name required", "error");
        if (msg == "" && file == "") return showNotify("❌ Message required", "error");

let formData = new FormData(this);

        $.ajax({
            url: "send.php", type: "POST", data: formData,
            processData: false, contentType: false,
            success: () => {
                this.reset(); loadMessages(); loadFiles();
                showNotify("📩 Sent");
            }
        });
    });

    $("#aiChatForm").submit(function (e) {
        e.preventDefault();
        let msg = $("#aiInput").val().trim();
        if (msg === "") return;

        let currentCode = editor ? editor.getValue() : "";

        $("#aiChatMessages").append(`
            <div class="ai-message user">
                <div style="font-weight: 600; margin-bottom: 4px;">You</div>
                <div>${$('<div>').text(msg).html()}</div>
            </div>
        `);
        $("#aiInput").val("");
        $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

        let loadingId = 'loading-' + Date.now();
        $("#aiChatMessages").append(`
            <div id="${loadingId}" class="ai-message assistant">
                <div style="font-style: italic; opacity: 0.7;">
                    <span class="inline-block animate-pulse">💭 Analyzing...</span>
                </div>
            </div>
        `);
        $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

        $.ajax({
            url: "ai_chat.php", type: "POST", contentType: "application/json",
            data: JSON.stringify({ message: msg, currentCode: currentCode }),
            success: function (response) {
                $(`#${loadingId}`).remove();

                let reply = "No response.";
                if (response.error) reply = "Error: " + (response.error.message || response.error);
                else if (response.choices && response.choices.length > 0) reply = response.choices[0].message.content;

                let messageId = 'msg-' + Date.now();
                let formatted = (window.marked) ? marked.parse(reply) : reply;

                $("#aiChatMessages").append(`
                    <div id="${messageId}" class="ai-message assistant">
                        <div style="font-weight: 600; margin-bottom: 4px; color: #4ec9b0;">AI Assistant</div>
                        <div class="prose prose-sm max-w-none" style="color: #cccccc;">${formatted}</div>
                    </div>
                `);

                // Highlight code blocks
                $(`#${messageId} pre code`).each(function () {
                    if (window.hljs) hljs.highlightElement(this);

                    // Add insert button
                    let code = $(this).text();
                    let btn = $(`<button class="chat-button" style="width:100%; margin-top:5px;">📋 Insert Code</button>`);
                    btn.click(() => insertCodeIntoEditor(code));
                    $(this).parent().after(btn);
                });

                $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);
            },
            error: function () {
                $(`#${loadingId}`).remove();
                showNotify("❌ AI Error", "error");
            }
        });
    });
});
