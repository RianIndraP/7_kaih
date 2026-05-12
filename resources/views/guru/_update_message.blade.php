@if($updateMessage)
<div id="updateMessageBox" class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg anim-fade-up relative">
    <button onclick="toggleUpdateMessage()" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition text-white text-sm" title="Sembunyikan/Tampilkan">
        <svg id="updateToggleIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div id="updateMessageContent" class="flex items-start gap-3">
        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1 pr-6">
            <h3 class="font-semibold text-sm mb-2">📢 Informasi Update</h3>
            <div class="update-message-text text-sm text-white/95 leading-relaxed">{!! $updateMessage !!}</div>
        </div>
    </div>
    <button id="updateShowBtn" onclick="toggleUpdateMessage()" class="hidden w-full flex items-center justify-center gap-2 py-2 text-white/80 hover:text-white text-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7M5 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
        <span>Tampilkan informasi update</span>
    </button>
</div>
<style>
    .update-message-text h1 { font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0.5rem; }
    .update-message-text h2 { font-size: 1.1rem; font-weight: 600; margin: 0.6rem 0 0.4rem; }
    .update-message-text h3 { font-size: 1rem; font-weight: 600; margin: 0.5rem 0 0.3rem; }
    .update-message-text h4 { font-size: 0.95rem; font-weight: 600; margin: 0.4rem 0 0.2rem; }
    .update-message-text p { margin: 0.4rem 0; }
    .update-message-text ul, .update-message-text ol { margin: 0.5rem 0; padding-left: 1.5rem; }
    .update-message-text ul { list-style-type: disc !important; }
    .update-message-text ol { list-style-type: decimal !important; }
    .update-message-text li { margin: 0.25rem 0; }
    .update-message-text ul ul { list-style-type: circle !important; }
    .update-message-text ol ol { list-style-type: lower-alpha !important; }
    .update-message-text strong { font-weight: 600; }
    .update-message-text em { font-style: italic; }
    .update-message-text a { color: #bfdbfe; text-decoration: underline; }
    .update-message-text a:hover { color: #ffffff; }
</style>
<script>
    function toggleUpdateMessage() {
        const content = document.getElementById('updateMessageContent');
        const showBtn = document.getElementById('updateShowBtn');
        const icon = document.getElementById('updateToggleIcon');
        const isHidden = content.classList.contains('hidden');
        const storageKey = 'updateMsg_' + window.location.pathname.split('/')[1];
        
        if (isHidden) {
            content.classList.remove('hidden'); showBtn.classList.add('hidden');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
            localStorage.setItem(storageKey, 'false');
        } else {
            content.classList.add('hidden'); showBtn.classList.remove('hidden');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
            localStorage.setItem(storageKey, 'true');
        }
    }
    
    // Restore state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const storageKey = 'updateMsg_' + window.location.pathname.split('/')[1];
        const isHidden = localStorage.getItem(storageKey) === 'true';
        if (isHidden) {
            const content = document.getElementById('updateMessageContent');
            const showBtn = document.getElementById('updateShowBtn');
            const icon = document.getElementById('updateToggleIcon');
            if (content && showBtn && icon) {
                content.classList.add('hidden'); showBtn.classList.remove('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
            }
        }
    });
</script>
@endif
