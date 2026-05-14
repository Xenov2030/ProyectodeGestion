<?php
// views/chat/index.php
use app\Core\Session;
use app\Core\I18n;
?>
<div class="container-fluid p-0" style="height: calc(100vh - 160px);">
    <div class="row h-100 g-4">
        <div class="col-md-4 h-100">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column overflow-hidden">
                <div class="p-4 border-bottom bg-white"><h5 class="fw-bold m-0"><?= I18n::t('chat') ?></h5></div>
                <div class="flex-grow-1 overflow-auto list-group list-group-flush">
                    <button onclick="loadBotChat()" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;"><i class="bi bi-robot fs-5"></i></div>
                        <div><div class="fw-bold small">GestorBot</div><div class="text-muted small" style="font-size: 0.6rem;">ASISTENTE</div></div>
                    </button>
                    <?php foreach ($usuarios as $u): ?>
                        <?php if ($u['id'] != Session::get('user_id')): ?>
                            <button onclick="loadChat(<?= $u['id'] ?>, '<?= addslashes($u['nombre']) ?>')" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;"><?= strtoupper(substr($u['nombre'], 0, 1)) ?></div>
                                <div><div class="fw-bold small"><?= $u['nombre'] ?></div><div class="text-muted small" style="font-size: 0.6rem;"><?= strtoupper($u['rol_nombre']) ?></div></div>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8 h-100">
            <div id="chat-window" class="card border-0 shadow-sm rounded-4 h-100 d-none flex-column">
                <div class="p-4 border-bottom bg-white d-flex align-items-center gap-3">
                     <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-person"></i></div>
                     <h6 id="chat-user-name" class="fw-bold m-0 text-dark"></h6>
                </div>
                <div id="chat-messages-container" class="flex-grow-1 overflow-auto p-4 d-flex flex-column gap-3 bg-light bg-opacity-50"></div>
                <div class="p-3 bg-white border-top">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="hidden" id="destinatario_id">
                        <input type="text" id="chat-input" class="form-control border-0 bg-light rounded-pill px-4" placeholder="...">
                        <button type="submit" class="btn btn-primary rounded-circle" style="width: 44px; height: 44px;"><i class="bi bi-send-fill"></i></button>
                    </form>
                </div>
            </div>
            <div id="chat-placeholder" class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 mb-4"><i class="bi bi-chat-left-dots-fill fs-1"></i></div>
                <h5 class="fw-bold"><?= I18n::t('chat') ?></h5><p class="text-muted small"><?= I18n::t('select_contact') ?></p>
            </div>
        </div>
    </div>
</div>

<script>
var currentChatId = null;
var refreshInterval = null;

function renderBotHistory() {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;
    container.innerHTML = '';
    
    let history = [];
    try {
        history = JSON.parse(localStorage.getItem('bot_history') || '[]');
    } catch(e) { console.error("History parse error", e); }

    if (history.length === 0) {
        history.push({sender: 'bot', text: '¡Hola! ¿En qué puedo ayudarte hoy?', time: new Date().toISOString()});
    }

    history.forEach(m => {
        const isMe = m.sender === 'user';
        const div = document.createElement('div');
        div.className = `p-3 rounded-4 shadow-sm ${isMe ? 'bg-primary text-white align-self-end' : 'bg-white text-dark align-self-start'}`;
        div.style.maxWidth = '75%';
        
        let content = `<div class="small">${m.text}</div>`;
        
        // Render options if any
        if (m.options && m.options.length > 0) {
            content += `<div class="d-flex flex-wrap gap-2 mt-2 pt-2 border-top border-opacity-10">`;
            m.options.forEach(opt => {
                content += `<button onclick="window.handleUserInput('${opt.value || opt.label}'); setTimeout(renderBotHistory, 500);" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill bg-white" style="font-size: 0.7rem;">${opt.label}</button>`;
            });
            content += `</div>`;
        }
        
        content += `<div class="text-end mt-1" style="font-size: 0.6rem; opacity: 0.6;">${new Date(m.time).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>`;
        div.innerHTML = content;
        container.appendChild(div);
    });
    container.scrollTop = container.scrollHeight;
}

function loadBotChat() {
    currentChatId = 'bot';
    document.getElementById('chat-window').classList.remove('d-none');
    document.getElementById('chat-window').classList.add('d-flex');
    document.getElementById('chat-placeholder').classList.add('d-none');
    document.getElementById('chat-user-name').innerText = 'GestorBot';
    document.getElementById('destinatario_id').value = 'bot';
    if (refreshInterval) clearInterval(refreshInterval);
    renderBotHistory();
}

function loadChat(id, name) {
    currentChatId = id;
    document.getElementById('chat-window').classList.remove('d-none');
    document.getElementById('chat-window').classList.add('d-flex');
    document.getElementById('chat-placeholder').classList.add('d-none');
    document.getElementById('chat-user-name').innerText = name;
    document.getElementById('destinatario_id').value = id;
    document.getElementById('chat-messages-container').innerHTML = '<div class="text-center py-5"><?= I18n::t("loading_chat") ?></div>';
    
    fetchMessages();
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(fetchMessages, 4000);
}

function fetchMessages() {
    if (!currentChatId || currentChatId === 'bot') return;
    fetch('<?= url("chat/getMessages") ?>?contacto_id=' + currentChatId)
        .then(res => {
            if (!res.ok) {
                return res.json().catch(() => { throw new Error("HTTP " + res.status); });
            }
            return res.json();
        })
        .then(data => {
            const container = document.getElementById('chat-messages-container');
            if (!container || currentChatId === 'bot') return;
            
            // Si hay un error devuelto por el servidor
            if (data.error) {
                throw new Error(data.error);
            }

            if (!Array.isArray(data)) {
                console.error("Data is not an array:", data);
                return;
            }

            if (data.length === 0) {
                container.innerHTML = `<div class="text-center py-5 text-muted opacity-50"><i class="bi bi-chat-dots fs-1 d-block mb-2"></i><div class="small"><?= I18n::t('no_messages') ?></div></div>`;
                return;
            }

            container.innerHTML = '';
            const myId = "<?= Session::get('user_id') ?>";
            data.forEach(m => {
                const isMe = String(m.remitente_id) === String(myId);
                const div = document.createElement('div');
                div.className = `p-3 rounded-4 shadow-sm ${isMe ? 'bg-primary text-white align-self-end' : 'bg-white text-dark align-self-start'}`;
                div.style.maxWidth = '75%';
                div.innerHTML = `<div class="small">${m.mensaje}</div><div class="text-end mt-1" style="font-size: 0.6rem; opacity: 0.6;">${new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>`;
                container.appendChild(div);
            });
            container.scrollTop = container.scrollHeight;
        })
        .catch(err => {
            console.error("Fetch error details:", err);
            document.getElementById('chat-messages-container').innerHTML = 
                '<div class="text-center py-5 text-danger small">' + 
                '<?= I18n::t("error_loading_chat") ?>' + 
                '<br><span style="font-size:0.6rem; opacity:0.7;">' + err.message + '</span></div>';
        });
}

document.getElementById('chat-form').onsubmit = (e) => {
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg || !currentChatId) return;

    if (currentChatId === 'bot') {
        input.value = '';
        if (typeof window.handleUserInput === 'function') {
            window.handleUserInput(msg);
            setTimeout(renderBotHistory, 200);
            setTimeout(renderBotHistory, 600);
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('destinatario_id', currentChatId);
    formData.append('mensaje', msg);
    formData.append('csrf_token', '<?= Session::get("csrf_token") ?>');
    input.value = '';
    fetch('<?= url("chat/send") ?>', { method: 'POST', body: formData }).then(() => fetchMessages());
};
</script>
