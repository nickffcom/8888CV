import { REGEX } from "../constants";
import "moment-timezone";
import { isValidPhoneNumber } from "libphonenumber-js";

export const validEmail = (email) => {
  const emailPattern = REGEX.emailPattern;
  return email.match(emailPattern);
};
export const validConfirmEmail = (email, confirmEmail) => {
  return email === confirmEmail ? true : false;
};

export const validPassword = (password) => {
  const passwordPattern = REGEX.passwordPattern;
  return password.match(passwordPattern);
};

export const validConfirmPassword = (password, confirmPassword) => {
  return password === confirmPassword ? true : false;
};

export const isValidPhoneNum = (value) => {
  if (value) {
    return isValidPhoneNumber(value, "JP");
  }
};

//validate input time for HH:mm format
export const validateTimeInput = (timeString) => {
  // Regular expression for HH:mm format
  const timeRegex = /^(?:\d|[01]\d|2[0-3]):[0-5]\d$/;
  // Test the input string against the regex pattern
  return timeRegex.test(timeString);
};

//check is has duplicate ite in array
export const isDuplicateItems = (array) => {
  const arraySet = new Set();

  for (const array of array) {
    if (arraySet.has(array)) {
      return true;
    } else {
      arraySet.add(array);
    }
  }

  return false; // No duplicate emails found
};