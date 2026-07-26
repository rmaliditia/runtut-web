<?php

/** @var mysqli $conn */
session_start();
require '../config/database.php';

// Cek Login
if (!isset($_SESSION['is_login'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_GET['action'] ?? '';

//  1. TAMBAH TUGAS BARU (PREPARED STATEMENT) 
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $category    = $_POST['category'];

    // Jika Anytime dicentang atau due_date kosong, set ke null
    $due_date    = (isset($_POST['anytime']) && $_POST['anytime'] == '1') ? null : (empty($_POST['due_date']) ? null : $_POST['due_date']);

    // Logika Ulangi Task
    $is_recurring = (isset($_POST['is_recurring']) && $_POST['is_recurring'] == '1') ? 1 : 0;
    $recurrence_type = ($is_recurring == 1 && isset($_POST['recurrence_type'])) ? $_POST['recurrence_type'] : null;

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, category, status, due_date, is_recurring, recurrence_type) VALUES (?, ?, ?, ?, 'pending', ?, ?, ?)");
    // i = integer, s = string
    $stmt->bind_param("issssis", $user_id, $title, $description, $category, $due_date, $is_recurring, $recurrence_type);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

//  1.5. EDIT TUGAS (PREPARED STATEMENT) 
elseif ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id     = $_POST['task_id'];
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $category    = $_POST['category'];

    $due_date    = (isset($_POST['anytime']) && $_POST['anytime'] == '1') ? null : (empty($_POST['due_date']) ? null : $_POST['due_date']);

    $is_recurring = (isset($_POST['is_recurring']) && $_POST['is_recurring'] == '1') ? 1 : 0;
    $recurrence_type = ($is_recurring == 1 && isset($_POST['recurrence_type'])) ? $_POST['recurrence_type'] : null;

    // Pastikan hanya bisa mengedit tugas miliknya sendiri (IDOR Protection)
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, due_date = ?, is_recurring = ?, recurrence_type = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssisii", $title, $description, $category, $due_date, $is_recurring, $recurrence_type, $task_id, $user_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

//  2. HAPUS TUGAS (PREPARED STATEMENT) 
elseif ($action == 'delete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

//  3. TANDAI SELESAI & AUTO-CLONE (PREPARED STATEMENT) 
elseif ($action == 'complete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $now = date('Y-m-d H:i:s');

    // Cek dulu data task sebelum di-update (Gunakan parameter binding untuk ID & User)
    $cek_stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $cek_stmt->bind_param("ii", $task_id, $user_id);
    $cek_stmt->execute();
    $result = $cek_stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Update status task ini jadi completed
        $update_stmt = $conn->prepare("UPDATE tasks SET status = 'completed', completed_at = ? WHERE id = ?");
        $update_stmt->bind_param("si", $now, $task_id);
        $update_stmt->execute();
        $update_stmt->close();

        // JIKA TASK INI ADALAH TUGAS BERULANG & BUKAN ANYTIME
        if ($row['is_recurring'] == 1 && $row['due_date'] != NULL) {
            $title = $row['title'];
            $desc  = $row['description'];
            $cat   = $row['category'];
            $rec_type = $row['recurrence_type'];

            $old_date = strtotime($row['due_date']);
            switch ($rec_type) {
                case 'daily':
                    $new_date = date('Y-m-d H:i:s', strtotime('+1 day', $old_date));
                    break;
                case 'weekly':
                    $new_date = date('Y-m-d H:i:s', strtotime('+1 week', $old_date));
                    break;
                case 'monthly':
                    $new_date = date('Y-m-d H:i:s', strtotime('+1 month', $old_date));
                    break;
                case 'yearly':
                    $new_date = date('Y-m-d H:i:s', strtotime('+1 year', $old_date));
                    break;
                default:
                    $new_date = date('Y-m-d H:i:s', strtotime('+1 day', $old_date));
            }

            // KLONING
            $clone_stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, category, status, due_date, is_recurring, recurrence_type) VALUES (?, ?, ?, ?, 'pending', ?, 1, ?)");
            $clone_stmt->bind_param("isssss", $user_id, $title, $desc, $cat, $new_date, $rec_type);
            $clone_stmt->execute();
            $clone_stmt->close();

            // PUTUS RANTAI
            $break_stmt = $conn->prepare("UPDATE tasks SET is_recurring = 0, recurrence_type = NULL WHERE id = ?");
            $break_stmt->bind_param("i", $task_id);
            $break_stmt->execute();
            $break_stmt->close();
        }
    }
    $cek_stmt->close();

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

//  4. BATALKAN SELESAI / UNCOMPLETE (PREPARED STATEMENT) 
elseif ($action == 'uncomplete' && isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE tasks SET status = 'pending', completed_at = NULL WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['HTTP_REFERER']);
}

//  5. BERSIHKAN SEMUA HISTORY (PREPARED STATEMENT) 
elseif ($action == 'clear_history') {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE user_id = ? AND status = 'completed'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../history.php");
}
