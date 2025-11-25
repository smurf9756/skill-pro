<script>
    // Pre-fill skill from URL if available
    const urlParams = new URLSearchParams(window.location.search);
    const selectedSkill = urlParams.get("skill");

    if (selectedSkill) {
        document.getElementById("skill").value = selectedSkill;
    }

    // Booking form validation & saving
    document.getElementById("bookingForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let fullname = document.getElementById("fullname").value;
        let email = document.getElementById("email").value;
        let phone = document.getElementById("phone").value;
        let skill = document.getElementById("skill").value;
        let date = document.getElementById("date").value;
        let message = document.getElementById("message").value;

        if (!skill || !date) {
            alert("Please complete all required fields.");
            return;
        }

        // Create booking object
        let booking = { fullname, email, phone, skill, date, message };

        // Save to localStorage
        let bookings = JSON.parse(localStorage.getItem("bookings")) || [];
        bookings.push(booking);
        localStorage.setItem("bookings", JSON.stringify(bookings));

        alert("Booking confirmed for " + skill + " on " + date + "! ✅");

        this.reset();
    });
</script>
