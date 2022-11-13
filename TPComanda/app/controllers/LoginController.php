<?php

 require_once './models/Usuario.php';

 class LoginController extends Usuario{

    /**
     * Verifies if the user exists in the database, then generates a token for the user
     *
     * @param Request $request The request object
     * @param Response $response The response object
     * @param mixed $args The arguments
     * @return Response The response object
     */
    public function verificarUsuario($request, $response, $args){
        $params = $request->getParsedBody();
        $nombre_usuario =trim($params['nombre_usuario']);
        $pass = ($params['clave']);
        
        $user = Usuario::obtenerUsuario($nombre_usuario);
        //$user->printSingleEntityAsTable();
       
      
        $payload = json_encode(array('status' => 'Usuario invalido'));
        if(!is_null($user)){
            if(password_verify($pass, $user->clave) && $user->tiempo_inicial==null){
                $userData = array(                    
                    'nombre' => $user->nombre,                    
                    'tipo' => $user->tipo);
            
                    $payload = json_encode(array(
                    'Token' => AutentificadorJWT::CrearToken($userData), 
                    'response' => 'Valid_user', 
                    'tipo' => $user->tipo));
                $idLoginInserted = Usuario::insertLogin($user);

                if($idLoginInserted > 0){
                    echo "Login inserted successfully";
                }
            }
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
 }
?>