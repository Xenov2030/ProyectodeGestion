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
        
        // Scroll suave al final
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    }
    
    // Guardar en historial (localStorage) para persistencia en el módulo de mensajería
    try {
        const history = JSON.parse(localStorage.getItem('bot_history') || '[]');
        history.push({sender: sender, text: text, time: new Date().toISOString()});
        // Limitar historial a últimos 50 mensajes para no saturar localStorage
        if (history.length > 50) history.shift();
        localStorage.setItem('bot_history', JSON.stringify(history));
    } catch(e) {}
    
    // Si estamos en la página de chat y viendo el bot, renderizar historial en tiempo real
    if (typeof renderBotHistory === 'function' && window.currentChatId === 'bot') {
        renderBotHistory();
    }
    
    return container;
}

// Función para añadir botones de opciones dinámicamente
function addOptions(options) {
    const container = document.getElementById('bot-content');
    if (!container) return;

    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'bot-options';
    
    options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'btn-option';
        btn.innerText = opt.label;
        btn.onclick = () => {
            handleUserInput(opt.value || opt.label);
        };
        optionsDiv.appendChild(btn);
    });
    
    container.appendChild(optionsDiv);
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
}

// Lógica principal de procesamiento (Global para ser accesible desde app.php)
window.handleUserInput = async function(inputText) {
    if (!inputText || inputText.trim() === "") return;

    const isEn = document.documentElement.lang === 'en';
    const lowerInput = inputText.toLowerCase();

    // 1. Mostrar mensaje del usuario (excepto comandos internos nav_)
    if (!lowerInput.startsWith('nav_')) {
        addMessage(inputText, 'user');
    }

    // --- Navegación Directa ---
    if (lowerInput === 'nav_ticket_alta' || lowerInput === 'nav_ticket_media' || lowerInput === 'nav_ticket_baja') {
        const prio = lowerInput.split('_')[2];
        window.location.href = BASE_URL + '/tickets/crear?prioridad=' + prio;
        return;
    }
    if (lowerInput === 'nav_crear_ticket') {
        window.location.href = BASE_URL + '/tickets/crear';
        return;
    }
    if (lowerInput === 'nav_proyectos') {
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

    // --- Respuestas del Bot ---
    let responseText = isEn 
        ? "I'm sorry, I don't have a specific answer. Use the menu options for help."
        : "Lo siento, no tengo una respuesta específica. Usa las opciones del menú para ayudarte.";
    
    let options = [];

    // Flujo: Crear Ticket
    if (lowerInput === 'crear ticket' || lowerInput === 'create ticket') {
        responseText = isEn ? "Choose ticket priority:" : "¿Qué prioridad tiene tu ticket?";
        options = isEn ? [
            { label: "🔴 High", value: "nav_ticket_alta" },
            { label: "🟡 Medium", value: "nav_ticket_media" },
            { label: "🟢 Low", value: "nav_ticket_baja" }
        ] : [
            { label: "🔴 Alta", value: "nav_ticket_alta" },
            { label: "🟡 Media", value: "nav_ticket_media" },
            { label: "🟢 Baja", value: "nav_ticket_baja" }
        ];
    } 
    // Flujo: Ver Estado / Consultar Proyecto
    else if (lowerInput === 'estado' || lowerInput === 'status' || lowerInput.includes('estado de mi proyecto')) {
        responseText = isEn ? "What do you want to check?" : "¿Qué deseas revisar?";
        options = isEn ? [
            { label: "📂 Projects", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ] : [
            { label: "📂 Proyectos", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ];
    }
    // Flujo: Información
    else if (lowerInput === 'informacion' || lowerInput === 'información' || lowerInput === 'info' || lowerInput === 'information') {
        responseText = isEn ? "Select information type:" : "¿Qué tipo de información necesitas?";
        options = isEn ? [
            { label: "🛠️ Services", value: "servicios" },
            { label: "💲 Pricing", value: "precios" },
            { label: "📞 Contact", value: "contacto" }
        ] : [
            { label: "🛠️ Servicios", value: "servicios" },
            { label: "💲 Precios", value: "precios" },
            { label: "📞 Contacto", value: "contacto" }
        ];
    }
    else if (lowerInput === 'servicios' || lowerInput === 'services') {
        responseText = isEn ? "We offer software development, web apps, and tech support." : "Ofrecemos desarrollo de software, apps web y soporte técnico.";
    }
    else if (lowerInput === 'precios' || lowerInput === 'pricing') {
        responseText = isEn ? "Prices depend on scope. Create a ticket for a quote." : "Precios según el alcance. Crea un ticket para presupuesto.";
    }
    else if (lowerInput === 'contacto' || lowerInput === 'contact') {
        responseText = isEn ? "Contact us at info@vexstudio.online or create a ticket." : "Contáctanos en info@vexstudio.online o crea un ticket.";
    }
    // Flujo: Horarios
    else if (lowerInput === 'horarios' || lowerInput === 'hours') {
        responseText = isEn ? "Business hours: Mon-Fri 9:00 AM - 6:00 PM." : "Horarios: Lunes a Viernes 9:00 AM a 6:00 PM.";
    }
    else {
        // Buscar en conocimiento JSON
        if (botKnowledge) {
            if (botKnowledge.respuestas) {
                for (const [key, val] of Object.entries(botKnowledge.respuestas)) {
                    if (lowerInput.includes(key)) { responseText = val; break; }
                }
            }
            if (botKnowledge.preguntas) {
                const found = botKnowledge.preguntas.find(p => lowerInput.includes(p.clave));
                if (found) responseText = found.respuesta;
            }
        }

        // Si no entiende, ofrecer menú
        const isHelp = lowerInput.match(/(ayuda|help|hola|hello|hi|opciones|options|menu|inicio|start)/);
        if (isHelp || responseText.includes('Lo siento') || responseText.includes('I\'m sorry')) {
            if (isHelp && !responseText.includes('Lo siento')) {
                responseText = isEn ? "Hello! How can I help you today?" : "¡Hola! ¿En qué te puedo ayudar hoy?";
            }
            options = isEn ? [
                { label: "🚀 Create Project", value: "nav_crear_proyecto" },
                { label: "ℹ️ Information", value: "information" },
                { label: "📍 View Status", value: "status" },
                { label: "🎫 Create Ticket", value: "nav_crear_ticket" },
                { label: "🕒 Business Hours", value: "hours" }
            ] : [
                { label: "🚀 Crear Proyecto", value: "nav_crear_proyecto" },
                { label: "ℹ️ Información", value: "informacion" },
                { label: "📍 Ver Estado", value: "estado" },
                { label: "🎫 Crear Ticket", value: "nav_crear_ticket" },
                { label: "🕒 Horarios", value: "horarios" }
            ];
        }
    }

    // Simular respuesta del bot
    setTimeout(() => {
        addMessage(responseText, 'bot');
        if (options.length > 0) {
            setTimeout(() => addOptions(options), 300);
        }
    }, 400);
}

// Inicialización
function initBot() {
    // Carga no bloqueante de conocimiento
    if (typeof KNOWLEDGE_PATH !== 'undefined') {
        fetch(KNOWLEDGE_PATH)
            .then(res => res.ok ? res.json() : null)
            .then(data => { if (data) botKnowledge = data; })
            .catch(e => console.warn("Modo básico: JSON no disponible."));
    }

    const input = document.getElementById('bot-input');
    const sendBtn = document.getElementById('bot-send');

    if (sendBtn) {
        sendBtn.onclick = (e) => {
            e.preventDefault();
            const val = input.value;
            input.value = '';
            handleUserInput(val);
        };
    }

    if (input) {
        input.onkeydown = (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = input.value;
                input.value = '';
                handleUserInput(val);
            }
        };
    }
}

// Arrancar
document.addEventListener('DOMContentLoaded', initBot);