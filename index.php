<?php
// Conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'inventario';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Manejo de registros
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;
    $precio = $_POST['precio'] ?? 0.0;

    if (!empty($nombre) && !empty($tipo) && !empty($fecha) && $cantidad > 0 && $precio > 0) {
        $codigo = uniqid('COD-');
        $sql = "INSERT INTO registros (tipo, fecha, codigo, nombre, cantidad, precio) 
                VALUES ('$tipo', '$fecha', '$codigo', '$nombre', $cantidad, $precio)";
        if ($conn->query($sql)) {
            echo "Registro agregado correctamente.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Todos los campos son obligatorios y la cantidad y precio deben ser mayores que cero.";
    }
}

// Filtro de búsqueda
$nombre_filtro = $_GET['nombre'] ?? '';
$cantidad_filtro = $_GET['cantidad'] ?? '';
$precio_filtro = $_GET['precio'] ?? '';
$fecha_filtro = $_GET['fecha'] ?? '';

$query = "SELECT * FROM registros WHERE 
          nombre LIKE '%$nombre_filtro%' AND 
          cantidad LIKE '%$cantidad_filtro%' AND 
          precio LIKE '%$precio_filtro%' AND 
          fecha LIKE '%$fecha_filtro%'";
$result = $conn->query($query);

$total_entradas = 0;
$total_salidas = 0;
$ganancia = 0;

while ($row = $result->fetch_assoc()) {
    if ($row['tipo'] === 'entrada') {
        $total_entradas += $row['cantidad'];
        $ganancia += $row['cantidad'] * $row['precio'];
    } else {
        $total_salidas += $row['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Inventario</title>
</head>
<body>
    <div class="container">
        <h1>Inventario Básico</h1>

        <!-- Formulario para agregar registros -->
        <form method="POST" class="form-inline">
            <select name="tipo" required>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
            </select>
            <input type="date" name="fecha" required>
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
            <input type="number" name="cantidad" placeholder="Cantidad" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio" required>
            <button type="submit">Agregar Registro</button>
        </form>

        <!-- Filtro de búsqueda -->
        <form method="GET" class="form-inline">
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre_filtro; ?>">
            <input type="text" name="cantidad" placeholder="Cantidad" value="<?php echo $cantidad_filtro; ?>">
            <input type="text" name="precio" placeholder="Precio" value="<?php echo $precio_filtro; ?>">
            <input type="date" name="fecha" value="<?php echo $fecha_filtro; ?>">
            <button type="submit">Filtrar</button>
        </form>

        <!-- Tabla de registros -->
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                    <th>Código</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ucfirst($row['tipo']); ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['codigo']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals">
            <p>Total Entradas: <?php echo $total_entradas; ?></p>
            <p>Total Salidas: <?php echo $total_salidas; ?></p>
            <p>Ganancia Total: <?php echo $ganancia; ?></p>
        </div>
    </div>
</body>
</html>
