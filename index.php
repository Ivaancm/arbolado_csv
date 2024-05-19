<?php

// parte 1: procesar los ficheros csv y mostrar el estado del arbolado por distrito

//ruta a los ficheros csv
$csvFile1 = '01_ArboladoParquesHistoricoSingularesForestales_2023.csv';
$csvFile2 = '02_Estado_ARBOLADO_ParquesHistoricoSingularesForestales_2023.csv';

// funcion para leer el archivo CSV y retornar los datos.
function getArboladoData($csvFile) {
    $data = [];
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        $header = fgetcsv($handle, 1000, ','); // lee la cabecera
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

// obtenemos los datos de los csv
$arboladoData1 = getArboladoData($csvFile1);
$arboladoData2 = getArboladoData($csvFile2);

// fusionamos los datos de los archivos csv
$arboladoData = array_merge($arboladoData1, $arboladoData2);

// obtenemos la lista de distritos unicos
$distritos = array_unique(array_column($arboladoData, 'DISTRITO'));
sort($distritos);

// processamos el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['distrito'])) {
    $distritoSeleccionado = $_POST['distrito'];
    $contador = ['RPLyNC' => 0, 'J' => 0, 'M' => 0, 'V' => 0, 'O' => 0];

    foreach ($arboladoData as $arbol) {
        if($arbol['DISTRITO'] == $distritoSeleccionado) {
            $estado = $arbol['ESTADO'];
            if (isset($contador[$estado])) {
                $contador[$estado]++;
            } else {
                $contador['0']++;
            }
        }
    }

    $totalArboles = array_sum($contador);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado del Arbolado por Distrito</title>
</head>
<body>
    <h1>Estado del Arbolado en Madrid</h1>
    <form method="post" action="">
        <label for="distrito">Selecciona un distrito:</label>
        <select name="distrito" id="distrito">
            <?php foreach ($distritos as $distrito): ?>
                <option value="<?php echo htmlspecialchars($distrito); ?>"><?php echo htmlspecialchars($distrito); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Consultar</button>
    </form>

    <?php if (isset($totalArboles)): ?>
        <h2>Resultados para el distrito: <?php echo htmlspecialchars($distritoSeleccionado); ?></h2>
        <p>Total de árboles: <?php echo $totalArboles; ?></p>
        <ul>
            <li>Recién plantado y no consolidado (RPLyNC): <?php echo $contador['RPLyNC']; ?></li>
            <li>Joven (J): <?php echo $contador['J']; ?></li>
            <li>Maduro (M): <?php echo $contador['M']; ?></li>
            <li>Viejo (V): <?php echo $contador['V']; ?></li>
            <li>Otros (O): <?php echo $contador['O']; ?></li>
        </ul>
    <?php endif; ?>
</body>
</html>