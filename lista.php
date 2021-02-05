<?php
 
function validaCPF($cpf){
 
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
 
    if (strlen($cpf) != 11) {
        return 0;
    }
 
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return 0;
    }
 
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return 0;
        }
    }
    return $cpf;
}

function valida_email($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    } else {
        return false;
    }
}

function valida_data($data) {
    if((strlen($data) < 8) || (strlen($data) > 11)) {
        return false;
    }

    if(!strpos($data, "/")) {
        return false;
    }

    $partes = explode('/', $data );

    $mes = $partes[0];
    $dia = $partes[1];
    $ano = $partes[2];

    if(strlen($ano) < 4) {
        return false;
    }

    if(!checkdate($mes, $dia, $ano)) {
        return false;
    }
    return true;
}
 
function LerPacientesCSV(){
 
    $delimitador = ',';
    $cerca = '"';
 
    // Abrir arquivo para leitura
    $f = fopen('pacientes.csv', 'r');
    if ($f) {
        
        $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
 
        while (!feof($f)) {
 
            $linha = fgetcsv($f, 0, $delimitador, $cerca);
            if (!$linha) {
                continue;
            }
 
            $registro = array_combine($cabecalho, $linha);
            
            $arr_type = ['A+','B+','O+','AB+','A-','O-','B-','AB-'];
 
            if(in_array($registro['tiposanguineo'],$arr_type)){
                $tipo_sanguineo = $registro['tiposanguineo'];
            }else{
                $tipo_sanguineo = 0;
            }
 
            echo "<table>";
 
            echo "<tr>";
            echo "<td width='100' style='border: 2px solid green'>";
            echo utf8_encode($registro['nome']);
            echo " ";
            echo utf8_encode($registro['sobrenome']);
            echo "</td>";
            echo "<td width='100'  style='border: 2px solid green'>";
            echo validaCPF($registro['cpf']);
            echo "</td>";
            echo "<td width='300' style='border: 2px solid green'>";
            echo valida_email($registro['email']);
            echo "</td>";
            echo "<td width='100' style='border: 2px solid green'>";
            echo valida_data('d/m/Y', strtotime($registro['datanascimento']));
            echo "</td>";
            echo "<td width='50' style='border: 2px solid green'>";
            echo $tipo_sanguineo;
            echo "</td>";
            echo "<td width='50' style='border: 2px solid green'>";
            echo $registro['genero'];
            echo "</td>";
            echo "<td width='300' style='border: 2px solid green'>";
            echo utf8_encode($registro['endereco']);
            echo "</td>";
            echo "<td width='100' style='border: 2px solid green'>";
            echo utf8_encode($registro['cidade']);
            echo "</td>";
            echo "<td width='50' style='border: 2px solid green'>";
            echo utf8_encode($registro['estado']);
            echo "</td>";
            echo "<td width='100' style='border: 2px solid green'>";
            echo $registro['cep'];
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        }
 
        fclose($f);
    }
 
}
 
LerPacientesCSV();