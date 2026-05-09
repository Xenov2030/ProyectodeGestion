// botchat/js/bot.js

let botKnowledge = null;

// Cargar conocimiento inmediatamente
fetch(KNOWLEDGE_PATH)
    .then(res => res.ok ? res.json() : null)
    .then(data => { if (data) botKnowledge = data; });

function addMessage(text, sender = 'bot') {
    const container = document.getElementById('bot-content');
    if (container) {
        const div = document.createElement('div');
        div.className = `msg ${sender}`;
        div.innerText = text;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }
    
    // Guardar en historial
    try {
        const history = JSON.parse(localStorage.getItem('bot_history') || '[]');
        history.push({sender: sender, text: text, time: new Date().toISOString()});
        if (history.length > 50) history.shift();
        localStorage.setItem('bot_history', JSON.stringify(history));
    } catch(e) { console.error("Error saving history:", e); }
    
    // Sincronizar con la vista de chat si existe
    if (typeof renderBotHistory === 'function' && window.currentChatId === 'bot') {
        renderBotHistory();
    }
}

function addOptions(options) {
    const container = document.getElementById('bot-content');
    if (!container) return;
    const div = document.createElement('div');
    div.className = 'bot-options';
    options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'btn-option';
        btn.innerText = opt.label;
        btn.onclick = () => window.handleUserInput(opt.value || opt.label);
        div.appendChild(btn);
    });
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

window.botProcess = function(inputText) {
    const isEn = document.documentElement.lang === 'en';
    const lowerInput = inputText.toLowerCase().trim();

    if (!lowerInput.startsWith('nav_')) {
        addMessage(inputText, 'user');
    }

    // Lógica de navegación
    if (lowerInput === 'nav_crear_proyecto') {
        window.location.href = BASE_URL + '/tickets/crear?asunto=Solicitud%20de%20Nuevo%20Proyecto';
        return;
    }
    if (lowerInput === 'nav_crear_ticket' || lowerInput === 'crear ticket') {
        window.location.href = BASE_URL + '/tickets/crear';
        return;
    }
    if (lowerInput === 'nav_proyectos' || lowerInput === 'proyectos') {
        window.location.href = BASE_URL + '/proyectos';
        return;
    }
    if (lowerInput === 'nav_tickets' || lowerInput === 'tickets') {
        window.location.href = BASE_URL + '/tickets';
        return;
    }

    let response = isEn ? "I'm not sure about that." : "No estoy seguro de eso.";
    let options = [];

    if (lowerInput === 'estado' || lowerInput === 'status') {
        response = isEn ? "What do you want to see?" : "¿Qué deseas ver?";
        options = [{label: isEn?'Projects':'Proyectos', value:'nav_proyectos'}, {label:'Tickets', value:'nav_tickets'}];
    } else {
        // Fallback al menú principal
        response = isEn ? "How can I help you?" : "¿En qué te puedo ayudar?";
        options = [
            {label: isEn?'New Project':'Nuevo Proyecto', value:'nav_crear_proyecto'},
            {label: isEn?'New Ticket':'Nuevo Ticket', value:'nav_crear_ticket'},
            {label: isEn?'View Status':'Ver Estado', value:'estado'}
        ];
    }

    setTimeout(() => {
        addMessage(response, 'bot');
        if (options.length > 0) setTimeout(() => addOptions(options), 200);
    }, 400);
};

// Eventos de entrada
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('bot-input');
    const btn = document.getElementById('bot-send');
    if (btn) btn.onclick = () => { const v = input.value; input.value = ''; window.handleUserInput(v); };
    if (input) input.onkeydown = (e) => { if (e.key === 'Enter') { window.handleUserInput(input.value); input.value = ''; } };
});