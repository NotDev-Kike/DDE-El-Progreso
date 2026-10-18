<?php
include "../../includes/header.php";
include "../../includes/db.php";
?>

<div style="margin-bottom: 15px; text-align: center;">
    <a href="../../menu.php" 
       style="display: inline-block; background-color: #007bff; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; font-weight: bold;">
        🏠 Volver al Menú
    </a>
</div>

<h2 style="text-align:center; color:#155724;">📋 Listado de Nombramientos - Media</h2>

<div style="margin: 20px 0; text-align:center;">
    <a href="agregar.php" 
       style="display:inline-block; background-color:#28a745; color:white; padding:10px 20px; 
              border-radius:6px; text-decoration:none; font-weight:bold;">
        ➕ Agregar Registro
    </a>
</div>

<table id="tablaNombramiento" class="display nowrap" style="width:100%; font-size:13px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Docente</th>
            <th>Identidad</th>
            <th>Tipo Nombramiento</th>
            <th>Jornada</th>
            <th>Requisito</th>
            <th>Fecha Inicio</th>
            <th>Fecha Vencimiento</th>
            <th>No. Horas</th>

            <th>Centro Educativo Cuenta</th>
            <th>Cargo Cuenta</th>
            <th>Lugar Cuenta</th>
            <th>Municipio Cuenta</th>

            <th>Centro Educativo Reubicación</th>
            <th>Lugar Reubicación</th>
            <th>Cargo Reubicación</th>
            <th>Municipio Reubicación</th>

            <th>Sustituye a</th>
            <th>Identidad Sustituido</th>
            <th>Motivo Vacante</th>

            <th>INST.</th>
            <th>PROGRAMA</th>
            <th>SUB-PROGRAMA</th>
            <th>FONDO</th>

            <th>DEPTO.</th>
            <th>MUNI.</th>
            <th>CENTRO EDUCATIVO</th>
            <th>Nº PLAZA</th>
            <th>Nº HRS</th>

            <th>Asignatura</th>
            <th>Horas</th>
            <th>Modalidad</th>
            <th>Cursos</th>
            <th>Grupos</th>
            <th>Jornada Académica</th>

            <th>Firma Docente</th>
            <th>VB Director Centro</th>
            <th>Firma Revisa</th>
            <th>Teléfono</th>
            <th>VB Director Distrital</th>
            <th>Fecha Entrega</th>
            <th>Fecha Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // ✅ Consulta principal con los nombres reales de las tablas
        $sql = "
            SELECT 
                f.id, f.nombre_docente, f.identidad, f.tipo_nombramiento, f.jornada_trabajo, f.requisito,
                f.fecha_inicio, f.fecha_vencimiento, f.numero_horas,
                f.centro_cuenta, f.cargo_cuenta, f.lugar_cuenta, f.municipio_cuenta,
                f.centro_reubicacion, f.lugar_reubicacion, f.cargo_reubicacion, f.municipio_reubicacion,
                f.sustituye_a, f.identidad_sustituido, f.motivo_vacante,

                e.estructura_inst, e.estructura_programa, e.estructura_subprograma, e.estructura_fondo,
                s.siarhd_depto, s.siarhd_muni, s.siarhd_centro, s.siarhd_num_plaza, s.siarhd_num_horas,
                c.asignatura, c.horas AS carga_num_horas, c.modalidad, c.cursos, c.grupos, c.jornada_academica,

                f.firma_docente, f.vb_director_centro, f.firma_revisa, 
                f.telefono_contacto, f.vb_director_distrital, f.fecha_entrega, f.fecha_registro

            FROM formato_nombramiento_media f
            LEFT JOIN estructura_presupuestaria_nombramiento_media e 
                ON f.id = e.id_formato
            LEFT JOIN siarhd_estructura_nombramiento_media s 
                ON f.id = s.id_formato
            LEFT JOIN carga_academica_nombramiento_media c 
                ON f.id = c.id_formato
            ORDER BY f.id ASC
        ";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id']); ?></td>
            <td style="white-space: normal;"><?= htmlspecialchars($row['nombre_docente']); ?></td>
            <td><?= htmlspecialchars($row['identidad']); ?></td>
            <td><?= htmlspecialchars($row['tipo_nombramiento']); ?></td>
            <td><?= htmlspecialchars($row['jornada_trabajo']); ?></td>
            <td><?= htmlspecialchars($row['requisito']); ?></td>
            <td><?= htmlspecialchars($row['fecha_inicio']); ?></td>
            <td><?= htmlspecialchars($row['fecha_vencimiento']); ?></td>
            <td><?= htmlspecialchars($row['numero_horas']); ?></td>

            <td><?= htmlspecialchars($row['centro_cuenta']); ?></td>
            <td><?= htmlspecialchars($row['cargo_cuenta']); ?></td>
            <td><?= htmlspecialchars($row['lugar_cuenta']); ?></td>
            <td><?= htmlspecialchars($row['municipio_cuenta']); ?></td>

            <td><?= htmlspecialchars($row['centro_reubicacion']); ?></td>
            <td><?= htmlspecialchars($row['lugar_reubicacion']); ?></td>
            <td><?= htmlspecialchars($row['cargo_reubicacion']); ?></td>
            <td><?= htmlspecialchars($row['municipio_reubicacion']); ?></td>

            <td><?= htmlspecialchars($row['sustituye_a']); ?></td>
            <td><?= htmlspecialchars($row['identidad_sustituido']); ?></td>
            <td><?= htmlspecialchars($row['motivo_vacante']); ?></td>

            <td><?= htmlspecialchars($row['estructura_inst']); ?></td>
            <td><?= htmlspecialchars($row['estructura_programa']); ?></td>
            <td><?= htmlspecialchars($row['estructura_subprograma']); ?></td>
            <td><?= htmlspecialchars($row['estructura_fondo']); ?></td>

            <td><?= htmlspecialchars($row['siarhd_depto']); ?></td>
            <td><?= htmlspecialchars($row['siarhd_muni']); ?></td>
            <td><?= htmlspecialchars($row['siarhd_centro']); ?></td>
            <td><?= htmlspecialchars($row['siarhd_num_plaza']); ?></td>
            <td><?= htmlspecialchars($row['siarhd_num_horas']); ?></td>

            <td><?= htmlspecialchars($row['asignatura']); ?></td>
            <td><?= htmlspecialchars($row['carga_num_horas']); ?></td>
            <td><?= htmlspecialchars($row['modalidad']); ?></td>
            <td><?= htmlspecialchars($row['cursos']); ?></td>
            <td><?= htmlspecialchars($row['grupos']); ?></td>
            <td><?= htmlspecialchars($row['jornada_academica']); ?></td>

            <td><?= htmlspecialchars($row['firma_docente']); ?></td>
            <td><?= htmlspecialchars($row['vb_director_centro']); ?></td>
            <td><?= htmlspecialchars($row['firma_revisa']); ?></td>
            <td><?= htmlspecialchars($row['telefono_contacto']); ?></td>
            <td><?= htmlspecialchars($row['vb_director_distrital']); ?></td>
            <td><?= htmlspecialchars($row['fecha_entrega']); ?></td>
            <td><?= htmlspecialchars($row['fecha_registro']); ?></td>

            <td style="text-align:center;">
                <a href="modificar.php?id=<?= $row['id']; ?>" 
                   style="display:inline-block; background-color:#ffc107; color:black; padding:6px 10px; 
                          border-radius:5px; text-decoration:none; margin-bottom:4px; font-weight:bold;">
                    ✏️ Modificar
                </a>
                <a href="eliminar.php?id=<?= $row['id']; ?>" 
                   style="display:inline-block; background-color:#dc3545; color:white; padding:6px 10px; 
                          border-radius:5px; text-decoration:none; font-weight:bold;"
                   onclick="return confirm('¿Seguro que deseas eliminar este registro?');">
                    🗑️ Eliminar
                </a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr><td colspan="45" style="text-align:center;">No hay registros en la tabla.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaNombramiento').DataTable({
        scrollX: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Reporte Nombramientos - Media',
                text: '📊 Exportar a Excel',
                className: 'button',
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
        ],
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});
</script>

<?php include "../../includes/footer.php"; ?>
