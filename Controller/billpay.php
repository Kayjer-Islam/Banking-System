<?php
session_start();
if (!isset($_SESSION['email'])) {
  http_response_code(403);
  echo "Unauthorized";
  exit();
}

require_once '../dbBill.php';
$conn = new mysqli('localhost', 'root', '', 'banking_system');
$model = new dbBill($conn);
$email = $_SESSION['email'];

header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    echo json_encode($model->getBillsByEmail($email));
    break;

  case 'POST':
    $data = $_POST;
    $id = $model->addBill($email, $data['biller'], $data['amount'], $data['date'], $data['recurring']);
    echo json_encode([
      'id' => $id,
      'biller' => $data['biller'],
      'amount' => $data['amount'],
      'due_date' => $data['date'],
      'recurring' => $data['recurring']
    ]);
    break;

  case 'DELETE':
    parse_str(file_get_contents("php://input"), $data);
    $model->deleteBill($data['id'], $email);
    echo json_encode(['status' => 'deleted']);
    break;
}
?>
