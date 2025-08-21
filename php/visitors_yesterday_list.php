<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitors Yesterday</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            margin: 0;
        }

        <?php
        // visitors_yesterday_list.php
        error_reporting(0);
        header('Content-Type: text/html; charset=utf-8');
        require_once 'connect.php';

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $sql = "SELECT name, contact, purpose, entry_time, exit_time 
                FROM visitors 
                WHERE visit_date = ? 
                ORDER BY entry_time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $yesterday);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = 0;


        $stmt->close();
        $conn->close();


?>  
         body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            margin: 0;
        }

        .header {
            background: linear-gradient(90deg, #2e7d32 0%, #66bb6a 100%);
            color: #fff;
            padding: 24px 0;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 4px 16px rgba(46,125,50,0.15);
        }
        .container {
            max-width: 950px;
            margin: 48px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(46,125,50,0.12);
            padding: 44px 32px;
        }
        h2 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 28px;
            font-size: 1.5rem;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
            background: #f9fff7;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 16px 12px;
            border-bottom: 1px solid #c8e6c9;
            text-align: left;
            font-size: 1.05rem;
        }
        th {
            background: linear-gradient(90deg, #a5d6a7 0%, #e8f5e9 100%);
            color: #2e7d32;
            font-weight: 700;
            font-size: 1.13rem;
            border-bottom: 2px solid #66bb6a;
        }
        tr:hover {
            background: #f1f8e9;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .inside {
            color: #1b5e20;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .exited {
            color: #c62828;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .btn-back {
            background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 1.15rem;
            font-weight: 600;
            margin-top: 32px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 8px rgba(46,125,50,0.15);
        }
        .btn-back:hover {
            background: linear-gradient(90deg, #2e7d32 0%, #43a047 100%);
            transform: scale(1.06);
        }
    </style>
</head>
<body>
    <div class="header">Visitors Yesterday</div>
    <div class="container">
        <h2>Date: <?php echo date('d-m-Y', strtotime($yesterday)); ?></h2>
        <table>
            <thead>
                <tr>
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
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['purpose']}</td>
                        <td>{$entry}</td>
                        <td>{$exit}</td>
                        <td class='{$statusClass}'>{$status}</td>
                      </tr>";
            }
            if ($count === 0) {
                echo "<tr><td colspan='6' style='text-align:center;color:#c62828;font-weight:bold;'>No visitors found for yesterday.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <button class="btn-back" onclick="window.location.href='../dashbord.html'">Back to Dashboard</button>
    </div>
</body>
</html>
