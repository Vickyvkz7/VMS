<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitors This Month</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            }   
        <?php
        // visitors_this_month_list.php
        error_reporting(0);
        header('Content-Type: text/html; charset=utf-8');
        require_once 'connect.php';

        // Get first and last day of current month
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');

        // Query visitors for this month
        $sql = "SELECT name, contact, purpose, entry_time, exit_time, visit_date 
                FROM visitors 
                WHERE visit_date BETWEEN ? AND ? 
                ORDER BY visit_date DESC, entry_time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $firstDay, $lastDay);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = 0;
        ?>

         body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            }   

        .header {
            background: linear-gradient(90deg, #ffcc00, #ffeb3b);
            color: #222;
            padding: 24px 0;
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.08);
            padding: 36px 28px;
        }
        h2 {
            color: #ff9800;
            text-align: center;
            margin-bottom: 24px;
            font-size: 1.5rem;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 1rem;
        }
        th {
            background: #fff9c4;
            color: #333;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 2px solid #ffb300;
        }
        tr:hover {
            background: #fffde7;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .inside {
            color: #388e3c;
            font-weight: bold;
        }
        .exited {
            color: #d32f2f;
            font-weight: bold;
        }
        .btn-back {
            background: linear-gradient(90deg, #ffca28, #ffc107);
            color: #222;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 30px;
            cursor: pointer;
            transition: 0.2s;
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-back:hover {
            background: linear-gradient(90deg, #ffc107, #ffca28);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="header">Visitors This Month</div>
    <div class="container">
        <h2>Month: <?php echo date('F Y'); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Purpose</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                $count++;
                $entry = (!empty($row['entry_time']) && $row['entry_time'] != '00:00:00') 
                         ? date('h:i A', strtotime($row['entry_time'])) : '-';
                $exit = (!empty($row['exit_time']) && $row['exit_time'] != '00:00:00') 
                        ? date('h:i A', strtotime($row['exit_time'])) : '-';
                $status = ($row['exit_time'] && $row['exit_time'] != '00:00:00') ? 'Exited' : 'Inside';
                $statusClass = $status === 'Exited' ? 'exited' : 'inside';
                $visitDate = !empty($row['visit_date']) ? date('d-m-Y', strtotime($row['visit_date'])) : '-';
                echo "<tr>
                        <td>{$visitDate}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['purpose']}</td>
                        <td>{$entry}</td>
                        <td>{$exit}</td>
                        <td class='{$statusClass}'>{$status}</td>
                      </tr>";
            }
            if ($count === 0) {
                echo "<tr><td colspan='7' style='text-align:center;color:#d32f2f;font-weight:bold;'>No visitors found for this month.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <button class="btn-back" onclick="window.location.href='../dashbord.html'">Back to Dashboard</button>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
