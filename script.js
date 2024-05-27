//Este script usa la función "showSection" carga y muestra el contenido de diferentes secciones 
//en el área principal de la página (content1) al hacer clic en un botón del menú,
//permitiendo la navegación por distintas secciones del sitio web desde la barra lateral.
function showSection(sectionId) {
    fetch(sectionId + '.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('content1').innerHTML = data;

            // Actualiza la clase activa del botón
            const buttons = document.querySelectorAll('.menu-button');
            buttons.forEach(button => {
                button.classList.remove('active');
            });
            document.querySelector(`[onclick="showSection('${sectionId}')"]`).classList.add('active');
        })
        .catch(error => console.error('Error al cargar la sección:', error));
}
