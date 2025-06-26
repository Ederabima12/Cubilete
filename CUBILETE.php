<html>
<head>
    <title>Cubilete</title>
</head>
<body style="background: skyblue;">
<center>

<h1 style="font-family: Serif, sans-serif; font-size: 40px; display: inline-block; padding: 20px 50px; background-color: white; border-radius: 50px;">
    Juego del Cubilete 
</h1>

<?php
session_start();

$figuras = ['9', '10', 'J', 'Q', 'K', 'A'];

function tirarDados() {
    global $figuras;
    $dados = [];
    for ($i = 0; $i < 5; $i++) {
        $figuraAleatoria = $figuras[rand(0, 5)];
        $dados[] = $figuraAleatoria;
    }
    return $dados;
}

function detectarCombinacion($dados) {
    $conteo = array_count_values($dados);
    $valores = array_values($conteo);
    rsort($valores);

    if ($valores[0] == 5) {
        return ['nombre' => 'Quintilla', 'valor' => 7];
    } elseif ($valores[0] == 4) {
        return ['nombre' => 'Poker', 'valor' => 6];
    } elseif ($valores[0] == 3 && in_array(2, $valores)) {
        return ['nombre' => 'Full', 'valor' => 5];
    } elseif ($valores[0] == 3) {
        return ['nombre' => 'Tercia', 'valor' => 4];
    } elseif ($valores[0] == 2 && count(array_keys($conteo, 2)) == 2) {
        return ['nombre' => 'Dos pares', 'valor' => 3];
    } elseif ($valores[0] == 2) {
        return ['nombre' => 'Un par', 'valor' => 2];
    } else {
        return ['nombre' => 'Nada', 'valor' => 1];
    }
}

if (isset($_POST['jugador1'])) {
    $_SESSION['jugador1'] = tirarDados();
}
if (isset($_POST['jugador2'])) {
    $_SESSION['jugador2'] = tirarDados();
}
if (isset($_POST['reiniciar'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function mostrarDados($jugador) {
    global $figuras;
    echo '<p style="font-family: Serif, sans-serif; font-size: 24px; padding: 10px 30px; background-color: white; border-radius: 20px;">';
    echo strtoupper($jugador) . ': ';
    foreach ($_SESSION[$jugador] as $dado) {
        $indice = array_search($dado, $figuras) + 1;
        echo "<img src='imagenes/imagenes{$indice}.jpg' width='100' height='100' style='margin:5px'>";
    }
    $combinacion = detectarCombinacion($_SESSION[$jugador]);
    echo "<br>Combinación: <strong>" . $combinacion['nombre'] . "</strong>";
    echo '</p>';
}
?>

<br><br>
<form method="post">
    <input type="submit" name="jugador1" value="Lanzar Jugador 1" 
           style="padding: 12px 24px; font-size: 18px;" 
           <?php if (isset($_SESSION['jugador1'])) echo "disabled"; ?> />

    <input type="submit" name="jugador2" value="Lanzar Jugador 2" 
           style="padding: 12px 24px; font-size: 18px;" 
           <?php if (!isset($_SESSION['jugador1']) || isset($_SESSION['jugador2'])) echo "disabled"; ?> />
</form>

<br>

<?php
if (isset($_SESSION['jugador1'])) {
    mostrarDados('jugador1');
}
if (isset($_SESSION['jugador2'])) {
    mostrarDados('jugador2');
}

if (isset($_SESSION['jugador1']) && isset($_SESSION['jugador2'])) {
    $mano1 = detectarCombinacion($_SESSION['jugador1']);
    $mano2 = detectarCombinacion($_SESSION['jugador2']);

    echo '<h2 style="background-color: white; padding: 10px 30px; border-radius: 30px; font-family: Serif, sans-serif;">';

    if ($mano1['valor'] > $mano2['valor']) {
        echo "¡Jugador 1 ganó con " . $mano1['nombre'] . "!";
    } elseif ($mano2['valor'] > $mano1['valor']) {
        echo "¡Jugador 2 ganó con " . $mano2['nombre'] . "!";
    } else {
        echo "¡Empate con " . $mano1['nombre'] . "!";
    }

    echo '</h2>';

    echo '<form method="post">
        <input type="submit" name="reiniciar" value="Volver a Jugar" 
               style="padding: 12px 24px; font-size: 18px;" />
    </form>';
}
?>

</center>
</body>
</html>
