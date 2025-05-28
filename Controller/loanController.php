<?php
session_start();
require_once(__DIR__ . '/../Model/db.php');

// Use user email from session for authentication
$email = $_SESSION['email'] ?? null;
if (!$email) {
    http_response_code(401);
    echo json_encode(['error'=>'Not authenticated']);
    exit();
}

function fetchLoanProducts() {
    global $con;
    $res = mysqli_query($con, 'SELECT * FROM loan_products');
    $arr = [];
    while ($row = mysqli_fetch_assoc($res)) $arr[] = $row;
    return $arr;
}

function submitLoanApplication($email, $loan_product_id, $income_proof) {
    global $con;
    $stmt = mysqli_prepare($con, "INSERT INTO loan_applications (user_email, loan_product_id, income_proof) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sis', $email, $loan_product_id, $income_proof);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getApplicationsByUser($email) {
    global $con;
    $stmt = mysqli_prepare($con, "SELECT la.*, lp.name AS product_name
                                  FROM loan_applications la
                                  JOIN loan_products lp ON la.loan_product_id=lp.id
                                  WHERE la.user_email=?
                                  ORDER BY la.submission_date DESC");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $arr = [];
    while ($row = mysqli_fetch_assoc($res)) $arr[] = $row;
    mysqli_stmt_close($stmt);
    return $arr;
}

function signAgreement($app_id, $email) {
    global $con;
    $stmt = mysqli_prepare($con, "UPDATE loan_applications SET agreement_signed=1, status='signed' WHERE id=? AND user_email=?");
    mysqli_stmt_bind_param($stmt, "is", $app_id, $email);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// Routing
header('Content-Type: application/json');
$action = $_GET['action'] ?? '';

if ($action === 'products') {
    echo json_encode(fetchLoanProducts());
} elseif ($action === 'apply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_product_id = $_POST['loan_product_id'];
    $income_proof = '';
    if (isset($_FILES['income_proof']) && $_FILES['income_proof']['error'] == 0) {
        $dir = __DIR__ . '/../View/uploads/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $filename = time() . "_" . basename($_FILES['income_proof']['name']);
        move_uploaded_file($_FILES['income_proof']['tmp_name'], $dir . $filename);
        $income_proof = "uploads/" . $filename;
    }
    $success = submitLoanApplication($email, $loan_product_id, $income_proof);
    echo json_encode(['success' => $success]);
} elseif ($action === 'status') {
    echo json_encode(getApplicationsByUser($email));
} elseif ($action === 'esign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);
    $success = signAgreement($application_id, $email);
    echo json_encode(['success' => $success]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
}
?>