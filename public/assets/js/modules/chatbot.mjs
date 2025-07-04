export default function chatbotjs() {
    // /public/assets/js/chatbot.js

    document.addEventListener('DOMContentLoaded', function () {
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const chatMessages = document.getElementById('chatMessages');

        sendMessage.addEventListener('click', async () => {
            console.log('Botón de envío presionado');

            const userMessage = chatInput.value.trim();
            if (!userMessage) return;

            // Mostrar mensaje del usuario
            const userBubble = document.createElement('div');
            userBubble.className = 'alert alert-primary py-2 px-3 mb-2 rounded-3 align-self-end';
            userBubble.style.maxWidth = '80%';
            userBubble.textContent = userMessage;
            chatMessages.appendChild(userBubble);

            // Limpiar input
            chatInput.value = '';

            // Hacer solicitud al backend
            // Hacer solicitud al backend
            const response = await fetch('/public/chatbot_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: userMessage })
            });

            console.log(response); // <-- AÑADE ESTO

            if (!response.ok) {
                console.error("❌ Error de red o status HTTP:", response.status);
                return;
            }


            const data = await response.json();

            // Mostrar respuesta del chatbot
            const botBubble = document.createElement('div');
            botBubble.className = 'alert alert-secondary py-2 px-3 mb-2 rounded-3 align-self-start';
            botBubble.style.maxWidth = '80%';
            botBubble.textContent = data.reply;
            chatMessages.appendChild(botBubble);

            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    });


}