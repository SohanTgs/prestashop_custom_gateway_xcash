<?php 

function viser_system_details(){
    $system['prefix'] = 'ps_';
    $system['real_name'] = 'viserlab';
    $system['name'] = $system['prefix'].'viserlab';
    $system['version'] = '1.0';
    $system['build_version'] = '1';
    return $system;
}

function dd(...$args){
    foreach($args as $data){
            echo "<pre style='background:#18171B; color:#00ff4e; padding: 10px;'>";
            print_r($data);
            echo "</pre>";
        } 
    die();
}

function xcash_redirect($url){
    Tools::redirect($url);
}