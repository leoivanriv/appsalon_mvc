<div class="campo">
    <label class="nombre">Nombre</label>
    <input 
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Nombre Servicio"
        value="<?php echo $servicio->nombre; ?>"
        />
</div>
<div class="campo">
    <label class="precio">Precio</label>
    <input 
        type="number"
        id="precio"
        name="precio"
        placeholder="Precio Servicio"
        value="<?php echo $servicio->precio; ?>"
        />
</div>