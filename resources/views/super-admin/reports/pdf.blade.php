<!DOCTYPE html>
<html>
<head>
    <title>Hospital Report</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:14px;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        table,th,td{
            border:1px solid #000;
        }
        th,td{
            padding:8px;
        }
    </style>
</head>
<body>

<h2 align="center">AuraHMS Report</h2>

<table>
    <tr>
        <th>Total Patients</th>
        <td>{{ $patients }}</td>
    </tr>

    <tr>
        <th>Total Doctors</th>
        <td>{{ $doctors }}</td>
    </tr>

    <tr>
        <th>Total Appointments</th>
        <td>{{ $appointments }}</td>
    </tr>

    <tr>
        <th>Total Revenue</th>
        <td>₹{{ number_format($revenue,2) }}</td>
    </tr>
</table>

</body>
</html>