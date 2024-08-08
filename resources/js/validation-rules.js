import { extend } from "vee-validate";
import { required, email, min } from "vee-validate/dist/rules";

// Add built-in rules
extend("required", required);
extend("email", email);
extend("min", min);

// Add custom rules
extend("password_strength", {
    validate(value) {
        return /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(value);
    },
    message:
        "Password must be at least 8 characters long and contain both letters and numbers",
});

extend("username", {
    validate(value) {
        return /^[a-zA-Z0-9_]{5,15}$/.test(value);
    },
    message:
        "Username must be 5-15 characters long and can only contain letters, numbers, and underscores",
});

extend("date", {
    validate(value) {
        // Check if the date is in the format YYYY-MM-DD
        return /^\d{4}-\d{2}-\d{2}$/.test(value) && !isNaN(new Date(value).getTime());
    },
    message: "The date must be in YYYY-MM-DD format and must be a valid date",
});

extend("name", {
    validate(value) {
        // Check if the name contains only letters and spaces and is 2 to 50 characters long
        return /^[a-zA-Z\s]{2,50}$/.test(value);
    },
    message: "Name must be 2-50 characters long and can only contain letters and spaces",
});