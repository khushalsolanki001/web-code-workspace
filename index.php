<!DOCTYPE html>
<html>
<head>
    <title>MCA Pro Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .custom-toast { position: fixed; top: 20px; right: 20px; padding: 15px 25px; border-radius: 8px; color: white; z-index: 1000; display: none; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .bg-success { background: #10b981; }
        .bg-error { background: #ef4444; }
        .bg-info { background: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col">

<div id="toast" class="custom-toast"></div>

<header class="bg-slate-900 text-white p-3 text-center font-bold text-sm tracking-widest uppercase">
    MCA Peer Collaboration & Code Sync
</header>

<div class="flex flex-1 overflow-hidden">
    <div class="w-1/4 flex flex-col border-r bg-white">
        <div id="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50"></div>
        
        <div class="p-4 border-t shadow-2xl">
            <form id="chatForm" class="space-y-3">
                <input type="text" id="username" name="username" placeholder="Full Name" class="w-full border-2 p-2 text-sm rounded-md focus:border-indigo-500 outline-none">
                <textarea id="msgInput" name="message" placeholder="Type your message..." class="w-full border-2 p-2 text-sm rounded-md h-20 focus:border-indigo-500 outline-none"></textarea>
                <div class="flex items-center justify-between">
                    <input type="file" name="file" id="fileInput" class="text-[10px] text-gray-400 w-2/3">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 text-xs rounded-md font-bold hover:bg-indigo-700">SEND</button>
                </div>
            </form>
        </div>
    </div>

    <div class="w-3/4 flex flex-col p-6 bg-slate-800">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <h2 class="text-slate-300 font-mono text-sm ml-2">live_sync_editor.php</h2>
            </div>
            <div class="flex gap-3">
                <button onclick="copyCode()" class="text-slate-400 hover:text-white text-xs border border-slate-600 px-3 py-1 rounded">Copy</button>
                <button onclick="refreshCode()" class="text-slate-400 hover:text-white text-xs border border-slate-600 px-3 py-1 rounded">Sync</button>
            </div>
        </div>
        
        <textarea id="code" spellcheck="false" class="flex-1 w-full bg-slate-900 text-blue-300 p-6 font-mono text-xl border border-slate-700 rounded-lg outline-none shadow-2xl leading-relaxed"></textarea>
        
        <button onclick="saveCode()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-4 rounded-lg font-black text-xl tracking-widest transition-all shadow-lg active:scale-[0.99]">
            UPDATE GLOBAL REPOSITORY
        </button>
    </div>
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

    setInterval(loadMessages, 3000);
    setInterval(refreshCode, 4000); 
    loadMessages();
    refreshCode();
</script>
</body>
</html>