<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["crear"])) {
  
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $sql = "INSERT INTO usuarios (nombre, correo) VALUES ('$nombre', '$correo')";

        if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro creado con éxito";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST["actualizar"])) {
   
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $sql = "UPDATE usuarios SET nombre='$nombre', correo='$correo' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Registro actualizado con éxito";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
} elseif (isset($_GET["borrar"])) {

    $id = $_GET["borrar"];
    $sql = "DELETE FROM usuarios WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro borrado con éxito";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}


$sql = "SELECT id, nombre, correo FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Usuarios</title>
    <!-- Aca debe ir el <script></script> o el link a los estilos -->
</head>

<body>
    <h1>Crear Nuevo Usuario</h1>
    <form method="post" action="crud_usuarios.php">
        Nombre: <input type="text" name="nombre"><br><br>
        Correo: <input type="text" name="correo"><br><br>
        <input type="submit" name="crear" value="Crear">
    </form>

    <h2>Lista de Usuarios</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["correo"] . "</td>";
                echo "<td><a href='crud_usuarios.php?borrar=" . $row["id"] . "'>Borrar</a> | <a href='crud_usuarios.php?editar=" . $row["id"] . "'>Editar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 resultados</td></tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_GET["editar"])) {
        $id_editar = $_GET["editar"];
        $sql_editar = "SELECT id, nombre, correo FROM usuarios WHERE id=$id_editar";
        $result_editar = $conn->query($sql_editar);
        if ($result_editar->num_rows == 1) {
            $row_editar = $result_editar->fetch_assoc();
    ?>
            <h2>Editar Usuario</h2>
            <form method="post" action="crud_usuarios.php">
                <input type="hidden" name="id" value="<?php echo $row_editar['id']; ?>">
                Nombre: <input type="text" name="nombre" value="<?php echo $row_editar['nombre']; ?>"><br><br>
                Correo: <input type="text" name="correo" value="<?php echo $row_editar['correo']; ?>"><br><br>
                <input type="submit" name="actualizar" value="Actualizar">
            </form>
    <?php
        }
    }
    ?>

</body>

</html>

<?php $conn->close(); ?>