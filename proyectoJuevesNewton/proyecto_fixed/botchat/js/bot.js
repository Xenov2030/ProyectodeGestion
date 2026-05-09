// botchat/js/bot.js

let botKnowledge = null;

// Función para añadir mensajes al chat
function addMessage(text, sender = 'bot') {
    const container = document.getElementById('bot-content');
    if (container) {
        const div = document.createElement('div');
        div.className = `msg ${sender}`;
        div.innerText = text;
        container.appendChild(div);
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    }
    
    try {
        const history = JSON.parse(localStorage.getItem('bot_history') || '[]');
        history.push({sender: sender, text: text, time: new Date().toISOString()});
        if (history.length > 50) history.shift();
        localStorage.setItem('bot_history', JSON.stringify(history));
    } catch(e) {}
    
    if (typeof renderBotHistory === 'function' && window.currentChatId === 'bot') {
        renderBotHistory();
    }
}

function addOptions(options) {
    const container = document.getElementById('bot-content');
    if (!container) return;

    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'bot-options';
    
    options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'btn-option';
        btn.innerText = opt.label;
        btn.onclick = () => handleUserInput(opt.value || opt.label);
        optionsDiv.appendChild(btn);
    });
    
    container.appendChild(optionsDiv);
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
}

window.handleUserInput = async function(inputText) {
    if (!inputText || inputText.trim() === "") return;

    const isEn = document.documentElement.lang === 'en';
    const lowerInput = inputText.toLowerCase().trim();

    if (!lowerInput.startsWith('nav_')) {
        addMessage(inputText, 'user');
    }

    // Navegación
    if (lowerInput.includes('nav_ticket_alta') || lowerInput.includes('nav_ticket_media') || lowerInput.includes('nav_ticket_baja')) {
        const prio = lowerInput.split('_')[2];
        window.location.href = BASE_URL + '/tickets/crear?prioridad=' + prio;
        return;
    }
    if (lowerInput === 'nav_crear_ticket' || lowerInput === 'crear ticket' || lowerInput === 'create ticket') {
        window.location.href = BASE_URL + '/tickets/crear';
        return;
    }
    if (lowerInput === 'nav_proyectos' || lowerInput.includes('estado de mi proyecto')) {
        window.location.href = BASE_URL + '/proyectos';
        return;
    }
    if (lowerInput === 'nav_tickets') {
        window.location.href = BASE_URL + '/tickets';
        return;
    }
    if (lowerInput === 'nav_crear_proyecto') {
        window.location.href = BASE_URL + '/tickets/crear?asunto=Solicitud%20de%20Nuevo%20Proyecto';
        return;
    }

    let responseText = isEn ? "I'm sorry, I don't understand. Try the options." : "Lo siento, no entiendo. Prueba con las opciones.";
    let options = [];

    if (lowerInput === 'estado' || lowerInput === 'status') {
        responseText = isEn ? "What do you want to check?" : "¿Qué deseas revisar?";
        options = isEn ? [
            { label: "📂 Projects", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ] : [
            { label: "📂 Proyectos", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ];
    } else if (lowerInput === 'informacion' || lowerInput === 'información' || lowerInput === 'info') {
        responseText = isEn ? "What info do you need?" : "¿Qué información necesitas?";
        options = isEn ? [
            { label: "🛠️ Services", value: "servicios" },
            { label: "💲 Pricing", value: "precios" }
        ] : [
            { label: "🛠️ Servicios", value: "servicios" },
            { label: "💲 Precios", value: "precios" }
        ];
    } else if (lowerInput === 'horarios' || lowerInput === 'hours') {
        responseText = isEn ? "Mon-Fri 9AM-6PM" : "Lunes a Viernes 9AM a 6PM";
    } else {
        if (botKnowledge) {
            if (botKnowledge.respuestas) {
                for (const [key, val] of Object.entries(botKnowledge.respuestas)) {
                    if (lowerInput.includes(key)) { responseText = val; break; }
                }
            }
        }
        options = isEn ? [
            { label: "🚀 New Project", value: "nav_crear_proyecto" },
            { label: "🎫 New Ticket", value: "nav_crear_ticket" },
            { label: "📍 Status", value: "estado" }
        ] : [
            { label: "🚀 Nuevo Proyecto", value: "nav_crear_proyecto" },
            { label: "🎫 Nuevo Ticket", value: "nav_crear_ticket" },
            { label: "📍 Ver Estado", value: "estado" }
        ];
    }

    setTimeout(() => {
        addMessage(responseText, 'bot');
        if (options.length > 0) setTimeout(() => addOptions(options), 300);
    }, 400);
}

function initBot() {
    if (typeof KNOWLEDGE_PATH !== 'undefined') {
        fetch(KNOWLEDGE_PATH).then(res => res.ok ? res.json() : null).then(data => { if (data) botKnowledge = data; });
    }
    const input = document.getElementById('bot-input');
    const sendBtn = document.getElementById('bot-send');
    if (sendBtn) sendBtn.onclick = () => { const v = input.value; input.value = ''; handleUserInput(v); };
    if (input) input.onkeydown = (e) => { if (e.key === 'Enter') { handleUserInput(input.value); input.value = ''; } };
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBot);
} else {
    initBot();
}