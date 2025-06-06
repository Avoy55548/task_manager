function validateAdminForm() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password1 = document.getElementById("password").value;
    const password2 = document.getElementById("confirm_password").value;
    const dob = document.getElementById("dob").value;
    const location = document.getElementById("location").value.trim();
    const city = document.getElementById("city").value.trim();

    // Error elements
    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const password1Error = document.getElementById("password1Error");
    const password2Error = document.getElementById("password2Error");
    const dobError = document.getElementById("dobError");
    const locationError = document.getElementById("locationError");
    const cityError = document.getElementById("cityError");

    // Reset errors
    nameError.textContent = "";
    emailError.textContent = "";
    password1Error.textContent = "";
    password2Error.textContent = "";
    dobError.textContent = "";
    locationError.textContent = "";
    cityError.textContent = "";

    let isValid = true;

    // Name validation: no special chars except dot, only one dot
    const specialCharPattern = /[^a-zA-Z0-9 .]/;
    const dotCount = (name.match(/\./g) || []).length;
    if (specialCharPattern.test(name)) {
        nameError.textContent = "Special character detected in name!";
        isValid = false;
    }
    if (dotCount > 1) {
        nameError.textContent = "Name cannot contain more than one dot.";
        isValid = false;
    }

    // Email validation (simple)
    if (!email.includes("@") || !email.includes(".")) {
        emailError.textContent = "Invalid email format.";
        isValid = false;
    }

    // Password validation
    if (password1.length < 3) {
        password1Error.textContent = "Password must be at least 4 characters long.";
        isValid = false;
    }
    if (password1 !== password2) {
        password2Error.textContent = "Passwords do not match.";
        isValid = false;
    }

    // DOB validation (must be at least 18)
    if (dob) {
        const birthDate = new Date(dob);
        if (isNaN(birthDate.getTime())) {
            dobError.textContent = "Invalid Date of Birth.";
            isValid = false;
        } else {
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age < 18) {
                dobError.textContent = "You must be at least 18 years old.";
                isValid = false;
            }
        }
    }

    // Location and City validation (not empty)
    if (location.length === 0) {
        locationError.textContent = "Location is required.";
        isValid = false;
    }
    if (city.length === 0) {
        cityError.textContent = "City is required.";
        isValid = false;
    }

    return isValid;
}