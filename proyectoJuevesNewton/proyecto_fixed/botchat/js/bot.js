// botchat/js/bot.js

let botKnowledge = null;

// Función para añadir mensajes al chat
function addMessage(text, sender = 'bot') {
    const container = document.getElementById('bot-content');
    if (!container) return;
    
    const div = document.createElement('div');
    div.className = `msg ${sender}`;
    div.innerText = text;
    container.appendChild(div);
    
    // Scroll suave al final
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    return div;
}

// Función para añadir botones de opciones
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
            // Al hacer clic, enviamos el valor como si el usuario lo hubiera escrito
            handleUserInput(opt.value || opt.label);
        };
        optionsDiv.appendChild(btn);
    });
    
    container.appendChild(optionsDiv);
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
}

// Lógica principal de procesamiento
async function handleUserInput(inputText) {
    if (!inputText || inputText.trim() === "") return;

    const isEn = document.documentElement.lang === 'en';
    const lowerInput = inputText.toLowerCase();

    // 1. Mostrar mensaje del usuario inmediatamente (excepto comandos ocultos de navegación)
    if (!lowerInput.startsWith('nav_')) {
        addMessage(inputText, 'user');
    }

    // Navegación directa
    if (lowerInput === 'nav_ticket_alta' || lowerInput === 'nav_ticket_media' || lowerInput === 'nav_ticket_baja') {
        const prio = lowerInput.split('_')[2];
        window.location.href = BASE_URL + '/tickets/crear?prioridad=' + prio;
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

    let responseText = isEn 
        ? "I'm sorry, I don't have a specific answer for that. Could you try another word or use the options?"
        : "Lo siento, no tengo una respuesta específica para eso. ¿Podrías intentar con otra palabra o usar las opciones?";
    
    let options = [];

    // Flujos especiales: Crear Ticket
    if (lowerInput === 'crear ticket' || lowerInput === 'create ticket') {
        responseText = isEn ? "What priority does your ticket have?" : "¿Qué prioridad tiene tu ticket?";
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
    // Flujos especiales: Ver Estado
    else if (lowerInput === 'estado' || lowerInput === 'status') {
        responseText = isEn ? "What do you want to check?" : "¿Qué deseas revisar?";
        options = isEn ? [
            { label: "📂 Projects", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ] : [
            { label: "📂 Proyectos", value: "nav_proyectos" },
            { label: "🎫 Tickets", value: "nav_tickets" }
        ];
    }
    else {
        // 3. Buscar en el conocimiento cargado
        if (botKnowledge) {
            // Buscar en respuestas directas
            if (botKnowledge.respuestas) {
                for (const [key, val] of Object.entries(botKnowledge.respuestas)) {
                    if (lowerInput.includes(key)) {
                        responseText = val;
                        break;
                    }
                }
            }

            // Buscar en lista de preguntas/claves
            if (botKnowledge.preguntas) {
                const found = botKnowledge.preguntas.find(p => lowerInput.includes(p.clave));
                if (found) responseText = found.respuesta;
            }
        }

        // 4. Si es un saludo o no entiende, ofrecer menú principal
        const isHelp = lowerInput.match(/(ayuda|help|hola|hello|hi|opciones|options|menu|inicio|start)/);
        if (isHelp || responseText.includes('Lo siento') || responseText.includes('I\'m sorry')) {
            if (isHelp && !responseText.includes('Lo siento')) {
                responseText = isEn ? "Hello! How can I help you today?" : "¡Hola! ¿En qué te puedo ayudar hoy?";
            }
            options = isEn ? [
                { label: "📍 View States", value: "status" },
                { label: "🎫 Create Ticket", value: "create ticket" },
                { label: "🕒 Business Hours", value: "hours" }
            ] : [
                { label: "📍 Ver Estados", value: "estado" },
                { label: "🎫 Crear Ticket", value: "crear ticket" },
                { label: "🕒 Horarios", value: "horarios" }
            ];
        }
    }

    // 5. Simular respuesta del bot con un pequeño retraso
    setTimeout(() => {
        addMessage(responseText, 'bot');
        if (options.length > 0) {
            setTimeout(() => addOptions(options), 300);
        }
    }, 400);
}

// Inicialización de Eventos y Carga de Datos
async function initBot() {
    // Intentar cargar el JSON
    try {
        const response = await fetch(KNOWLEDGE_PATH);
        if (response.ok) {
            botKnowledge = await response.json();
        }
    } catch (e) {
        console.warn("No se pudo cargar el JSON de conocimiento, usando modo básico.");
    }

    const input = document.getElementById('bot-input');
    const sendBtn = document.getElementById('bot-send');

    // Configurar clic en botón enviar
    if (sendBtn) {
        sendBtn.onclick = (e) => {
            e.preventDefault();
            const val = input.value;
            input.value = '';
            handleUserInput(val);
        };
    }

    // Configurar tecla Enter
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

    // Mostrar opciones de bienvenida tras 1 segundo
    setTimeout(() => {
        const isEn = document.documentElement.lang === 'en';
        addOptions(isEn ? [
            { label: "📍 View States", value: "status" },
            { label: "🎫 Create Ticket", value: "create ticket" },
            { label: "🕒 Business Hours", value: "hours" }
        ] : [
            { label: "📍 Ver Estados", value: "estado" },
            { label: "🎫 Crear Ticket", value: "crear ticket" },
            { label: "🕒 Horarios", value: "horarios" }
        ]);
    }, 1000);
}

// Arrancar el bot
initBot();