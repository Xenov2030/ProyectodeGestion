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

    // 1. Mostrar mensaje del usuario inmediatamente
    addMessage(inputText, 'user');

    // 2. Respuesta por defecto
    let responseText = isEn 
        ? "I'm sorry, I don't have a specific answer for that. Could you try another word or use the options?"
        : "Lo siento, no tengo una respuesta específica para eso. ¿Podrías intentar con otra palabra o usar las opciones?";
    
    let options = [];

    // 3. Buscar en el conocimiento cargado
    if (botKnowledge) {
        const lowerInput = inputText.toLowerCase();
        
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

    // 4. Si es un saludo o no entiende, ofrecer opciones siempre
    const isHelp = inputText.toLowerCase().match(/(ayuda|help|hola|hello|hi|opciones|options|menu|inicio|start)/);
    if (isHelp || responseText.includes('Lo siento') || responseText.includes('I\'m sorry')) {
        options = isEn ? [
            { label: "📍 View States", value: "status" },
            { label: "🎫 Create Ticket", value: "create ticket" },
            { label: "🕒 Business Hours", value: "hours" },
            { label: "👥 Users", value: "users" }
        ] : [
            { label: "📍 Ver Estados", value: "estado" },
            { label: "🎫 Crear Ticket", value: "crear ticket" },
            { label: "🕒 Horarios", value: "horarios" },
            { label: "👥 Usuarios", value: "usuarios" }
        ];
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