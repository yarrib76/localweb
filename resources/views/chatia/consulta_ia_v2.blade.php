<link rel="stylesheet" href="/css/styles_ia.css">
<div id="myModalChatIA" class="modal">
        <!-- Modal content -->
     <div id="modal-content-chatia" class="modal-content">
         <span id="close-chat_ia" class="close-chat_ia">&times;</span>
         <h5 id="nombreCliente"></h5>
            <div id="general">
                <div class="chat-messages">
                    <!-- Aquí se mostrarán los mensajes del chat -->
                </div>
                <form id="chatForm" class="chat-form">
                    <input type="text" id="chatInput" placeholder="Escribe un mensaje..." required>
                        <button type="submit" class="sendbtn">Enviar</button>
                </form>
            </div>
     </div>

 </div>


    <script src="https://cdn.jsdelivr.net/npm/luxon@2.4.0/build/global/luxon.min.js"></script>
    <script>
        var global_id_cliente;
        var global_id_usuario;
        var global_id_Pedido_chatIA
        var modalChatIa = document.getElementById("myModalChatIA")
        var span_chat_ia = document.getElementById("close-chat_ia");
        const chatForm = document.getElementById('chatForm');
        const chatMessages = document.querySelector('.chat-messages');
        const chatInput = document.getElementById('chatInput');
        // When the user clicks on <span> (x), close the modal
        span_chat_ia.onclick = function() {
            modalChatIa.style.display = "none";
            // Limpia el contenedor de mensajes antes de agregar nuevos mensajes
            chatMessages.innerHTML = '';
        }
        function consulta_ia(id_pedido, id_usuario, id_cliente, nombreCliente, apellidoCliente){
            global_id_cliente = id_cliente;
            global_id_usuario = id_usuario;
            global_id_Pedido_chatIA = id_pedido;
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');
            // When the user clicks the button, open the modal
            modalChatIA.style.display = "block";
            $(".modal-content #nombreCliente").html( "Cliente: " + nombreCliente + ", " + apellidoCliente);

            cargoHistorialChat() //Funcion para cargar historial de conversaciones
            // Manejar el envío del formulario de chat
            chatForm.addEventListener('submit', function(event) {
                 event.preventDefault(); // Evita el envío del formulario
                const messageText = chatInput.value.trim();
                if (messageText !== '') {
                    // Crear un contenedor para cada mensaje
                    const messageContainer = document.createElement('div');
                    messageContainer.classList.add('message-container'); // Clase CSS para el contenedor del mensaje

                    // Crear un elemento para el nombre
                    const nameElement = document.createElement('span');
                    nameElement.classList.add('message-name'); // Clase CSS para el nombre
                    nameElement.textContent = "Yamil"; // Asignar el nombre desde el JSON

                    // Crear un elemento para el mensaje de chat
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message'); // Clase CSS para el mensaje
                    messageElement.textContent = messageText;

                    // Añadir el nombre y el mensaje al contenedor del mensaje
                    messageContainer.appendChild(nameElement);
                    messageContainer.appendChild(messageElement);

                    // Añadir el contenedor del mensaje a la lista de mensajes
                    chatMessages.appendChild(messageContainer);

                    // Desplazar hacia abajo para ver el último mensaje
                    chatMessages.scrollTop = chatMessages.scrollHeight;

                    // Limpiar el input de texto
                    chatInput.value = '';
                    refreshfunctionMensageIA(messageText)
                }
            });

        }
        function refreshfunctionMensageIA(consulta){
            // let fechaActual = new Date();
            let fechaActual = luxon.DateTime.local();
            let horaFormateada = fechaActual.toFormat('HH:mm:ss');
            // Formatear la fecha en el formato deseado
            let fechaFormateada = fechaActual.toFormat('dd/MM/yyyy');
            let fechaCompleta = fechaFormateada+ " " + horaFormateada
            $.ajax({
                url: '/chatia?consultaHumana=' + consulta + "&cliente_id=" + global_id_cliente + "&id_pedido=" + global_id_Pedido_chatIA + "&id_user=" + global_id_usuario,
                dataType : "json",
                success: function(json) {
                    // Crear un contenedor para cada mensaje
                    const messageContainer = document.createElement('div');
                    messageContainer.classList.add('message-container'); // Clase CSS para el contenedor del mensaje

                    // Crear un elemento para el nombre
                    const nameElement = document.createElement('span');
                    nameElement.classList.add('message-name'); // Clase CSS para el nombre
                    nameElement.textContent = "Mia"; // Asignar el nombre desde el JSON

                    // Crear un elemento para el mensaje de chat
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message'); // Clase CSS para el mensaje
                    messageElement.textContent = json; // Asignar el chat desde el JSON

                    // Crear un ícono para las respuestas de Mia
                    const iconElement = document.createElement('img');
                    iconElement.src = 'refresh/mia.png'; // Ruta al ícono de Mia
                    iconElement.classList.add('mia-icon'); // Clase CSS para el ícono
                    iconElement.alt = 'Icono de Mia'; // Texto alternativo para accesibilidad

                    // Añadir el ícono al contenedor del mensaje antes del nombre
                    messageContainer.appendChild(iconElement);
                    // Añadir el nombre y el mensaje al contenedor del mensaje
                    messageContainer.appendChild(nameElement);
                    messageContainer.appendChild(messageElement);

                    // Añadir el contenedor del mensaje a la lista de mensajes
                    chatMessages.appendChild(messageContainer);

                    // Desplazar hacia abajo para ver el último mensaje
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                },
            });
        }

        function cerrarChatIA(){
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');
            // When the user clicks on <span> (x), close the modal
            modalChatIA.style.display = "none";
        }
        function cargoHistorialChat(){
            $.ajax({
                url: 'carga_chatia?id_pedido=' + global_id_Pedido_chatIA,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json) {
                        // Crear un contenedor para cada mensaje
                        const messageContainer = document.createElement('div');
                        messageContainer.classList.add('message-container'); // Clase CSS para el contenedor del mensaje

                        // Crear un elemento para el nombre
                        const nameElement = document.createElement('span');
                        nameElement.classList.add('message-name'); // Clase CSS para el nombre
                        nameElement.textContent = json['nombre']; // Asignar el nombre desde el JSON

                        // Crear un elemento para el mensaje de chat
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message'); // Clase CSS para el mensaje
                        messageElement.textContent = json['chat']; // Asignar el chat desde el JSON

                        // Comprobar si el remitente es "Mia"
                        if (json['nombre'] === 'Mia') {
                            // Crear un ícono para las respuestas de Mia
                            const iconElement = document.createElement('img');
                            iconElement.src = 'refresh/mia.png'; // Ruta al ícono de Mia
                            iconElement.classList.add('mia-icon'); // Clase CSS para el ícono
                            iconElement.alt = 'Icono de Mia'; // Texto alternativo para accesibilidad

                            // Añadir el ícono al contenedor del mensaje antes del nombre
                            messageContainer.appendChild(iconElement);
                        }

                        // Añadir el nombre y el mensaje al contenedor del mensaje
                        messageContainer.appendChild(nameElement);
                        messageContainer.appendChild(messageElement);

                        // Añadir el contenedor del mensaje a la lista de mensajes
                        chatMessages.appendChild(messageContainer);

                        // Desplazar hacia abajo para ver el último mensaje
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    });
                }
            });
        }

    </script>

