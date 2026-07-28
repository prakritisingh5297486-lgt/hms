<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Prescription</title>

    <style>

        body{
            font-family: DejaVu Sans,sans-serif;
            font-size:14px;
            color:#333;
        }

        .header{
            text-align:center;
            border-bottom:2px solid #000;
            padding-bottom:10px;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td,th{
            padding:8px;
            border:1px solid #ddd;
        }

        h2,h3{
            margin:5px 0;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>AuraHMS</h2>

    <p>Hospital Prescription</p>

</div>

<p>

<strong>Patient :</strong>

{{ $prescription->patient->user->name }}

</p>

<p>

<strong>Doctor :</strong>

Dr. {{ $prescription->doctor->user->name }}

</p>

<p>

<strong>Date :</strong>

{{ $prescription->created_at->format('d M Y') }}

</p>

<table>

    <thead>

        <tr>

            <th>Medicine</th>

            <th>Dosage</th>

            <th>Duration</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>{{ $prescription->medicine_name }}</td>

            <td>{{ $prescription->dosage }}</td>

            <td>{{ $prescription->duration }}</td>

        </tr>

    </tbody>

</table>

<br>

<p>

Doctor Signature

</p>

</body>

</html>