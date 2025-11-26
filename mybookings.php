<!DOCTYPE html>
<html>

<head>
    <title>My Bookings</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        button {
            padding: 6px 12px;
            margin: 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .approve {
            background: #4CAF50;
            color: white;
            border: none;
        }

        .cancel {
            background: #f44336;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <h1>My Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>Skill</th>
                <th>Trainer</th>
                <th>Date</th>
                <th>Time</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="bookingTable"></tbody>
    </table>

    <script>
        function loadBookings() {
            const bookings = JSON.parse(localStorage.getItem("bookings