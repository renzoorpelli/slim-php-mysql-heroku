<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];

    // Creamos el usuario
    $usr = new Usuario();
    $usr->usuario = $usuario;
    $usr->clave = $clave;
    $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $usr = $args['usuario'];
    $usuario = Usuario::obtenerUsuario($usr);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['usuario'];
    $clave = $parametros['clave'];
    $id = $parametros['id'];
    Usuario::modificarUsuario($nombre, $clave, $id);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function BorrarUno($request, $response, $args)
  {
    $usuarioId = $args['usuarioId'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function Login($request, $response)
  {

    $parametros = $request->getParsedBody();

    $nombre = $parametros['usuario'];
    $clave = $parametros['clave'];
    $respuesta = Usuario::verificarDatos($nombre, $clave);
    if ($respuesta === 1) {

      $datos = array('usuario' => $nombre);

      $token = AutentificadorJWT::CrearToken($datos);
      $payload = json_encode(array('jwt' => $token));

      $response->getBody()->write($payload);
      return $response
        ->withHeader(
          'Content-Type',
          'application/json'
        );


    } else if ($respuesta === 2) {
      $response->getBody()->write("Datos Invalidos");
    } else {
      $response->getBody()->write("El usuario no existe");
    }


    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }


  public function loginClaim($request, $response)
  {

    $parametros = $request->getParsedBody();

    $nombre = $parametros['usuario'];
    $clave = $parametros['clave'];
    $respuesta = Usuario::verificarDatos($nombre, $clave);
    if ($respuesta === 1) {

      $response->getBody()->write(json_encode(array("obj_json" => "usuario: " . $nombre . " clave: " . $clave)));
    } else if ($respuesta === 2) {
      $response->getBody()->write("Datos Invalidos");
    } else {
      $response->getBody()->write("El usuario no existe");
    }


    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );


  }
}