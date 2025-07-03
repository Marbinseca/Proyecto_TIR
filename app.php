<?php
function calcular_vpn($flujos, $tasa_descuento) {
    $vpn = 0.0;
    foreach ($flujos as $periodo => $flujo) {
        $vpn += $flujo / pow(1 + $tasa_descuento, $periodo);
    }
    return $vpn;
}

function calcular_tir($flujos, $tolerancia = 0.00001, $max_iter = 1000) {
    $tasa_baja = -0.99;
    $tasa_alta = 1.0;
    $tir = 0.0;

    for ($i = 0; $i < $max_iter; $i++) {
        $tir = ($tasa_baja + $tasa_alta) / 2;
        $vpn = calcular_vpn($flujos, $tir);

        if (abs($vpn) < $tolerancia) {
            return $tir;
        }

        if ($vpn > 0) {
            $tasa_baja = $tir;
        } else {
            $tasa_alta = $tir;
        }
    }
    return $tir;
}

// Inicializar variables para persistencia
$inversion = '';
$tasa = '';
$flujos = [];
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'calcular') {
    $inversion = isset($_POST['inversion']) ? $_POST['inversion'] : '';
    $tasa = isset($_POST['tasa']) ? $_POST['tasa'] : '';
    $flujos = isset($_POST['flujos']) ? $_POST['flujos'] : [];

    if ($inversion !== '' && $tasa !== '' && count($flujos) > 0) {
        $inversion_f = floatval($inversion);
        $tasa_f = floatval($tasa) / 100;
        $flujos_f = array_map('floatval', $flujos);
        array_unshift($flujos_f, -$inversion_f);

        $vpn = calcular_vpn($flujos_f, $tasa_f);
        $tir = calcular_tir($flujos_f);

        $resultado = '<div class="result">';
        $resultado .= "<strong>VPN:</strong> " . round($vpn, 2) . "<br>";
        $resultado .= "<strong>TIR:</strong> " . round($tir * 100, 2) . "%";
        $resultado .= '</div>';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'limpiar') {
    // Limpiar todos los campos
    $inversion = '';
    $tasa = '';
    $flujos = [];
    $resultado = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora TIR y VPN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        form {
            background: #fff;
            max-width: 430px;
            margin: 40px auto 0 auto;
            padding: 32px 28px 24px 28px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 80, 120, 0.13), 0 1.5px 8px rgba(60, 80, 120, 0.08);
        }
        h1 {
            text-align: center;
            font-size: 2.1em;
            margin-bottom: 28px;
            color: #2d3a4a;
            letter-spacing: 0.5px;
        }
        label {
            display: block;
            margin-bottom: 18px;
            color: #34495e;
            font-weight: 500;
            font-size: 1.04em;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1.5px solid #d0d7e2;
            border-radius: 7px;
            font-size: 1em;
            background: #f7fafd;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        input[type="number"]:focus {
            border-color: #6c63ff;
            outline: none;
            background: #f0f4ff;
        }
        #flujos {
            margin-bottom: 10px;
        }
        .flujo-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        .flujo-row label {
            flex: 1;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flujo-row input[type="number"] {
            margin-top: 0;
        }
        .btn-agregar, .btn-cerrar {
            background: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 1.3em;
            cursor: pointer;
            margin-left: 4px;
            transition: background 0.18s, transform 0.18s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-agregar {
            background: #43b581;
            font-size: 1.4em;
            margin: 0 auto;
            border-radius: 50%;
            width: 38px;
            height: 38px;
        }
        .btn-agregar:hover {
            background: #36a372;
            transform: scale(1.08);
        }
        .btn-cerrar {
            background: #bdbdbd;
            font-size: 0.9em;
            width: 20px;
            height: 20px;
            margin-left: 0;
        }
        .btn-cerrar:hover {
            background: #8d8d8d;
            transform: scale(1.08);
        }
        button[type="submit"] {
            background: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 28px;
            font-size: 1.08em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.18s, transform 0.18s;
            box-shadow: 0 2px 8px rgba(108, 99, 255, 0.08);
        }
        button[type="submit"]:hover {
            background: #5548c8;
            transform: translateY(-2px) scale(1.03);
        }
        button[type="submit"][style*="background: #bdbdbd"] {
            background: #bdbdbd !important;
            color: #fff;
        }
        button[type="submit"][style*="background: #bdbdbd"]:hover {
            background: #8d8d8d !important;
        }
        .result {
            background: #f0f4ff;
            border-left: 5px solid #6c63ff;
            margin-top: 22px;
            padding: 16px 18px;
            border-radius: 8px;
            font-size: 1.13em;
            color: #2d3a4a;
            box-shadow: 0 1px 6px rgba(108, 99, 255, 0.06);
        }
        @media (max-width: 600px) {
            form {
                padding: 18px 6vw 16px 6vw;
                max-width: 98vw;
            }
            h1 {
                font-size: 1.3em;
            }
            .flujo-row label {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            .flujo-row {
                gap: 4px;
            }
        }
    </style>
    <script>
        let flujoCount = <?php echo max(4, count($flujos)); ?>;

        function agregarFlujo() {
            flujoCount++;
            const contenedor = document.getElementById('flujos');
            const div = document.createElement('div');
            div.className = 'flujo-row';
            div.innerHTML = `<label>Flujo año ${flujoCount}: <input type="number" name="flujos[]" step="any" required></label>
                <button type="button" class="btn-cerrar" onclick="eliminarFlujo(this)" title="Eliminar flujo">&times;</button>`;
            contenedor.appendChild(div);
            actualizarBotonAgregar();
        }

        function eliminarFlujo(btn) {
            const row = btn.parentNode;
            row.parentNode.removeChild(row);
            actualizarNumeracion();
            actualizarBotonAgregar();
        }

        function actualizarNumeracion() {
            const rows = document.querySelectorAll('#flujos .flujo-row label');
            rows.forEach((label, idx) => {
                label.innerHTML = `Flujo año ${idx + 1}: <input type="number" name="flujos[]" step="any" required>`;
            });
            flujoCount = rows.length;
        }

        function actualizarBotonAgregar() {
            document.querySelectorAll('.btn-agregar').forEach(btn => btn.remove());
            const rows = document.querySelectorAll('#flujos .flujo-row');
            if (rows.length > 0) {
                const lastRow = rows[rows.length - 1];
                const btnAgregar = document.createElement('button');
                btnAgregar.type = 'button';
                btnAgregar.className = 'btn-agregar';
                btnAgregar.title = 'Agregar flujo';
                btnAgregar.innerHTML = '+';
                btnAgregar.onclick = agregarFlujo;
                lastRow.appendChild(btnAgregar);
            }
        }

        window.onload = function() {
            const contenedor = document.getElementById('flujos');
            const rows = contenedor.querySelectorAll('.flujo-row');
            rows.forEach(row => {
                if (!row.querySelector('.btn-cerrar')) {
                    const btnCerrar = document.createElement('button');
                    btnCerrar.type = 'button';
                    btnCerrar.className = 'btn-cerrar';
                    btnCerrar.title = 'Eliminar flujo';
                    btnCerrar.innerHTML = '&times;';
                    btnCerrar.onclick = function() { eliminarFlujo(btnCerrar); };
                    row.appendChild(btnCerrar);
                }
            });
            actualizarBotonAgregar();
        }
    </script>
</head>
<body>
    <form method="post" autocomplete="off">
        <h1>Calculadora TIR y VPN</h1>
        <label>Inversión inicial:
            <input type="number" name="inversion" step="any" required
                value="<?php echo htmlspecialchars($inversion); ?>">
        </label>
        <label>Tasa de interés (%):
            <input type="number" name="tasa" step="any" required
                value="<?php echo htmlspecialchars($tasa); ?>">
        </label>
        <div id="flujos">
            <?php
            $num_flujos = max(4, count($flujos));
            for ($i = 0; $i < $num_flujos; $i++) {
                $valor = isset($flujos[$i]) ? htmlspecialchars($flujos[$i]) : '';
                if ($i === 0) {
                    echo '<div style="font-weight: 600; font-size: 1.08em; color: #2d3a4a; margin-bottom: 8px;">Flujos de caja</div>';
                }
                echo '<div class="flujo-row"><label> Año ' . ($i + 1) . ': <input type="number" name="flujos[]" step="any" required value="' . $valor . '"></label></div>';
            }
            ?>
        </div>
        <div style="display: flex; justify-content: center; margin-top: 10px;">
            <button type="button" class="btn-agregar" title="Agregar flujo" onclick="agregarFlujo()">+</button>
        </div>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 10px;">
            <button type="submit" name="accion" value="calcular">Calcular</button>
            <button type="submit" name="accion" value="limpiar" style="background: #bdbdbd; color: #fff;">Limpiar</button>
        </div>
        <?php
        echo $resultado;
        ?>
    </form>
</body>
</html>
