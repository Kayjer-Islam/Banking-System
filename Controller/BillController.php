<?php

session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

require_once '../Model/db.php';
require_once '../Model/BillModel.php';

$model = new BillModel($con);
$email = $_SESSION['email'];
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($action) {
            case 'calendar':
                echo json_encode($model->getCalendar($email));
                break;
            case 'inbox':
                echo json_encode($model->getInbox($email));
                break;
            case 'billers':
                echo json_encode($model->getBillers());
                break;
            default:
                echo json_encode($model->getBillsByEmail($email));
                break;
        }
        break;

case 'POST':
    $data = $_POST;

    
    $biller_id = filter_var($data['biller_id'], FILTER_VALIDATE_INT);
    $amount = filter_var($data['amount'], FILTER_VALIDATE_FLOAT);
    $due_date = $data['due_date'];
    $recurring = $data['recurring'];
    $e_bill = $data['e_bill'] ?? null;

    if (!$biller_id || !$amount || empty($due_date) || empty($recurring)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input"]);
        exit();
    }

    $id = $model->addBill($email, $biller_id, $amount, $due_date, $recurring, $e_bill);

    echo json_encode([
        'id' => $id,
        'biller_id' => $biller_id,
        'amount' => $amount,
        'due_date' => $due_date,
        'recurring' => $recurring,
        'e_bill' => $e_bill
    ]);
    break;


    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $model->deleteBill($data['id'], $email);
        echo json_encode(['status' => 'deleted']);
        break;
}
