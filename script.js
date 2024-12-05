
document.addEventListener('DOMContentLoaded', () => {
    const filtroTexto = document.getElementById('filtroTexto');
    const tablaRegistros = document.getElementById('tablaRegistros').querySelector('tbody');
    const totalEntradas = document.getElementById('totalEntradas');
    const totalSalidas = document.getElementById('totalSalidas');
    const ganancia = document.getElementById('ganancia');

    let registros = [];

    // Función para actualizar la tabla y los totales
    function actualizarTabla() {
        tablaRegistros.innerHTML = '';
        let entradas = 0, salidas = 0, gananciaTotal = 0;

        registros.forEach(registro => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${registro.tipo}</td>
                <td>${registro.fecha}</td>
                <td>${registro.codigo}</td>
                <td>${registro.numero}</td>
                <td>${registro.precio}</td>
            `;
            tablaRegistros.appendChild(row);

            // Cálculos
            if (registro.tipo === 'entrada') {
                entradas += registro.numero;
                gananciaTotal += registro.numero * registro.precio;
            } else {
                salidas += registro.numero;
            }
        });

        totalEntradas.textContent = entradas;
        totalSalidas.textContent = salidas;
        ganancia.textContent = gananciaTotal.toFixed(2);
    }

    // Filtrar registros
    filtroTexto.addEventListener('input', () => {
        const texto = filtroTexto.value.toLowerCase();
        tablaRegistros.innerHTML = '';
        registros.filter(registro => 
            registro.codigo.toLowerCase().includes(texto) || registro.fecha.includes(texto)
        ).forEach(registro => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${registro.tipo}</td>
                <td>${registro.fecha}</td>
                <td>${registro.codigo}</td>
                <td>${registro.numero}</td>
                <td>${registro.precio}</td>
            `;
            tablaRegistros.appendChild(row);
        });
    });

    // Cargar datos iniciales
    fetch('registros.json')
        .then(response => response.json())
        .then(data => {
            registros = data;
            actualizarTabla();
        });
});
