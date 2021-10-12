<!DOCTYPE html>
<html>
<head>
    <title>Inquiry received for Virtual Lab Application</title>
</head>
<body>
    <h1>Inquiry #{{ $details['indexOfInquiry'] }}</h1>
    <p>Sender Name: {{ $details['userName'] }}</p>
    <p>Sender Email: {{ $details['userEmail'] }}</p>
    <p>Sender's Inquiry : {{ $details['inquiry'] }}</p>

    <p>Thank you</p>
</body>
</html>