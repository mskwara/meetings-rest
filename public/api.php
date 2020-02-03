<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;
$app->get(
  '/hello/{name}',
  function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
  }
);

$app->get(
    '/api/participants',
    function (Request $request, Response $response, array $args) {
        class MyDB extends SQLite3 {
            function __construct() {
               $this->open('../participants.db');
            }
         }
         $db = new MyDB();
         if(!$db) {
            echo $db->lastErrorMsg();
            exit();
         }

        $sql = "SELECT id, firstname, lastname FROM participant";
        $ret = $db->query($sql);
        $array = [];
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
            $array[] = $row;
        }
        $db->close();
        return $response->withJson($array);
    }
);

$app->get(
    '/api/participants/{id}',
    function (Request $request, Response $response, array $args) {

      class MyDB extends SQLite3 {
          function __construct() {
             $this->open('../participants.db');
          }
       }
       $db = new MyDB();
       if(!$db) {
          echo $db->lastErrorMsg();
          exit();
       }

      $sql = "SELECT id, firstname, lastname FROM participant WHERE id = :id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue('id', $args['id']);
      $ret = $stmt->execute();
      $array = [];
      while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
          $array[] = $row;
      }
      $db->close();
      return $response->withJson($array);
  }

);

$app->post(
    '/api/add',
    function (Request $request, Response $response, array $args) {

      class MyDB extends SQLite3 {
          function __construct() {
             $this->open('../participants.db');
          }
       }
       $db = new MyDB();
       if(!$db) {
          echo $db->lastErrorMsg();
          exit();
       }
      $requestData = $request->getParsedBody();
      $sql = "INSERT INTO participant (firstname, lastname) VALUES(:name, :surname)";
      $stmt = $db->prepare($sql);
      $stmt->bindValue('name', $requestData['firstname']);
      $stmt->bindValue('surname', $requestData['lastname']);
      $ret = $stmt->execute();

      $db->close();
      return $requestData;
  }

);

$app->delete(
    '/api/delete/{id}',
    function (Request $request, Response $response, array $args) {

      class MyDB extends SQLite3 {
          function __construct() {
             $this->open('../participants.db');
          }
       }
       $db = new MyDB();
       if(!$db) {
          echo $db->lastErrorMsg();
          exit();
       }
      $sql = "DELETE FROM participant WHERE id = :id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue('id', $args['id']);
      $ret = $stmt->execute();

      $db->close();
      return $response->withStatus(204);
  }

);




$app->run();
