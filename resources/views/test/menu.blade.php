@extends('layouts.master')
@section('contenido')
            <!-- Botón para abrir la ventana de chat -->
    <button id="openChatBtn" class="openbtn">&#9993; Abrir Chat</button>

    <!-- Barra lateral para el chat -->
    <div id="chatSidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" id="closeChatBtn">&times;</a>
        <div class="chat-header">
            <h2 id="chatHeader">Notificacion</h2>
        </div>
        <div class="chat-messages">
            <!-- Aquí se mostrarán los mensajes del chat -->
        </div>
        <form id="chatForm" class="chat-form">
            <input type="text" id="chatInput" placeholder="Escribe un mensaje..." required>
            <button type="submit" class="sendbtn">Enviar</button>
        </form>
    </div>


<style>
    /* Estilos para la barra lateral del chat */
    .sidebar {
        height: 100%; /* Altura completa */
        width: 0; /* Ancho inicial de la barra */
        position: fixed; /* Posición fija */
        z-index: 1; /* Asegura que la barra esté sobre otros elementos */
        top: 0;
        right: 0; /* La barra aparece desde la derecha */
        background-color: #333; /* Color de fondo */
        overflow-x: hidden; /* Desbordamiento en el eje X oculto */
        transition: 0.5s; /* Transición suave */
        padding-top: 20px; /* Espaciado superior */
        display: flex;
        flex-direction: column;
    }

    /* Estilo del botón de cerrar la barra */
    .closebtn {
        position: absolute;
        top: 0;
        left: 15px;
        font-size: 36px;
        margin-left: 10px;
        color: white;
        text-decoration: none;
    }

    /* Estilos para el encabezado del chat */
    .chat-header {
        padding: 10px;
        background-color: #444;
        color: white;
        text-align: center;
    }

    /* Estilos para los mensajes del chat */
    .chat-messages {
        flex: 1; /* Toma el espacio disponible */
        padding: 15px;
        background-color: #f1f1f1;
        overflow-y: auto; /* Añade scroll si es necesario */
    }

    /* Estilos para el formulario de chat */
    .chat-form {
        display: flex;
        padding: 10px;
        background-color: #444;
    }

    /* Estilos para el input de texto */
    .chat-form input[type="text"] {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
        font-size: 16px;
    }

    /* Estilos para el botón de enviar */
    .sendbtn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .sendbtn:hover {
        background-color: #218838;
    }

    /* Estilo para el botón de abrir la barra de chat */
    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 15px;
        margin: 10px;
        transition: background-color 0.3s;
    }

    .openbtn:hover {
        background-color: #555;
    }
</style>
@stop


@section('extra-javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openChatBtn = document.getElementById('openChatBtn');
            const closeChatBtn = document.getElementById('closeChatBtn');
            const chatSidebar = document.getElementById('chatSidebar');
            const chatHeader = document.getElementById('chatHeader'); // Elemento h2 para cerrar el chat
            const chatForm = document.getElementById('chatForm');
            const chatMessages = document.querySelector('.chat-messages');
            const chatInput = document.getElementById('chatInput');

            // Función para abrir la barra lateral del chat
            function openChat() {
                chatSidebar.style.width = '300px'; // Cambia el ancho de la barra lateral del chat
            }

            // Función para cerrar la barra lateral del chat
            function closeChat() {
                chatSidebar.style.width = '0'; // Colapsa la barra lateral del chat a un ancho de 0
                // Elimina todos los mensajes del chat
                chatMessages.innerHTML = ''; // Limpia el contenido de chatMessages
            }

            // Añadir eventos de clic para los botones de abrir y cerrar
            openChatBtn.addEventListener('click', openChat);
            closeChatBtn.addEventListener('click', closeChat);

            // Añadir evento de clic al encabezado del chat para cerrar la barra lateral
            chatHeader.addEventListener('click', closeChat);

            // Manejar el envío del formulario de chat
            chatForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Evita el envío del formulario

                const messageText = chatInput.value.trim();
                if (messageText !== '') {
                    // Crear un nuevo elemento de mensaje
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.textContent = messageText;

                    // Añadir el mensaje a la lista de mensajes
                    chatMessages.appendChild(messageElement);

                    // Limpiar el input de texto
                    chatInput.value = '';

                    // Desplazar hacia abajo para ver el último mensaje
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
        });

    </script>

@stop
