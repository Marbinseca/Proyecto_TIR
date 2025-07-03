# 📊 Calculadora de TIR y VPN

Una sencilla pero potente calculadora web para determinar el Valor Presente Neto (VPN) y la Tasa Interna de Retorno (TIR) de un proyecto de inversión.

Está construida con PHP y JavaScript vanilla, en un único archivo para facilitar su portabilidad y despliegue.

## ✨ Características

-   **Cálculo de VPN:** Calcula el Valor Presente Neto a partir de una inversión inicial, una tasa de descuento y una serie de flujos de caja futuros.
-   **Cálculo de TIR:** Estima la Tasa Interna de Retorno utilizando el método de bisección para encontrar la tasa que hace que el VPN sea cero.
-   **Interfaz Dinámica:** Permite agregar o eliminar campos para los flujos de caja de forma interactiva, adaptándose a proyectos de cualquier duración.
-   **Diseño Responsivo:** La interfaz se ajusta para una visualización óptima tanto en dispositivos de escritorio como móviles.
-   **Persistencia de Datos:** Los valores introducidos en el formulario se conservan después de realizar un cálculo para facilitar ajustes y nuevas simulaciones.
-   **Funcionalidad de Limpieza:** Un botón para restablecer todos los campos del formulario fácilmente.

## 🚀 Tecnologías Utilizadas

-   **Backend:** PHP
-   **Frontend:** HTML5, CSS3, JavaScript (Vanilla)

## 🛠️ Cómo Usar

Para ejecutar esta calculadora en tu entorno local, necesitas un servidor web con soporte para PHP (como XAMPP, WAMP, MAMP o el servidor incorporado de PHP).

1.  **Clona o descarga el proyecto.**
2.  **Coloca el archivo `app.php`** en el directorio raíz de tu servidor web (por ejemplo, `htdocs/` en XAMPP).
3.  **Inicia tu servidor web.**
4.  **Abre tu navegador** y accede a la aplicación. Por ejemplo: `http://localhost/app.php` o `http://localhost/Proyecto_TIR/app.php`.

### Uso del Servidor Integrado de PHP

Si tienes PHP instalado en tu sistema, puedes usar su servidor de desarrollo integrado.

1.  Navega a la carpeta del proyecto en tu terminal.
2.  Ejecuta el siguiente comando:
    ```bash
    php -S localhost:8000
    ```
3.  Abre tu navegador y ve a `http://localhost:8000/app.php`.

## 📂 Estructura del Archivo

El proyecto consiste en un único archivo que integra toda la lógica:

-   `app.php`: Contiene el código PHP para los cálculos financieros y el manejo del formulario, el HTML para la estructura de la página, el CSS para los estilos y el JavaScript para la interactividad del frontend.

## 🔍 Vistazo al Código

### Lógica PHP

-   `calcular_vpn($flujos, $tasa_descuento)`: Recibe un array de flujos de caja (incluyendo la inversión inicial como un valor negativo en el período 0) y una tasa de descuento para calcular el VPN.
-   `calcular_tir($flujos, ...)`: Utiliza un algoritmo numérico (método de bisección) para encontrar la TIR. Itera hasta encontrar una tasa que aproxime el VPN a cero con una tolerancia definida.
-   **Manejo de `$_POST`**: El script revisa si la petición es de tipo POST y actúa según la `accion` enviada (`calcular` o `limpiar`), procesando los datos del formulario.

### Lógica JavaScript

-   `agregarFlujo()`: Crea y añade un nuevo campo de entrada para un flujo de caja.
-   `eliminarFlujo(btn)`: Elimina el campo de flujo de caja asociado al botón presionado.
-   `actualizarNumeracion()`: Re-enumera los años de los flujos de caja después de que uno es eliminado para mantener la consistencia.
-   `actualizarBotonAgregar()`: Mueve el botón de "agregar" para que siempre esté después del último flujo de caja.