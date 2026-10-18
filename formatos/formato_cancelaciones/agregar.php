<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>

<div style="margin-bottom: 15px; text-align: center;">
    <a href="javascript:void(0);" onclick="window.history.back();" 
       style="display: inline-block; background-color: #555; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🔙 Volver Atrás
    </a>
    <a href="../../menu.php" 
       style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🏠 Volver al Menú
    </a>
</div>

<h1 style="text-align:center; color:#155724;">📝 Formato de Cancelación</h1>

<style>
.form-container {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    max-width: 1000px;
    margin: 20px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
fieldset {
    border: 2px solid #28a745;
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px;
    background: white;
}
legend {
    font-weight: bold;
    color: #155724;
    background: #e9f7ef;
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid #28a745;
}
label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
}
input[type="text"],
input[type="number"],
input[type="date"],
select {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}
input[type="submit"] {
    background-color: #28a745;
    color: white;
    cursor: pointer;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 8px;
    display: block;
    margin: 20px auto;
    font-weight: bold;
    transition: all 0.3s ease;
}
input[type="submit"]:hover {
    background-color: #218838;
    transform: translateY(-2px);
}
.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 10px 0;
}
.radio-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    margin: 5px 0;
}
.radio-group input[type="radio"] {
    width: auto;
    margin-right: 8px;
}
.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 10px 0;
}
.checkbox-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    margin: 5px 0;
}
.checkbox-group input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}
.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}
.form-group {
    flex: 1;
}
.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 15px 0;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}
.data-table th {
    background: #2c3e50;
    color: white;
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 12px;
}
.data-table td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: center;
}
.data-table input {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}
.btn-small {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.3s ease;
}
.btn-add {
    background: #28a745;
    color: white;
}
.btn-add:hover {
    background: #218838;
}
.btn-remove {
    background: #dc3545;
    color: white;
}
.btn-remove:hover {
    background: #c82333;
}
.required::after {
    content: " *";
    color: #dc3545;
}
.form-section {
    background: #e9f7ef;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
    border-left: 4px solid #28a745;
}
</style>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Iniciar transacción
        $conn->begin_transaction();
        
        // Preparar variables para la tabla principal
        $no_identidad = $_POST['no_identidad'];
        $tipo_accion = $_POST['tipo_accion'];
        $nombre_propuesto = $_POST['nombre_propuesto'];
        $clave_srhd = $_POST['clave_srhd'];
        $nombre_cargo = $_POST['nombre_cargo'];
        $nombre_centro_educativo = $_POST['nombre_centro_educativo'];
        $codigo_centro = $_POST['codigo_centro'];
        $nivel_educativo = $_POST['nivel_educativo'];
        $lugar = $_POST['lugar'];
        $municipio = $_POST['municipio'];
        $departamento = $_POST['departamento'];
        $total_horas_semanales = $_POST['total_horas_semanales'];
        $fecha = $_POST['fecha'];
        $revisado_por = $_POST['revisado_por'];
        $firma_director_departamental = $_POST['firma_director_departamental'];
        $acuerdo_nombramiento = isset($_POST['acuerdo_nombramiento']) ? 1 : 0;
        $datos_cancelacion = isset($_POST['datos_cancelacion']) ? 1 : 0;
        
        // Insertar en tabla principal formato_cancelacion
        $sql = "INSERT INTO formato_cancelacion 
                (no_identidad, tipo_accion, nombre_propuesto, clave_srhd, nombre_cargo, 
                 nombre_centro_educativo, codigo_centro, nivel_educativo, lugar, municipio, 
                 departamento, total_horas_semanales, fecha, revisado_por, 
                 firma_director_departamental, acuerdo_nombramiento, datos_cancelacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssisssii", 
            $no_identidad,
            $tipo_accion,
            $nombre_propuesto,
            $clave_srhd,
            $nombre_cargo,
            $nombre_centro_educativo,
            $codigo_centro,
            $nivel_educativo,
            $lugar,
            $municipio,
            $departamento,
            $total_horas_semanales,
            $fecha,
            $revisado_por,
            $firma_director_departamental,
            $acuerdo_nombramiento,
            $datos_cancelacion
        );
        
        $stmt->execute();
        $cancelacion_id = $conn->insert_id;
        $stmt->close();
        
        // Preparar variables para ciclos
        $ciclo_comun = isset($_POST['ciclo_comun']) ? 1 : 0;
        $ciclo_basico_tecnico = isset($_POST['ciclo_basico_tecnico']) ? 1 : 0;
        $bachillerato_tc = isset($_POST['bachillerato_tc']) ? 1 : 0;
        $otros_ciclo = isset($_POST['otros_ciclo']) ? 1 : 0;
        $comercio = isset($_POST['comercio']) ? 1 : 0;
        $sat = isset($_POST['sat']) ? 1 : 0;
        $semed = isset($_POST['semed']) ? 1 : 0;
        $iii_ciclo_cebot = isset($_POST['iii_ciclo_cebot']) ? 1 : 0;
        $basica = isset($_POST['basica']) ? 1 : 0;
        
        // Insertar ciclos en formato_ciclo_cancelacion
        $sql_ciclos = "INSERT INTO formato_ciclo_cancelacion 
                      (formato_cancelacion_id, ciclo_comun, ciclo_basico_tecnico, bachillerato_tc, 
                       otros_ciclo, comercio, sat, semed, iii_ciclo_cebot, basica) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_ciclos = $conn->prepare($sql_ciclos);
        $stmt_ciclos->bind_param("iiiiiiiiii",
            $cancelacion_id,
            $ciclo_comun,
            $ciclo_basico_tecnico,
            $bachillerato_tc,
            $otros_ciclo,
            $comercio,
            $sat,
            $semed,
            $iii_ciclo_cebot,
            $basica
        );
        $stmt_ciclos->execute();
        $stmt_ciclos->close();
        
        // Insertar estructura presupuestaria si existe
        if (isset($_POST['estructura_inst']) && is_array($_POST['estructura_inst'])) {
            $sql_estructura = "INSERT INTO formato_estructura_presupuestaria_cancelacion 
                              (formato_cancelacion_id, estructura_inst, estructura_prog, estructura_subprog, 
                               estructura_fnd, siarhd_depto, siarhd_munic, siarhd_centro, siarhd_plaza, 
                               siarhd_cargo, siarhd_jornada, siarhd_hrs_sem) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_estructura = $conn->prepare($sql_estructura);
            
            foreach ($_POST['estructura_inst'] as $index => $estructura_inst) {
                if (!empty($estructura_inst)) {
                    $estructura_prog = $_POST['estructura_prog'][$index] ?? null;
                    $estructura_subprog = $_POST['estructura_subprog'][$index] ?? null;
                    $estructura_fnd = $_POST['estructura_fnd'][$index] ?? null;
                    $siarhd_depto = $_POST['siarhd_depto'][$index] ?? null;
                    $siarhd_munic = $_POST['siarhd_munic'][$index] ?? null;
                    $siarhd_centro = $_POST['siarhd_centro'][$index] ?? null;
                    $siarhd_plaza = $_POST['siarhd_plaza'][$index] ?? null;
                    $siarhd_cargo = $_POST['siarhd_cargo'][$index] ?? null;
                    $siarhd_jornada = $_POST['siarhd_jornada'][$index] ?? null;
                    $siarhd_hrs_sem = $_POST['siarhd_hrs_sem'][$index] ?? null;
                    
                    $stmt_estructura->bind_param("isssssssissi",
                        $cancelacion_id,
                        $estructura_inst,
                        $estructura_prog,
                        $estructura_subprog,
                        $estructura_fnd,
                        $siarhd_depto,
                        $siarhd_munic,
                        $siarhd_centro,
                        $siarhd_plaza,
                        $siarhd_cargo,
                        $siarhd_jornada,
                        $siarhd_hrs_sem
                    );
                    $stmt_estructura->execute();
                }
            }
            $stmt_estructura->close();
        }
        
        // Insertar según el tipo de acción
        switch ($_POST['tipo_accion']) {
            case 'Suspensión':
                if (!empty($_POST['suspension_por'])) {
                    $suspension_por = $_POST['suspension_por'];
                    $suspension_a_partir = $_POST['suspension_a_partir'];
                    $suspension_de = $_POST['suspension_de'];
                    $suspension_del = $_POST['suspension_del'];
                    $suspension_al = $_POST['suspension_al'];
                    $suspension_de2 = $_POST['suspension_de2'];
                    $suspension_del2 = $_POST['suspension_del2'];
                    
                    $sql_accion = "INSERT INTO formato_suspension_cancelacion 
                                  (formato_cancelacion_id, suspension_por, suspension_a_partir, 
                                   suspension_de, suspension_del, suspension_al, suspension_de2, suspension_del2) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("isssssss",
                        $cancelacion_id,
                        $suspension_por,
                        $suspension_a_partir,
                        $suspension_de,
                        $suspension_del,
                        $suspension_al,
                        $suspension_de2,
                        $suspension_del2
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
                
            case 'Cancelación':
                if (!empty($_POST['cancelacion_por'])) {
                    $cancelacion_por = $_POST['cancelacion_por'];
                    $cancelacion_a_partir = $_POST['cancelacion_a_partir'];
                    $cancelacion_de = $_POST['cancelacion_de'];
                    $cancelacion_del = $_POST['cancelacion_del'];
                    
                    $sql_accion = "INSERT INTO formato_cancelacion_detalle 
                                  (formato_cancelacion_id, cancelacion_por, cancelacion_a_partir, 
                                   cancelacion_de, cancelacion_del) 
                                  VALUES (?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("issss",
                        $cancelacion_id,
                        $cancelacion_por,
                        $cancelacion_a_partir,
                        $cancelacion_de,
                        $cancelacion_del
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
                
            case 'Destitución':
                if (!empty($_POST['destitucion_por'])) {
                    $destitucion_por = $_POST['destitucion_por'];
                    $destitucion_a_partir = $_POST['destitucion_a_partir'];
                    $destitucion_de = $_POST['destitucion_de'];
                    $destitucion_del = $_POST['destitucion_del'];
                    
                    $sql_accion = "INSERT INTO formato_destitucion_cancelacion 
                                  (formato_cancelacion_id, destitucion_por, destitucion_a_partir, 
                                   destitucion_de, destitucion_del) 
                                  VALUES (?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("issss",
                        $cancelacion_id,
                        $destitucion_por,
                        $destitucion_a_partir,
                        $destitucion_de,
                        $destitucion_del
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
        }
        
        // Insertar asignaturas en formato_asignatura_cancelacion si existen
        if (isset($_POST['asignatura']) && is_array($_POST['asignatura'])) {
            $sql_asignaturas = "INSERT INTO formato_asignatura_cancelacion 
                               (formato_cancelacion_id, asignatura, horas, curso, grupo, jornada) 
                               VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt_asignaturas = $conn->prepare($sql_asignaturas);
            
            foreach ($_POST['asignatura'] as $index => $asignatura) {
                if (!empty($asignatura)) {
                    $horas = $_POST['horas'][$index] ?? null;
                    $curso = $_POST['curso'][$index] ?? null;
                    $grupo = $_POST['grupo'][$index] ?? null;
                    $jornada = $_POST['jornada'][$index] ?? null;
                    
                    $stmt_asignaturas->bind_param("isisss",
                        $cancelacion_id,
                        $asignatura,
                        $horas,
                        $curso,
                        $grupo,
                        $jornada
                    );
                    $stmt_asignaturas->execute();
                }
            }
            $stmt_asignaturas->close();
        }
        
        $conn->commit();
        echo "<script>alert('✅ Formato de cancelación guardado correctamente'); window.location.href='listar.php?success=1';</script>";
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div style='color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin: 10px 0;'>❌ Error al guardar el formulario: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="form-container">
    <form method="POST">
        <fieldset>
            <legend>Información Principal</legend>
            <div class="form-row">
                <div class="form-group">
                    <label class="required">No. Identidad</label>
                    <input type="text" name="no_identidad" required>
                </div>
                <div class="form-group">
                    <label>Nombre del Propuesto</label>
                    <input type="text" name="nombre_propuesto">
                </div>
            </div>
            
            <div class="form-section">
                <label class="required">Tipo de Acción</label>
                <div class="radio-group">
                    <label><input type="radio" name="tipo_accion" value="Suspensión" required> Suspensión</label>
                    <label><input type="radio" name="tipo_accion" value="Cancelación"> Cancelación</label>
                    <label><input type="radio" name="tipo_accion" value="Destitución"> Destitución</label>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Clave S R H D</label>
                    <input type="text" name="clave_srhd">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Información del Cargo</legend>
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre del Cargo</label>
                    <input type="text" name="nombre_cargo">
                </div>
                <div class="form-group">
                    <label>Nombre del Centro Educativo</label>
                    <input type="text" name="nombre_centro_educativo">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Código</label>
                    <input type="text" name="codigo_centro">
                </div>
                <div class="form-group">
                    <label>Nivel</label>
                    <input type="text" name="nivel_educativo">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Lugar</label>
                    <input type="text" name="lugar">
                </div>
                <div class="form-group">
                    <label>Municipio</label>
                    <input type="text" name="municipio">
                </div>
                <div class="form-group">
                    <label>Departamento</label>
                    <input type="text" name="departamento">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Ciclos Educativos</legend>
            <div class="checkbox-group">
                <label><input type="checkbox" name="ciclo_comun"> Ciclo Común</label>
                <label><input type="checkbox" name="ciclo_basico_tecnico"> Ciclo Básico Técnico</label>
                <label><input type="checkbox" name="bachillerato_tc"> Bachillerato T.C.</label>
                <label><input type="checkbox" name="otros_ciclo"> Otros</label>
                <label><input type="checkbox" name="comercio"> Comercio</label>
                <label><input type="checkbox" name="sat"> SAT</label>
                <label><input type="checkbox" name="semed"> Semed</label>
                <label><input type="checkbox" name="iii_ciclo_cebot"> III Ciclo C.E.B OT</label>
                <label><input type="checkbox" name="basica"> BÁSICA</label>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Total de Horas Semanales</label>
                    <input type="number" name="total_horas_semanales">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Asignaturas</legend>
            <div class="table-container">
                <table class="data-table" id="asignaturas-table">
                    <thead>
                        <tr>
                            <th>Asignaturas</th>
                            <th>Horas</th>
                            <th>Curso</th>
                            <th>Grupo</th>
                            <th>Jornada</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="asignatura[]"></td>
                            <td><input type="number" name="horas[]"></td>
                            <td><input type="text" name="curso[]"></td>
                            <td><input type="text" name="grupo[]"></td>
                            <td><input type="text" name="jornada[]"></td>
                            <td><button type="button" class="btn-small btn-add" onclick="agregarFilaAsignatura()">+</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>

        <fieldset>
            <legend>Estructura Presupuestaria</legend>
            <div class="table-container">
                <table class="data-table" id="estructura-table">
                    <thead>
                        <tr>
                            <th>Inst</th>
                            <th>Prog.</th>
                            <th>SubProg.</th>
                            <th>Fnd</th>
                            <th>Depto</th>
                            <th>Munic.</th>
                            <th>Centro</th>
                            <th>Plaza</th>
                            <th>Cargo</th>
                            <th>Jornada</th>
                            <th>Hrs/Sem</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="estructura_inst[]"></td>
                            <td><input type="text" name="estructura_prog[]"></td>
                            <td><input type="text" name="estructura_subprog[]"></td>
                            <td><input type="text" name="estructura_fnd[]"></td>
                            <td><input type="text" name="siarhd_depto[]"></td>
                            <td><input type="text" name="siarhd_munic[]"></td>
                            <td><input type="text" name="siarhd_centro[]"></td>
                            <td><input type="number" name="siarhd_plaza[]"></td>
                            <td><input type="text" name="siarhd_cargo[]"></td>
                            <td><input type="text" name="siarhd_jornada[]"></td>
                            <td><input type="number" name="siarhd_hrs_sem[]"></td>
                            <td><button type="button" class="btn-small btn-add" onclick="agregarFilaEstructura()">+</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>

        <fieldset>
            <legend>Detalles del Motivo</legend>
            <div id="detalles-accion">
                <p style="text-align: center; color: #666; font-style: italic;">Seleccione un tipo de acción para ver los campos específicos</p>
            </div>
        </fieldset>

        <fieldset>
            <legend>Documentos y Firma</legend>
            <div class="form-row">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="fecha">
                </div>
                <div class="form-group">
                    <label>Revisado por</label>
                    <input type="text" name="revisado_por">
                </div>
                <div class="form-group">
                    <label>Firma Director Departamental</label>
                    <input type="text" name="firma_director_departamental">
                </div>
            </div>
            
            <div class="form-section">
                <label><strong>Documentos a Acompañar:</strong></label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="acuerdo_nombramiento"> a) Acuerdo de Nombramiento</label>
                    <label><input type="checkbox" name="datos_cancelacion"> b) Datos que dan Origen a Cancelación (Acta de Defunción, Jubilación, Pensión, Renuncia, Resolución)</label>
                </div>
            </div>
        </fieldset>

        <input type="submit" value="💾 Guardar Formulario">
    </form>
</div>

<script>
    // Función para agregar filas dinámicamente a la tabla de asignaturas
    function agregarFilaAsignatura() {
        const table = document.getElementById('asignaturas-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td><input type="text" name="asignatura[]"></td>
            <td><input type="number" name="horas[]"></td>
            <td><input type="text" name="curso[]"></td>
            <td><input type="text" name="grupo[]"></td>
            <td><input type="text" name="jornada[]"></td>
            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
        `;
    }

    // Función para agregar filas dinámicamente a la tabla de estructura presupuestaria
    function agregarFilaEstructura() {
        const table = document.getElementById('estructura-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td><input type="text" name="estructura_inst[]"></td>
            <td><input type="text" name="estructura_prog[]"></td>
            <td><input type="text" name="estructura_subprog[]"></td>
            <td><input type="text" name="estructura_fnd[]"></td>
            <td><input type="text" name="siarhd_depto[]"></td>
            <td><input type="text" name="siarhd_munic[]"></td>
            <td><input type="text" name="siarhd_centro[]"></td>
            <td><input type="number" name="siarhd_plaza[]"></td>
            <td><input type="text" name="siarhd_cargo[]"></td>
            <td><input type="text" name="siarhd_jornada[]"></td>
            <td><input type="number" name="siarhd_hrs_sem[]"></td>
            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
        `;
    }

    // Función para cargar campos específicos según el tipo de acción
    document.querySelectorAll('input[name="tipo_accion"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const detallesDiv = document.getElementById('detalles-accion');
            const tipoAccion = this.value;
            
            let campos = '';
            
            switch(tipoAccion) {
                case 'Suspensión':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Suspensión Por:</label>
                                <input type="text" name="suspension_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="suspension_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="suspension_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="suspension_del">
                            </div>
                            <div class="form-group">
                                <label>al</label>
                                <input type="date" name="suspension_al">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="suspension_de2">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="suspension_del2">
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'Cancelación':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Cancelación Por:</label>
                                <input type="text" name="cancelacion_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="cancelacion_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="cancelacion_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="cancelacion_del">
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'Destitución':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Destitución Por:</label>
                                <input type="text" name="destitucion_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="destitucion_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="destitucion_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="destitucion_del">
                            </div>
                        </div>
                    `;
                    break;
                    
                default:
                    campos = '<p style="text-align: center; color: #666; font-style: italic;">Seleccione un tipo de acción para ver los campos específicos</p>';
            }
            
            detallesDiv.innerHTML = campos;
        });
    });
</script>

<?php include "../../includes/footer.php"; ?>