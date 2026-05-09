<?php
// views/chat/index.php
use app\Core\Session;
use app\Core\I18n;
?>
<div class="container-fluid p-0" style="height: calc(100vh - 160px);">
    <div class="row h-100 g-4">
        <!-- Contact List -->
        <div class="col-md-4 h-100">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column overflow-hidden">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-bold m-0"><?= I18n::t('chat') ?></h5>
                </div>
                <div class="flex-grow-1 overflow-auto list-group list-group-flush">
                    <!-- Bot Contact -->
                    <button onclick="loadBotChat()" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center gap-3 transition-all bg-light">
                        <div class="bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="bi bi-robot fs-5"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold small text-truncate">GestorBot</div>
                            <div class="text-muted small text-truncate" style="font-size: 0.65rem;">ASISTENTE VIRTUAL</div>
                        </div>
                    </button>
                    <?php foreach ($usuarios as $u): ?>
                        <?php if ($u['id'] != Session::get('user_id')): ?>
                            <button onclick="loadChat(<?= $u['id'] ?>, '<?= addslashes($u['nombre']) ?>')" 
                                    class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center gap-3 transition-all">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; min-width: 44px;">
                                    <?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold small text-truncate"><?= $u['nombre'] ?></div>
                                    <div class="text-muted small text-truncate" style="font-size: 0.65rem;"><?= strtoupper($u['rol_nombre']) ?></div>
                                </div>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="col-md-8 h-100">
            <div id="chat-window" class="card border-0 shadow-sm rounded-4 h-100 d-none flex-column">
                <div class="p-4 border-bottom bg-white d-flex align-items-center gap-3">
                     <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        <i class="bi bi-person"></i>
                     </div>
                     <h6 id="chat-user-name" class="fw-bold m-0 text-dark"></h6>
                </div>
                
                <div id="chat-messages-container" class="flex-grow-1 overflow-auto p-4 d-flex flex-column gap-3 bg-light bg-opacity-50">
                    <!-- Mensajes cargados vía AJAX -->
                </div>

                <div class="p-3 bg-white border-top">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="hidden" id="destinatario_id">
                        <input type="text" id="chat-input" class="form-control border-0 bg-light rounded-pill px-4 py-2" placeholder="<?= I18n::t('bot_placeholder') ?>" autocomplete="off">
                        <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="bi bi-send-fill text-white"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div id="chat-placeholder" class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-4 mb-4">
                    <i class="bi bi-chat-left-dots-fill fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark"><?= I18n::t('chat') ?></h5>
                <p class="text-muted small"><?= I18n::t('select_contact') ?></p>
            </div>
        </div>
    </div>
</div>

<script>
let currentChatId = null;
let refreshInterval = null;

function loadBotChat() {
    currentChatId = 'bot';
    document.getElementById('chat-window').classList.add('d-flex');
    document.getElementById('chat-window').classList.remove('d-none');
    document.getElementById('chat-placeholder').classList.add('d-none');
    document.getElementById('chat-user-name').innerText = 'GestorBot';
    document.getElementById('destinatario_id').value = 'bot';
    
    if (refreshInterval) clearInterval(refreshInterval);
    renderBotHistory();
}

function renderBotHistory() {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;
    container.innerHTML = '';
    const history = JSON.parse(localStorage.getItem('bot_history') || '[]');
    
    if (history.length === 0) {
        history.push({sender: 'bot', text: '¡Hola! ¿En qué puedo ayudarte hoy?', time: new Date().toISOString()});
        localStorage.setItem('bot_history', JSON.stringify(history));
    }

    history.forEach(m => {
        const isMe = m.sender === 'user';
        const div = document.createElement('div');
        div.className = `p-3 rounded-4 shadow-sm ${isMe ? 'bg-primary text-white align-self-end' : 'bg-white text-dark align-self-start'}`;
        div.style.maxWidth = '70%';
        div.style.borderRadius = isMe ? '16px 16px 4px 16px' : '16px 16px 16px 4px';
        
        div.innerHTML = `
            <div class="small">${m.text}</div>
            <div class="text-end mt-1" style="font-size: 0.6rem; opacity: 0.6;">
                ${new Date(m.time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
            </div>
        `;
        container.appendChild(div);
    });
    container.scrollTop = container.scrollHeight;
}

function loadChat(id, name) {
    currentChatId = id;
    document.getElementById('chat-window').classList.add('d-flex');
    document.getElementById('chat-window').classList.remove('d-none');
    document.getElementById('chat-placeholder').classList.add('d-none');
    document.getElementById('chat-user-name').innerText = name;
    document.getElementById('destinatario_id').value = id;
    
    document.getElementById('chat-messages-container').innerHTML = '<div class="text-center py-5 text-muted small"><?= I18n::t("loading_chat") ?></div>';
    fetchMessages();

    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(fetchMessages, 4000);
}

function fetchMessages() {
    if (!currentChatId || currentChatId === 'bot') return;
    
    fetch('<?= url("chat/getMessages") ?>?contacto_id=' + currentChatId)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('chat-messages-container');
            if (!container) return;
            const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
            
            container.innerHTML = '';
            const myId = "<?= Session::get('user_id') ?>";

            data.forEach(m => {
                const isMe = String(m.remitente_id) === String(myId);
                const div = document.createElement('div');
                div.className = `p-3 rounded-4 shadow-sm ${isMe ? 'bg-primary text-white align-self-end' : 'bg-white text-dark align-self-start'}`;
                div.style.maxWidth = '70%';
                div.style.borderRadius = isMe ? '16px 16px 4px 16px' : '16px 16px 16px 4px';
                
                div.innerHTML = `
                    <div class="small">${m.mensaje}</div>
                    <div class="text-end mt-1" style="font-size: 0.6rem; opacity: 0.6;">
                        ${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                    </div>
                `;
                container.appendChild(div);
            });

            if (wasAtBottom) {
                container.scrollTop = container.scrollHeight;
            }
        });
}

document.getElementById('chat-form').onsubmit = (e) => {
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg || !currentChatId) return;

    if (currentChatId === 'bot') {
        input.value = '';
        if (typeof handleUserInput === 'function') {
            handleUserInput(msg);
            setTimeout(renderBotHistory, 100);
            setTimeout(renderBotHistory, 600);
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('destinatario_id', currentChatId);
    formData.append('mensaje', msg);
    formData.append('csrf_token', '<?= Session::get("csrf_token") ?>');

    input.value = '';

    fetch('<?= url("chat/send") ?>', { method: 'POST', body: formData })
        .then(() => fetchMessages());
};
</script>
