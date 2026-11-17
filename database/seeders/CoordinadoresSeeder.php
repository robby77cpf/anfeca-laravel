<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CoordinadoresSeeder extends Seeder
{
    public function run(): void
    {
        $rolId = DB::table('rol')->whereRaw("lower(rol_nombre) = 'coordinador'")->value('rol_id_rol');
        if (! $rolId) {
            throw new \RuntimeException('No existe el rol Coordinador en la tabla rol.');
        }

        // En este orden: zona 1 a zona 7
        $nombres = ['Anuar', 'Saul', 'Roberto', 'Daniel', 'Merino', 'Lemus', 'Adriana'];
        $hash = Hash::make('123456767');

        foreach ($nombres as $i => $nombre) {
            $datos = [
                'usua_nombre' => $nombre,
                'usua_contrasenia' => $hash,
                'usual_id_rol' => $rolId,
                'usua_estatus' => 'Activo',
                'usua_zona' => $i + 1,
            ];

            // Si ya existe un usuario con ese nombre se actualiza; si no, se crea
            $existente = DB::table('usuario')->where('usua_nombre', $nombre)->first();

            if ($existente) {
                DB::table('usuario')->where('usua_id_usuario', $existente->usua_id_usuario)->update($datos);
            } else {
                $datos['usua_id_usuario'] = DB::selectOne("SELECT nextval('usuario_usua_id_usuario_seq') AS id")->id;
                DB::table('usuario')->insert($datos);
            }
        }
    }
}
