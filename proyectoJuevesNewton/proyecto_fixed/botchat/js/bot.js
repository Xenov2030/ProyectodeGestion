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
    if (lowerInput === 'nav_crear_proyecto') {
        // Redirigir a crear ticket con prellenado para proyecto
        window.location.href = BASE_URL + '/tickets/crear?asunto=Solicitud%20de%20Nuevo%20Proyecto';
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
    // Flujos especiales: Información
    else if (lowerInput === 'informacion' || lowerInput === 'información' || lowerInput === 'info' || lowerInput === 'information') {
        responseText = isEn ? "What kind of information do you need?" : "¿Qué tipo de información necesitas?";
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
        responseText = isEn ? "We offer custom software development, web applications, and technical support." : "Ofrecemos desarrollo de software a medida, aplicaciones web y soporte técnico.";
    }
    else if (lowerInput === 'precios' || lowerInput === 'pricing') {
        responseText = isEn ? "Our prices depend on the project scope. Please create a ticket to request a quote." : "Nuestros precios dependen del alcance del proyecto. Por favor crea un ticket para solicitar una cotización.";
    }
    else if (lowerInput === 'contacto' || lowerInput === 'contact') {
        responseText = isEn ? "You can contact us via email at info@vexstudio.online or create a ticket." : "Puedes contactarnos por email a info@vexstudio.online o creando un ticket.";
    }
    // Flujos especiales: Horarios
    else if (lowerInput === 'horarios' || lowerInput === 'hours') {
        responseText = isEn ? "Our business hours are Monday to Friday from 9:00 AM to 6:00 PM." : "Nuestros horarios de atención son de Lunes a Viernes de 9:00 AM a 6:00 PM.";
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
                { label: "🚀 Create Project", value: "nav_crear_proyecto" },
                { label: "ℹ️ Information", value: "information" },
                { label: "📍 View Status", value: "status" },
                { label: "🎫 Create Ticket", value: "create ticket" },
                { label: "🕒 Business Hours", value: "hours" }
            ] : [
                { label: "🚀 Crear Proyecto", value: "nav_crear_proyecto" },
                { label: "ℹ️ Información", value: "informacion" },
                { label: "📍 Ver Estado", value: "estado" },
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
function initBot() {
    // Intentar cargar el JSON sin bloquear
    fetch(KNOWLEDGE_PATH)
        .then(response => {
            if (response.ok) {
                return response.json();
            }
        })
        .then(data => {
            if (data) botKnowledge = data;
        })
        .catch(e => {
            console.warn("No se pudo cargar el JSON de conocimiento, usando modo básico.");
        });

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
            { label: "🚀 Create Project", value: "nav_crear_proyecto" },
            { label: "ℹ️ Information", value: "information" },
            { label: "📍 View Status", value: "status" },
            { label: "🎫 Create Ticket", value: "create ticket" },
            { label: "🕒 Business Hours", value: "hours" }
        ] : [
            { label: "🚀 Crear Proyecto", value: "nav_crear_proyecto" },
            { label: "ℹ️ Información", value: "informacion" },
            { label: "📍 Ver Estado", value: "estado" },
            { label: "🎫 Crear Ticket", value: "crear ticket" },
            { label: "🕒 Horarios", value: "horarios" }
        ]);
    }, 1000);
}

// Arrancar el bot
initBot();