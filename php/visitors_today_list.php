<!-- visitors_today_list.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitors Today</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e3f0ff 0%, #f8fafc 100%);
            margin: 0;
        }
        <?php
        require_once 'connect.php';
        $date = date('Y-m-d');
        $sql = "SELECT name, contact, purpose, entry_time, exit_time FROM visitors WHERE DATE(created_at) = ? ORDER BY entry_time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $visitors = [];
        while ($row = $result->fetch_assoc()) {
            $entry = !empty($row['entry_time']) && $row['entry_time'] != '00:00:00' ? date('h:i:s A', strtotime($row['entry_time'])) : '-';
            $exit = !empty($row['exit_time']) && $row['exit_time'] != '00:00:00' ? date('h:i:s A', strtotime($row['exit_time'])) : '-';
            $status = ($row['exit_time'] && $row['exit_time'] != '00:00:00') ? 'Exited' : 'Inside';
            $statusClass = $status === 'Exited' ? 'exited' : 'inside';
            $visitors[] = [
                'name' => $row['name'],
                'contact' => $row['contact'],
                'purpose' => $row['purpose'],
                'entry' => $entry,
                'exit' => $exit,
                'status' => $status,
                'statusClass' => $statusClass
            ];
        }
        $stmt->close();
        $conn->close();
        ?>
            border-collapse: collapse;
            margin-top: 22px;
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Visitors Today</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #e3f0ff 0%, #f8fafc 100%); margin: 0; }
                    .header { background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%); color: #fff; padding: 24px 0 20px 0; text-align: center; font-size: 2rem; font-weight: bold; letter-spacing: 1px; box-shadow: 0 4px 16px rgba(25,118,210,0.08); }
                    .container { max-width: 950px; margin: 48px auto; background: #fff; border-radius: 16px; box-shadow: 0 6px 32px rgba(25,118,210,0.12); padding: 44px 32px; }
                    h2 { color: #1976d2; text-align: center; margin-bottom: 28px; font-size: 1.5rem; font-weight: 600; }
                    table { width: 100%; border-collapse: collapse; margin-top: 22px; background: #f4f8fd; border-radius: 8px; overflow: hidden; }
                    th, td { padding: 16px 12px; border-bottom: 1px solid #cfd8dc; text-align: left; font-size: 1.05rem; }
                    th { background: linear-gradient(90deg, #bbdefb 0%, #e3f2fd 100%); color: #1976d2; font-weight: 700; font-size: 1.13rem; border-bottom: 2px solid #64b5f6; }
                    tr:last-child td { border-bottom: none; }
                    .inside { color: #1565c0; font-weight: bold; letter-spacing: 0.5px; }
                    .exited { color: #d32f2f; font-weight: bold; letter-spacing: 0.5px; }
                    .btn-back { background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%); color: #fff; border: none; border-radius: 8px; padding: 12px 32px; font-size: 1.15rem; font-weight: 600; margin-top: 32px; cursor: pointer; transition: background 0.2s, transform 0.2s; display: block; margin-left: auto; margin-right: auto; box-shadow: 0 2px 8px rgba(25,118,210,0.08); }
                    .btn-back:hover { background: linear-gradient(90deg, #0d47a1 0%, #1976d2 100%); transform: scale(1.06); }
                </style>
            </head>
            <body>
                <div class="header">Visitors Today</div>
                <div class="container">
                    <h2>Date: <?php echo htmlspecialchars($date); ?></h2>
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
                        <?php if (count($visitors) > 0): ?>
                            <?php foreach ($visitors as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                                    <td><?php echo htmlspecialchars($row['entry']); ?></td>
                                    <td><?php echo htmlspecialchars($row['exit']); ?></td>
                                    <td class="<?php echo $row['statusClass']; ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;color:#e53935;font-weight:bold;">No visitors found for today.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <button class="btn-back" onclick="window.location.href='../dashbord.html'">Back to Dashboard</button>
                </div>
            </body>
            </html>
      
</body>
</html>

