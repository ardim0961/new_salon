<?php
require_once "../config/db.php";

class BookingController {

    public function createBooking($user_id, $service_id, $employee_id, $tanggal, $jam) {
        global $conn;

        $conn->begin_transaction();

        try {
            // cek slot
            $stmt = $conn->prepare("
                SELECT id FROM bookings 
                WHERE tanggal=? AND jam=? AND employee_id=? 
                FOR UPDATE
            ");
            $stmt->bind_param("ssi", $tanggal, $jam, $employee_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                throw new Exception("Slot sudah diambil");
            }

            // insert booking
            $stmt = $conn->prepare("
                INSERT INTO bookings (user_id, service_id, employee_id, tanggal, jam)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiiss", $user_id, $service_id, $employee_id, $tanggal, $jam);
            $stmt->execute();

            $booking_id = $stmt->insert_id;

            // ambil harga
            $service = $conn->query("SELECT price FROM services WHERE id=$service_id")->fetch_assoc();

            // insert payment
            $stmt = $conn->prepare("
                INSERT INTO payments (booking_id, amount, status)
                VALUES (?, ?, 'pending')
            ");
            $stmt->bind_param("ii", $booking_id, $service['price']);
            $stmt->execute();

            $conn->commit();

            return $booking_id;

        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }
}
?>