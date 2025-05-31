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

        
        $e_bill = $data['e_bill'] ?? null;

        $id = $model->addBill(
            $email,
            $data['biller_id'],
            $data['amount'],
            $data['due_date'],
            $data['recurring'],
            $e_bill
        );

        echo json_encode([
            'id' => $id,
            'biller_id' => $data['biller_id'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date'],
            'recurring' => $data['recurring'],
            'e_bill' => $e_bill
        ]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $model->deleteBill($data['id'], $email);
        echo json_encode(['status' => 'deleted']);
        break;
}
