/**
 * Checks if a given value is empty.
 *
 * A value is considered empty if it is:
 * - `null` or `undefined`
 * - A string with only whitespace characters or an empty string
 *
 * @param {*} value - The value to check for emptiness.
 * @returns {boolean} `true` if the value is empty, otherwise `false`.
 */
function isEmpty(value) {
    return (value == null || (typeof value === "string" && value.trim().length === 0));
}
/**
 * Displays an alert using SweetAlert2 with the specified message, title, and type.
 *
 * @param {string} message - The message to display in the alert.
 * @param {string} title - The title of the alert.
 * @param {string} type - The type of the alert (e.g., 'success', 'error', 'warning', 'info', 'question').
 */
function showAlert(message, title, type) {
    Swal.fire({
        icon: type,
        title: title,
        text: message,
        footer: '<a href="mailto:youremail@gmail.com">Need some help?</a>'
    });
}
/**
 * Displays a customizable alert dialog with a question and confirmation options.
 *
 * @async
 * @function showQuestionAlert
 * @param {string} message - The message to display in the alert dialog.
 * @param {string} title - The title of the alert dialog.
 * @param {"success"|"error"|"warning"|"info"|"question"} type - The type of alert icon to display.
 * @returns {Promise<boolean>} A promise that resolves to `true` if the user confirms, or `false` if the user cancels.
 */
async function showQuestionAlert(message, title, type) {
    const result = await Swal.fire({
        title: title,
        text: message,
        icon: type,
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonColor: "green",
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
    });
    return result.isConfirmed;
}
/**
 * Handles the display of a loading screen with a customizable message.
 *
 * @param {string} text - The text to display on the loading screen.
 * @param {number} state - The state of the loading screen. Use `1` to show the loading screen
 *                         with the specified text, or any other value to hide it and reset the text.
 */
function loadingHandler(text, state) {
    if (state == 1) {
        $("#loadingScreen").show();
        $("#loadingText").text(text);
    } else {
        $("#loadingScreen").hide();
        $("#loadingText").text("A carregar...");
    }
}
/**
 * Toggles the visibility of a password input field and updates the associated icon.
 *
 * @param {string} field_id - The ID of the input field whose type will be toggled between "password" and "text".
 * @param {string} icon_id - The ID of the icon element that visually indicates the current state (e.g., eye or eye-slash).
 */
function showPasswordFieldText(field_id, icon_id) {
    let field = document.getElementById(field_id);
    let icon = document.getElementById(icon_id);
    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}
/**
 * Copies the provided text to the clipboard using the Clipboard API.
 *
 * @param {string} text - The text to be copied to the clipboard.
 * @returns {void}
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
}
/**
 * Exports an HTML table to an Excel file.
 *
 * @param {string} tableId - The ID of the HTML table element to export.
 * @param {string} filename - The desired name for the exported Excel file (without extension).
 *
 * @throws Will display an alert if the table with the specified ID is not found.
 *
 * @example
 * // Exports a table with the ID "myTable" to a file named "contacts.xlsx"
 * exportToExcel("myTable", "contacts");
 */
function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) {
        showAlert("Table not found", "Error", "error");
        return;
    }
    const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });
    XLSX.writeFile(workbook, filename + ".xlsx");
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#search-table tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    }
});
