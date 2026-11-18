<?php

namespace App\Services;

/**
 * Genera el Acta en el mismo formato del reporte de ejemplo
 * (tabla HTML con extensión .xls, que Excel abre directamente).
 */
class ActaExcel
{
    /** Columnas en el mismo orden que el ejemplo: [llave del arreglo, encabezado, estilo opcional] */
    private const COLUMNAS = [
        ['folio', 'Folio'],
        ['zona', 'Zona'],
        ['nombre', 'Nombre'],
        ['curp', 'CURP'],
        ['no_afiliacion', 'No. Afiliaci&oacute;n'],
        ['institucion', 'Insctituci&oacute;n de Procedencia', 'width: 200px'],
        ['facultad', 'Facultad/Escuela/Divisi&oacute;n', 'width: 200px'],
        ['campus', 'Campus/Unidad/Sede'],
        ['certificacion', 'Certificaci&oacute;n'],
        ['estatus', 'Estatus'],
        ['no_refrendo', 'No. Refrendo'],
        ['puntaje', 'Puntaje de evaluaci&oacute;n'],
        ['art14', 'Art. 14', 'width: 60px'],
        ['dictamen', 'Dictamen', 'width: 80px'],
        ['no_certificado', 'No. de Certificado'],
        ['licenciatura', 'Licenciatura'],
        ['maestria', 'Maestr&iacute;a'],
        ['doctorado', 'Doctorado'],
        ['tel_particular', 'Tel. Particular'],
        ['tel_celular', 'Tel. Celular'],
        ['correo', 'Correo Electr&oacute;nico'],
        ['url_docs', 'Documentos Probatorios (URL)'],
        ['tipo_certificacion', 'Tipo Certificaci&oacute;n a concursar'],
    ];

    /**
     * Asigna el No. de Certificado de forma consecutiva por zona:
     * toda la zona 1, luego la 2, ... hasta la 7. Solo cuentan las filas
     * con 'concluido' = true. $inicio es el primer número a usar
     * (último conteo + 1).
     */
    public static function numerar(array $filas, int $inicio = 1): array
    {
        usort($filas, fn ($a, $b) =>
            [(int) $a['zona'], (int) ($a['orden'] ?? 0)] <=> [(int) $b['zona'], (int) ($b['orden'] ?? 0)]);

        $n = $inicio;
        foreach ($filas as &$f) {
            $f['no_certificado'] = !empty($f['concluido']) ? $n++ : '';
        }
        return $filas;
    }

    public static function html(array $filas, int $inicio = 1): string
    {
        $filas = self::numerar($filas, $inicio);

        $out  = "<!DOCTYPE html>\n<html>\n<head>\n    <meta http-equiv='Content-Type; Pragma' content='text/html' charset='utf-8'/>\n</head>\n<body>\n";
        $out .= "<table style=\"text-align: center; border: solid thin\">\n";
        $out .= "    <tr style=\"border: solid #000; background-color: #C1C1C1; height: 50px\">\n";
        foreach (self::COLUMNAS as $c) {
            $out .= '        <th' . (isset($c[2]) ? ' style="' . $c[2] . '"' : '') . '>' . $c[1] . "</th>\n";
        }
        $out .= "    </tr>\n";

        foreach ($filas as $f) {
            $out .= "<tr style='text-align: center; border: solid thin'>";
            foreach (self::COLUMNAS as $c) {
                $out .= '<td>' . htmlspecialchars((string) ($f[$c[0]] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
            }
            $out .= '</tr>';
        }

        return $out . "</table>\n</body>\n\n</html>";
    }
}