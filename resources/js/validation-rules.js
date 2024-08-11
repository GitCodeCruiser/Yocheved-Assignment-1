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


extend("integer", {
    validate(value) {
        return /^[0-9]+$/.test(value);
    },
    message: "The value must be a valid integer.",
});

extend("max", {
    validate(value, { max }) {
        const isInteger = /^[0-9]+$/.test(value);
        const isLessThanMax = parseInt(value, 10) <= max;
        return isInteger && isLessThanMax;
    },
    params: ['max'],
    message: "The value must be a valid integer and less than or equal to {max}.",
});

extend('nullableString', {
    validate(value) {
        return value === null || typeof value === 'string';
    },
    message: 'The value must be a string or null.',
});

extend("time", {
    validate(value) {
        // Check if the time is in the format HH:MM
        return /^\d{2}:\d{2}$/.test(value) && isValidTime(value);
    },
    message: "The time must be in HH:MM format and must be a valid time",
});

function isValidTime(value) {
    const [hours, minutes] = value.split(':').map(Number);
    return hours >= 0 && hours < 24 && minutes >= 0 && minutes < 60;
}

extend('notFutureDate', {
    validate(value) {
        const today = new Date();
        const inputDate = new Date(value);

        // Normalize time for comparison
        today.setHours(0, 0, 0, 0);
        inputDate.setHours(0, 0, 0, 0);

        return inputDate <= today;
    },
    message: 'The date must not be in the future.',
});
