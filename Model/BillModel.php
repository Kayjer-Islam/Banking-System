<?php

class BillModel {
    private $conn;

    public function __construct($con) {
        $this->conn = $con;
    }

    public function getBillsByEmail($email) {
        $stmt = $this->conn->prepare("
            SELECT b.*, br.name AS biller_name, br.category 
            FROM bills b 
            JOIN billers br ON b.biller_id = br.id 
            WHERE b.email = ? 
            ORDER BY due_date ASC
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addBill($email, $biller_id, $amount, $due_date, $recurring, $e_bill = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO bills (email, biller_id, amount, due_date, recurring, e_bill) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sidsss", $email, $biller_id, $amount, $due_date, $recurring, $e_bill);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function deleteBill($id, $email) {
        $stmt = $this->conn->prepare("DELETE FROM bills WHERE id = ? AND email = ?");
        $stmt->bind_param("is", $id, $email);
        return $stmt->execute();
    }

    public function getBillers() {
        $result = $this->conn->query("SELECT * FROM billers");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCalendar($email) {
        $stmt = $this->conn->prepare("
            SELECT due_date, COUNT(*) AS count 
            FROM bills 
            WHERE email = ? 
            GROUP BY due_date
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getInbox($email) {
        $stmt = $this->conn->prepare("
            SELECT id, due_date, e_bill 
            FROM bills 
            WHERE email = ? AND e_bill IS NOT NULL
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
