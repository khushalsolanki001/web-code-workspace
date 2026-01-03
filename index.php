<!DOCTYPE html>
<html>
<head>
    <title>MCA Pro Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', 'Roboto', sans-serif; 
            overflow: hidden; 
        }
        .custom-toast { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            padding: 15px 25px; 
            border-radius: 12px; 
            color: white; 
            z-index: 1000; 
            display: none; 
            font-weight: 600; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.15); 
            backdrop-filter: blur(10px);
        }
        .bg-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .bg-error { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .bg-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        
        /* Professional styling */
        .code-editor-container {
            background: #0f172a;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .window-controls {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        
        .window-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            body { overflow-y: auto; }
            .custom-toast { 
                top: 10px; 
                right: 10px; 
                left: 10px; 
                padding: 12px 16px; 
                font-size: 13px; 
                border-radius: 10px;
            }
            
            /* Chat panel - smaller on mobile */
            .w-full.md\:w-1\/4 { 
                width: 100% !important; 
                height: 35vh !important; 
                max-height: 35vh !important;
            }
            
            /* Code editor - larger on mobile */
            .w-full.md\:w-3\/4 { 
                width: 100% !important; 
                height: 65vh !important; 
                min-height: 65vh !important;
            }
            
            #code { 
                font-size: 16px !important; 
                padding: 16px !important; 
                line-height: 1.6 !important;
            }
            
            /* Smaller button */
            button[onclick="saveCode()"] {
                padding: 10px 16px !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                letter-spacing: 0.5px !important;
            }
            
            #aiChatWindow { 
                width: calc(100vw - 20px) !important; 
                height: calc(100vh - 100px) !important; 
                max-width: none !important; 
                border-radius: 16px;
            }
            #aiChatWidget { bottom: 10px !important; right: 10px !important; }
            #aiChatWidget button { padding: 14px !important; }
            header { 
                padding: 12px !important; 
                font-size: 11px !important; 
                letter-spacing: 1px;
            }
            .text-xl { font-size: 18px !important; }
            .text-sm { font-size: 13px !important; }
            .text-xs { font-size: 11px !important; }
            .p-6 { padding: 16px !important; }
            .p-4 { padding: 12px !important; }
            .p-3 { padding: 10px !important; }
            .mb-4 { margin-bottom: 12px !important; }
            .mt-4 { margin-top: 12px !important; }
            button:not([onclick="saveCode()"]) { 
                font-size: 12px !important; 
                padding: 8px 14px !important; 
            }
            input, textarea { font-size: 15px !important; }
            #chatBox { max-height: calc(35vh - 140px) !important; }
            
            /* Window controls - compact on mobile */
            .window-controls {
                gap: 4px;
            }
            .window-dot {
                width: 10px;
                height: 10px;
            }
        }
        
        @media (max-width: 480px) {
            #code { font-size: 15px !important; padding: 14px !important; }
            .text-xl { font-size: 16px !important; }
            .text-sm { font-size: 12px !important; }
            .text-xs { font-size: 10px !important; }
            #aiChatWindow { height: calc(100vh - 80px) !important; }
            button[onclick="saveCode()"] {
                padding: 8px 12px !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col">

<div id="toast" class="custom-toast"></div>

<header class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-4 text-center font-bold text-sm tracking-widest uppercase shadow-lg">
    MCA Peer Collaboration & Code Sync
</header>

<div class="flex flex-1 overflow-hidden flex-col md:flex-row">
    <div class="w-full md:w-1/4 flex flex-col border-r bg-white shadow-sm">
        <div id="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50"></div>
        
        <div class="p-4 border-t bg-white shadow-lg">
            <form id="chatForm" class="space-y-3">
                <input type="text" id="username" name="username" placeholder="Full Name" class="w-full border-2 border-gray-200 p-2.5 text-sm rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                <textarea id="msgInput" name="message" placeholder="Type your message..." class="w-full border-2 border-gray-200 p-2.5 text-sm rounded-lg h-20 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none resize-none transition-all"></textarea>
                <div class="flex items-center justify-between gap-2">
                    <input type="file" name="file" id="fileInput" class="text-xs text-gray-500 flex-1 text-xs border border-gray-200 rounded-lg px-2 py-1.5">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white px-4 py-2 text-xs rounded-lg font-bold hover:from-indigo-700 hover:to-indigo-600 whitespace-nowrap shadow-md transition-all active:scale-95">SEND</button>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full md:w-3/4 flex flex-col p-6 bg-slate-800 code-editor-container">
        <div class="flex justify-between items-center mb-4">
            <div class="window-controls">
                <span class="window-dot bg-red-500"></span>
                <span class="window-dot bg-yellow-500"></span>
                <span class="window-dot bg-green-500"></span>
                <h2 class="text-slate-300 font-mono text-sm ml-3 hidden md:block"></h2>
            </div>
            <div class="flex gap-2">
                <button onclick="copyCode()" class="text-slate-300 hover:text-white hover:bg-slate-700 text-xs border border-slate-600 px-3 py-1.5 rounded-md transition-all font-medium">Copy</button>
                <button onclick="refreshCode()" class="text-slate-300 hover:text-white hover:bg-slate-700 text-xs border border-slate-600 px-3 py-1.5 rounded-md transition-all font-medium">Sync</button>
            </div>
        </div>
        
        <textarea id="code" spellcheck="false" class="flex-1 w-full bg-slate-900 text-blue-300 p-6 font-mono text-xl border border-slate-700 rounded-lg outline-none shadow-2xl leading-relaxed resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
        
        <button onclick="saveCode()" class="mt-4 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white py-3 rounded-lg font-bold text-base tracking-wide transition-all shadow-lg hover:shadow-xl active:scale-[0.98] w-full">
            UPDATE GLOBAL REPOSITORY
        </button>
    </div>
</div>

<!-- AI Chat Widget -->
<div id="aiChatWidget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <!-- Chat Window -->
    <div id="aiChatWindow" class="bg-white w-80 h-96 rounded-xl shadow-2xl border border-gray-200 flex flex-col hidden mb-4 overflow-hidden max-w-[calc(100vw-20px)]">
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-3 flex justify-between items-center rounded-t-xl">
            <span class="font-bold text-sm">AI Assistant</span>
            <button onclick="toggleAiChat()" class="text-gray-300 hover:text-white text-xl leading-none w-6 h-6 flex items-center justify-center rounded hover:bg-slate-700 transition-all">&times;</button>
        </div>
        <div id="aiChatMessages" class="flex-1 p-3 overflow-y-auto space-y-3 bg-gray-50 text-sm">
            <!-- Messages go here -->
            <div class="flex justify-start">
                <div class="bg-gray-200 text-gray-800 rounded-lg py-2 px-3 max-w-[85%] rounded-tl-none">
                    Hello! How can I help you with your code today?
                </div>
            </div>
        </div>
        <div class="p-3 border-t bg-white">
            <form id="aiChatForm" class="flex gap-2">
                <input type="text" id="aiInput" class="flex-1 border-2 border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all" placeholder="Ask AI..." autocomplete="off">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white px-4 py-2 rounded-lg text-xs font-bold hover:from-indigo-700 hover:to-indigo-600 whitespace-nowrap shadow-md transition-all active:scale-95">SEND</button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button onclick="toggleAiChat()" class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-4 rounded-full shadow-xl hover:shadow-2xl hover:from-slate-800 hover:to-slate-700 transition-all hover:scale-110 active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </button>
</div>

<script>
    // Custom Notification Function (No Localhost Alert)
    function showNotify(msg, type='info') {
        const toast = $("#toast");
        toast.removeClass('bg-success bg-error bg-info').addClass('bg-'+type);
        toast.text(msg).fadeIn().delay(3000).fadeOut();
    }

    function loadMessages(){
        $.get("fetch.php", d => {
            $("#chatBox").html(d);
            $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
        });
    }

    // Validation for Code Save
    function saveCode(){
        let codeVal = $("#code").val().trim();
        if(codeVal == ""){
            showNotify("⚠️ Cannot save empty code!", "error");
            return;
        }
        $.post("save_code.php", {code: codeVal}, function(){
            showNotify("🚀 Code synced successfully for all users!", "success");
        });
    }

    function refreshCode(){
        $.get("save_code.php", d => {
            if(!$("#code").is(":focus")) { $("#code").val(d); }
        });
    }

    function copyCode(){
        navigator.clipboard.writeText($("#code").val());
        showNotify("📋 Code copied to clipboard", "info");
    }

    function deleteMsg(id){
        // Custom Styled Confirmation could be added here, using simple confirm for now
        if(confirm("Confirm deletion of this record?")){
            $.get("delete.php?id="+id, () => {
                loadMessages();
                showNotify("🗑️ Message removed from server", "info");
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

        $.ajax({
            url: "send.php",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: () => { 
                this.reset(); 
                loadMessages(); 
                showNotify("📩 Message Sent", "success");
            }
        });
    });

    // AI Chat Functions
    function toggleAiChat() {
        $("#aiChatWindow").toggleClass("hidden");
        if (!$("#aiChatWindow").hasClass("hidden")) {
            $("#aiInput").focus();
        }
    }

    $("#aiChatForm").submit(function(e) {
        e.preventDefault();
        let msg = $("#aiInput").val().trim();
        if (msg === "") return;

        // Add user message
        $("#aiChatMessages").append(`
            <div class="flex justify-end">
                <div class="bg-indigo-600 text-white rounded-lg py-2 px-3 max-w-[85%] rounded-tr-none">
                    ${$('<div>').text(msg).html()}
                </div>
            </div>
        `);
        
        $("#aiInput").val("");
        $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

        // Add loading indicator
        let loadingId = 'loading-' + Date.now();
        $("#aiChatMessages").append(`
            <div id="${loadingId}" class="flex justify-start">
                <div class="bg-gray-200 text-gray-800 rounded-lg py-2 px-3 max-w-[85%] rounded-tl-none italic text-xs">
                    Thinking...
                </div>
            </div>
        `);
        $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);

        // Call API
        $.ajax({
            url: "ai_chat.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ message: msg }),
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
                    // Hugging Face Response Structure
                    reply = response[0].generated_text;
                } else if (response.candidates && response.candidates.length > 0) {
                    // Gemini Response Structure (fallback)
                    reply = response.candidates[0].content.parts[0].text;
                } else if (response.choices && response.choices.length > 0) {
                    // OpenAI Response Structure (fallback)
                    reply = response.choices[0].message.content;
                }

                // Format simple markdown (newlines) to br
                reply = reply.replace(/\n/g, '<br>');

                $("#aiChatMessages").append(`
                    <div class="flex justify-start">
                        <div class="bg-gray-200 text-gray-800 rounded-lg py-2 px-3 max-w-[85%] rounded-tl-none">
                            ${reply}
                        </div>
                    </div>
                `);
                $("#aiChatMessages").scrollTop($("#aiChatMessages")[0].scrollHeight);
            },
            error: function() {
                $(`#${loadingId}`).remove();
                showNotify("❌ Failed to contact AI", "error");
            }
        });
    });

    setInterval(loadMessages, 3000);
    setInterval(refreshCode, 4000); 
    loadMessages();
    refreshCode();
</script>
</body>
</html>