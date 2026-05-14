// public/botchat/js/bot.js

let botKnowledge = null;

// Cargar conocimiento inmediatamente
if (typeof KNOWLEDGE_PATH !== 'undefined') {
    fetch(KNOWLEDGE_PATH)
        .then(res => res.ok ? res.json() : null)
        .then(data => { 
            if (data) {
                botKnowledge = data;
                console.log("Bot knowledge base loaded.");
            }
        })
        .catch(err => console.error("Error loading bot knowledge:", err));
}

function saveToHistory(text, sender, options = []) {
    let history = [];
    try {
        history = JSON.parse(localStorage.getItem('bot_history') || '[]');
    } catch(e) {}
    history.push({ text, sender, options, time: new Date().toISOString() });
    // Mantener solo los últimos 50 mensajes
    if (history.length > 50) history.shift();
    localStorage.setItem('bot_history', JSON.stringify(history));
}

function addMessage(text, sender = 'bot') {
    const container = document.getElementById('bot-content');
    if (container) {
        const div = document.createElement('div');
        div.className = `msg ${sender}`;
        div.innerText = text;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }
    // Solo guardamos si no es un comando de redirección (nav_)
    if (!text.includes('Redirecting') && !text.includes('Redirigiendo')) {
        saveToHistory(text, sender);
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

    // Actualizamos el último mensaje de la historia para incluir estas opciones
    let history = JSON.parse(localStorage.getItem('bot_history') || '[]');
    if (history.length > 0 && history[history.length-1].sender === 'bot') {
        history[history.length-1].options = options;
        localStorage.setItem('bot_history', JSON.stringify(history));
    }
}

window.botProcess = function(inputText) {
    const isEn = document.documentElement.lang === 'en';
    const lowerInput = inputText.toLowerCase().trim();
    
    console.log("Bot processing:", lowerInput);

    // Mapeo de navegación (Redirecciones directas)
    const navMap = {
        'nav_crear_proyecto': BASE_URL + '/proyectos/crear',
        'nav_proyectos': BASE_URL + '/proyectos',
        'nav_crear_ticket': BASE_URL + '/tickets/crear',
        'nav_tickets': BASE_URL + '/tickets',
        'estado': BASE_URL + '/tickets',
        'ver estados': BASE_URL + '/tickets',
        'proyectos': BASE_URL + '/proyectos',
        'tickets': BASE_URL + '/tickets'
    };

    // Si es un comando de navegación, redirigir
    if (navMap[lowerInput]) {
        addMessage(isEn ? "Redirecting..." : "Redirigiendo...", 'bot');
        console.log("Redirecting to:", navMap[lowerInput]);
        setTimeout(() => {
            window.location.href = navMap[lowerInput];
        }, 500);
        return;
    }

    // Si no es navegación, mostrar mensaje del usuario (si no es nav_)
    if (!lowerInput.startsWith('nav_')) {
        addMessage(inputText, 'user');
    }

    let response = null;
    let options = [];

    // 1. Buscar en la base de conocimientos JSON
    if (botKnowledge) {
        if (botKnowledge.respuestas && botKnowledge.respuestas[lowerInput]) {
            response = botKnowledge.respuestas[lowerInput];
        } else if (botKnowledge.preguntas) {
            const match = botKnowledge.preguntas.find(p => lowerInput.includes(p.clave.toLowerCase()));
            if (match) response = match.respuesta;
        }
    }

    // 2. Respuestas rápidas hardcoded (por si falla el JSON o para mayor velocidad)
    if (!response) {
        if (lowerInput.includes('horario')) {
            response = isEn ? "We are open Monday to Friday, 9am to 6pm." : "Nuestro horario de atención es de lunes a viernes de 9:00 a 18:00.";
        } else if (lowerInput === 'hola' || lowerInput === 'hi') {
            response = isEn ? "Hello! How can I help you today?" : "¡Hola! ¿En qué puedo ayudarte hoy?";
        } else if (lowerInput === 'estado') {
            response = isEn ? "Click below to see the status of your requests:" : "Haz clic abajo para ver el estado de tus trámites:";
            options = [{label: '📍 ' + (isEn ? 'View Status' : 'Ver Estados'), value: 'nav_tickets'}];
        }
    }

    // Fallback si nada funcionó
    if (!response) {
        response = isEn ? "I'm sorry, I don't have information about that yet. Try one of these:" : "Lo siento, aún no tengo información sobre eso. Intenta con una de estas opciones:";
        options = [
            {label: '🚀 ' + (isEn ? 'New Project' : 'Nuevo Proyecto'), value: 'nav_crear_proyecto'},
            {label: '📍 ' + (isEn ? 'View Status' : 'Ver Estados'), value: 'nav_tickets'},
            {label: '💳 ' + (isEn ? 'Create Ticket' : 'Crear Ticket'), value: 'nav_crear_ticket'},
            {label: '🕒 ' + (isEn ? 'Schedule' : 'Horarios'), value: 'horarios'}
        ];
    }

    // Mostrar respuesta con delay
    setTimeout(() => {
        addMessage(response, 'bot');
        if (options.length > 0) {
            setTimeout(() => addOptions(options), 300);
        }
    }, 400);
};

// Configurar eventos de input
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('bot-input');
    const btn = document.getElementById('bot-send');
    
    if (btn) {
        btn.onclick = () => { 
            const v = input.value; 
            if(v.trim()){
                input.value = ''; 
                window.handleUserInput(v); 
            }
        };
    }
    if (input) {
        input.onkeydown = (e) => { 
            if (e.key === 'Enter') { 
                const v = input.value;
                if(v.trim()){
                    input.value = ''; 
                    window.handleUserInput(v); 
                }
            } 
        };
    }
});