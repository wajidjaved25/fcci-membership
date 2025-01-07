<form id="request-otp-form" method="POST" action="{{ route('login.request-otp') }}">
    @csrf
    <label for="mobile_number">Mobile Number:</label>
    <input type="text" name="mobile_number" required>
    <button type="submit">Request OTP</button>
</form>

<form id="verify-otp-form" method="POST" action="{{ route('login.verify-otp') }}">
    @csrf
    <label for="mobile_number">Mobile Number:</label>
    <input type="text" name="mobile_number" required>

    <label for="otp">OTP:</label>
    <input type="text" name="otp" required>
    <button type="submit">Verify OTP</button>
</form>
