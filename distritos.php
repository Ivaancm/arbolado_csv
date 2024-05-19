<?php
// parte 3: mostrar distritos ordenados por cantidad de arboles totales

// contar arboles por distrito
$distritosContador = [];
foreach ($arboladoData as $arbol) {
    $distrito = $arbol['DISTRITO'];
    if (!isset($distritosContador[$distrito])) {
        $distritosContador[$distrito] = 0;
    }
    $distritosContador[$distrito]++;
}

// Ordenar los distritos por cantidad de arboles (de mayor a menor)
arsort($distritosContador);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Distritos Ordenados por Cantidad de arboles</title>
</head>
<body>
    <h1>Distritos de Madrid Ordenados por Cantidad de arboles</h1>
    <table border="1">
        <tr>
            <th>Distrito</th>
            <th>Cantidad de arboles</th>
        </tr>
        <?php foreach ($distritosContador as $distrito => $cantidad): ?>
            <tr>
                <td><?php echo htmlspecialchars($distrito); ?></td>
                <td><?php echo $cantidad; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
