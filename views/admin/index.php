<h1 class="nombre-pagina">Panel de Administración</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha; ?>"
            />
        </div>
    </form>
</div>

<?php
    if(count($citas) == 0) {
        echo "<h2>No hay citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita = 0;
            foreach($citas as $key => $cita) {

                if($idCita !== $cita->id) { // Inicio del IF
                    $total = 0; // inicia el total en cero una sola vez cada vez que re-inicia una nueva cita
            ?>
                <li>
                    <p>ID: <span><?php echo $cita->id; ?> </span> </p>
                    <p>Hora: <span><?php echo $cita->hora; ?> </span> </p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?> </span> </p>
                    <p>Email: <span><?php echo $cita->email; ?> </span> </p>
                    <p>Teléfono: <span><?php echo $cita->telefono; ?> </span> </p>

                    <h3>Servicios</h3>
            <?php 
                $idCita = $cita->id; 
            } // Fin de IF 
                $total += $cita->precio; /* Acumula el precio para obtener el total por servicio debe ir despues del IF 
                                            para que pueda sumar todos los servicios */
            ?> 
                    <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>          
                <!-- </li> --> <!-- se elimina porque separa el primer servicio de los demas HTML lo cierra automáticamente -->

                <?php
                    $actual = $cita->id; // retorna el id de la base de datos en el cual nos encontramos 
                    $proximo = $citas[$key + 1]->id ?? 0; /* es el índice en el arreglo de la BD arranca en cero
                                                        cuando llegue al ùltimo elemento del arreglo + 1 va a dar 
                                                        un error undefined para evitarlo colocamos OR ?? cero */
                    if( esUltimo($actual , $proximo) ) { ?>
                        <p class="total">Total: <span>$<?php echo $total; ?></span></p>
                    
                        <form action="/api/eliminar" method="POST">
                            
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">

                            <input type="submit" class="boton-eliminar" value="Eliminar">

                        </form>

                <?php } 
        } // Fin del foreach ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>" 

?>