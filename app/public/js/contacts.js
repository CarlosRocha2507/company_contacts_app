/**
 * Initializes and configures a modal for creating a new contact.
 * Resets all input fields to their default values, including department, 
 * contact number, country code, email, and department-only checkbox. 
 * Updates the save button to trigger the `createContact` function and 
 * sets its label to "Create".
 */
function createModal() {
    $("#department_id").val("0");
    $("#contact_extension_div").html("<select class='input' name='department_extension' id='department_extension' disabled><option value='0' selected>Select a department first</option></select>");
    $("#contact_number").val("");
    $("#country_id").val("+351");
    $("#contact_email").val("");
    $("#department_only").prop("checked", false);
    $("#contact_save").attr("onclick", "createContact()").text("Create");
}
/**
 * Updates the modal with the provided contact and department details.
 *
 * @param {number} department_id - The ID of the department to be set in the modal.
 * @param {number} contact_id - The ID of the contact to be updated.
 * @param {string} contact_number - The contact's phone number.
 * @param {number} country_id - The ID of the country associated with the contact.
 * @param {string} contact_email - The email address of the contact.
 * @param {string} extension - The department extension to be retrieved and displayed.
 * @param {number} department_only - Indicates if the contact is department-only (1 for true, 0 for false).
 */
async function updateModal(department_id, contact_id, contact_number, country_id, contact_email, extension, department_only) {
    $("#department_id").val(department_id);
    getDepartmentExtension(extension);
    $("#contact_id").val(contact_id);
    $("#contact_number").val(contact_number);
    $("#country_id").val(country_id);
    $("#contact_email").val(contact_email);
    if (department_only == 1)
        $("#department_only").prop("checked", true);
    else
        $("#department_only").prop("checked", false);
    $("#contact_save").attr("onclick", "updateContact(" + contact_id + ")").text("Update");
}
/**
 * Asynchronously creates a new contact by sending a POST request to the server.
 * 
 * This function collects data from various input fields on the page, validates
 * the required fields, and sends the data to the server to create a new contact.
 * It also handles loading states, success notifications, and error handling.
 * 
 * @async
 * @function
 * @throws Will display an error alert if the contact number is empty or if the server
 *         returns an error during the request.
 */
async function createContact() {
    let department_id = $("#department_id").find(":selected").val();
    let extension = $("#department_extension").find(":selected").val();
    let contact = $("#contact_number").val();
    let country_number = $("#country_id").find(":selected").val();
    let contact_email = $("#contact_email").val();
    let department_only = 0;
    if ($("#department_only").is(":checked"))
        department_only = 1;

    if (isEmpty(contact_number)) {
        showAlert("Contact number is required!", "Error", "error");
        return;
    }
    console.log("Extension: ", extension);

    loadingHandler("Creating contact...", 1);
    $.ajax({
        url: 'create-contact',
        type: "POST",
        dataType: 'json',
        data: {
            department_id: department_id,
            extension: extension,
            country_number: country_number,
            contact: contact,
            contact_email: contact_email,
            department_only: department_only
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while creating the contact.", "Error", "error");
                return;
            }
            showAlert("Contact created successfully!", "Success", "success");
            getContacts();
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while creating the contact.", "Error", "error");
        }
    });
}
/**
 * Updates a contact's information by sending an AJAX POST request to the server.
 *
 * @async
 * @function updateContact
 * @param {number} contact_id - The unique identifier of the contact to be updated.
 * @returns {void}
 *
 * @description
 * This function collects data from the DOM, including department ID, contact number,
 * country code, email, and a flag indicating if the update is department-specific.
 * It then sends this data to the server via an AJAX POST request. Upon success, it
 * displays a success message and refreshes the contact list. If an error occurs,
 * it logs the error details to the console and displays an error message to the user.
 *
 * @throws {Error} If the AJAX request fails, an error message is displayed to the user.
 *
 * @example
 * // Call the function to update a contact with ID 123
 * updateContact(123);
 */
async function updateContact(contact_id) {
    let department_id = $("#department_id").find(":selected").val();
    let contact = $("#contact_number").val();
    let country_number = $("#country_id").find(":selected").val();
    let contact_email = $("#contact_email").val();
    let extension = $("#department_extension").find(":selected").val();
    let department_only = 0;
    if ($("#department_only").is(":checked"))
        department_only = 1;

    loadingHandler("Updating contact...", 1);
    $.ajax({
        url: 'update-contact',
        type: "POST",
        dataType: 'json',
        data: {
            department_id: department_id,
            country_number: country_number,
            contact: contact,
            contact_email: contact_email,
            extension: extension,
            contact_id: contact_id,
            department_only: department_only
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while updating the contact.", "Error", "error");
                return;
            }
            showAlert("Contact updated successfully!", "Success", "success");
            getContacts();
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while updating the contact.", "Error", "error");
        }
    });
}
/**
 * Deletes a contact by its ID.
 *
 * Sends an asynchronous POST request to the server to delete the specified contact.
 * Displays a loading message during the operation and handles success or error responses.
 *
 * @async
 * @function deleteContact
 * @param {number|string} contact_id - The unique identifier of the contact to be deleted.
 * @returns {void}
 *
 * @example
 * // Deletes a contact with ID 123
 * deleteContact(123);
 *
 * @throws {Error} Logs error details to the console if the request fails.
 */
async function deleteContact(contact_id) {
    loadingHandler("Deleting contact...", 1);
    $.ajax({
        url: 'delete-contact',
        type: "POST",
        dataType: 'json',
        data: {
            contact_id: contact_id
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while deleting the contact.", "Error", "error");
                return;
            }
            showAlert("Contact deleted successfully!", "Success", "success");
            getContacts();
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while deleting the contact.", "Error", "error");
        }
    });
}
/**
 * Fetches the extension details for a selected department and updates the UI accordingly.
 * 
 * @async
 * @function getDepartmentExtension
 * @param {string} selected - The selected value to be sent to the server for processing.
 * @returns {void}
 * 
 * @description
 * This function sends an AJAX POST request to the server to retrieve the extension details
 * for a specific department. It displays a loading message while the request is in progress
 * and handles both success and error responses. On success, it updates the HTML content of
 * the `#contact_extension_div` element with the retrieved data. On error, it logs the error
 * details to the console and displays an alert with the error message.
 * 
 * @throws {Error} If the AJAX request fails, an error message is displayed to the user.
 */
async function getDepartmentExtension(selected) {
    let department_id = $("#department_id").find(":selected").val();
    loadingHandler("Getting department extension...", 1);
    $.ajax({
        url: 'get-department-extensions',
        type: "POST",
        dataType: 'json',
        data: {
            department_id: department_id,
            selected: selected
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while getting the department extension.", "Error", "error");
                return;
            }
            $("#contact_extension_div").html(response.data);
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while getting the department extension.", "Error", "error");
        }
    });
}
/**
 * Fetches the list of contacts from the server and updates the UI with the retrieved data.
 * Displays a loading message while the request is in progress and handles success or error responses.
 *
 * @async
 * @function getContacts
 * @returns {void}
 *
 * @description
 * This function sends an AJAX POST request to the 'get-contacts' endpoint to retrieve contact data.
 * On success, it updates the HTML content of the element with ID "contacts" using the response data.
 * On failure, it logs detailed error information to the console and displays an error alert to the user.
 *
 * @throws {Error} If the request fails, an error message is displayed to the user.
 */
async function getContacts() {
    loadingHandler("Getting contacts...", 1);
    $.ajax({
        url: 'get-contacts',
        type: "POST",
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while getting the contacts.", "Error", "error");
                return;
            }
            $("#contacts").html(response.data);
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while getting the contacts.", "Error", "error");
        }
    });
}
async function applyFilter() {
    let department_id = $("#department_id_filter").find(":selected").val();
    loadingHandler("Getting contacts...", 1);
    $.ajax({
        url: 'get-contacts-by-department',
        type: "POST",
        dataType: 'json',
        data: {
            department_id: department_id
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while getting the contacts.", "Error", "error");
                return;
            }
            $("#contacts").html(response.data);
        },
        error: function (xhr, status, error) {
            console.error("Error details:", {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON,
                error: error,
                fullResponse: xhr.responseText
            });
            loadingHandler(null, 0);
            showAlert(xhr.responseJSON?.message || "An error occurred while getting the contacts.", "Error", "error");
        }
    });
}
async function clearFilters() {
    $("#department_id_filter").val('0');
}
$("#department_id").change(function () {
    let selectedValue = $(this).val();
    if (selectedValue && selectedValue !== "0") {
        getDepartmentExtension(0);
    }
});