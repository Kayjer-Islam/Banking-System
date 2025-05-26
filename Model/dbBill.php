<?php
class BillModel {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function getBillsByEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM bills WHERE email = ? ORDER BY due_date ASC");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function addBill($email, $biller, $amount, $due_date, $recurring) {
    $stmt = $this->conn->prepare("INSERT INTO bills (email, biller, amount, due_date, recurring) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $email, $biller, $amount, $due_date, $recurring);
    $stmt->execute();
    return $stmt->insert_id;
  }

  public function deleteBill($id, $email) {
    $stmt = $this->conn->prepare("DELETE FROM bills WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $id, $email);
    return $stmt->execute();
  }
}
?>
