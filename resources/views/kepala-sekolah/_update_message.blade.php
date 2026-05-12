@if($updateMessage)
<div id="updateMessageBox" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 16px; color: white; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); position: relative;">
    <button onclick="toggleUpdateMessage()" style="position: absolute; top: 8px; right: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 50%; border: none; color: white; cursor: pointer;" title="Sembunyikan/Tampilkan">
        <svg id="updateToggleIcon" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div id="updateMessageContent" style="display: flex; align-items: start; gap: 12px;">
        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div style="flex: 1; padding-right: 24px;">
            <h3 style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">📢 Informasi Update</h3>
            <div class="update-message-text" style="font-size: 14px; opacity: 0.95; line-height: 1.5;">{!! $updateMessage !!}</div>
        </div>
    </div>
    <button id="updateShowBtn" onclick="toggleUpdateMessage()" style="display: none; width: 100%; align-items: center; justify-content: center; gap: 8px; padding: 8px; color: rgba(255,255,255,0.8); background: transparent; border: none; cursor: pointer; font-size: 14px;">
        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7M5 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
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
</style>
<script>
    function toggleUpdateMessage() {
        const content = document.getElementById('updateMessageContent');
        const showBtn = document.getElementById('updateShowBtn');
        const icon = document.getElementById('updateToggleIcon');
        const isHidden = content.style.display === 'none';
        const storageKey = 'updateMsgKepalaSekolah';
        
        if (isHidden) {
            content.style.display = 'flex'; showBtn.style.display = 'none';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
            localStorage.setItem(storageKey, 'false');
        } else {
            content.style.display = 'none'; showBtn.style.display = 'flex';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
            localStorage.setItem(storageKey, 'true');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const isHidden = localStorage.getItem('updateMsgKepalaSekolah') === 'true';
        if (isHidden) {
            const content = document.getElementById('updateMessageContent');
            const showBtn = document.getElementById('updateShowBtn');
            const icon = document.getElementById('updateToggleIcon');
            if (content && showBtn && icon) {
                content.style.display = 'none'; showBtn.style.display = 'flex';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
            }
        }
    });
</script>
@endif
