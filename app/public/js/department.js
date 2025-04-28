/**
 * Asynchronously creates a new department by sending a POST request to the server.
 * 
 * This function collects the department name, code, and extensions from the DOM,
 * validates the input, and sends the data to the server. It handles both success
 * and error responses, displaying appropriate alerts and reloading the page upon success.
 * 
 * @async
 * @function createDepartment
 * @returns {void}
 * 
 * @throws {Error} Displays an error alert if the request fails or if required fields are missing.
 * 
 * @example
 * // Trigger the function when a form is submitted
 * $("#createDepartmentForm").on("submit", function (e) {
 *     e.preventDefault();
 *     createDepartment();
 * });
 */
async function createDepartment() {
    let department_name = $("#department_name").val();
    let department_code = $("#department_code").val();
    let department_extensions = [];
    $("#extensions_list li").each(function () {
        department_extensions.push($(this).attr("value").trim());
    });

    if (isEmpty(department_name)) {
        showAlert("Name is required!", "Error", "error");
        return;
    }

    console.log("Creating department..." + department_name);
    loadingHandler("Creating department...", 1);
    $.ajax({
        url: 'create-department',
        type: "POST",
        dataType: 'json',
        data: {
            department_name: department_name,
            department_code: department_code,
            department_extensions: department_extensions
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while creating the department.", "Error", "error");
                return;
            }
            showAlert("Department created successfully!", "Success", "success");
            window.location.reload(); 
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
            showAlert(xhr.responseJSON?.message || "An error occurred while creating the department.", "Error", "error");
        }
    });
}
/**
 * Deletes a department by its ID after user confirmation.
 * Displays a confirmation alert, handles loading state, and sends an AJAX request to delete the department.
 * Provides feedback to the user based on the success or failure of the operation.
 *
 * @async
 * @function deleteDepartment
 * @param {number|string} department_id - The unique identifier of the department to be deleted.
 * @returns {Promise<void>} Resolves when the operation is complete.
 *
 * @throws {Error} Logs error details to the console if the AJAX request fails.
 *
 * @example
 * // Call the function to delete a department with ID 123
 * deleteDepartment(123);
 */
async function deleteDepartment(department_id) {
    if (await showQuestionAlert("Are you sure?", "Delete Department", "warning")) {
        loadingHandler("Deleting department...", 1);
        $.ajax({
            url: 'delete-department',
            type: "POST",
            dataType: 'json',
            data: { department_id: department_id },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                loadingHandler(null, 0);
                if (response.status !== 'success') {
                    showAlert(response.message || "An error occurred while deleting the department.", "Error", "error");
                    return;
                }
                showAlert("Department deleted successfully!", "Success", "success");
                window.location.reload();
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
                showAlert(xhr.responseJSON?.message || "An error occurred while deleting the department.", "Error", "error");
            }
        });

    }
}
/**
 * Updates the modal with the provided department details and prepares it for editing.
 *
 * @param {number} department_id - The unique identifier of the department.
 * @param {string} department_name - The name of the department.
 * @param {string} department_code - The code associated with the department.
 * @param {string} department_extensions - A comma-separated string of department extensions.
 */
function updateModal(department_id, department_name, department_code, department_extensions) {
    $("#department_name").val(department_name);
    $("#department_code").val(department_code);
    if (isEmpty(department_extensions)) {
        $("#extensions_list").empty();
    } else {
        // Clear the existing list items
        $("#extensions_list").empty();
        // Split the extensions string by commas and spaces, then add each to the list
        let extensions = department_extensions.split(/,\s*/);
        let department_extension_list = $("#extensions_list");
        extensions.forEach(extension => {
            let li = document.createElement("li");
            li.className = "list-item";
            li.value = extension;
            li.innerHTML = "Extension: " + extension + "<span class='icon is-small is-right' onclick='removeExtension(this);'><i class='fa-solid fa-trash'></i></span>";
            department_extension_list.append(li);
        });
    }
    $("#department_extension").val("");
    $("#department_save").attr("onclick", "updateDepartment(" + department_id + ", '" + department_name + "', '" + department_extensions + "')").text("Update");
}

/**
 * Resets and prepares the modal for creating a new department.
 * Clears the input fields for department name and code, empties the extensions list,
 * and updates the save button to trigger the `createDepartment` function with the label "Create".
 */
function createModal() {
    $("#department_name").val("");
    $("#department_code").val("");
    $("#extensions_list").empty();
    $("#department_save").attr("onclick", "createDepartment()").text("Create");
}

/**
 * Updates the details of a department by sending an AJAX POST request to the server.
 *
 * @async
 * @function updateDepartment
 * @param {number|string} department_id - The unique identifier of the department to be updated.
 * @description This function collects the department's updated name, code, and extensions from the DOM,
 *              sends the data to the server, and handles the response. It displays appropriate messages
 *              based on the success or failure of the operation.
 *
 * @example
 * // Call the function to update a department with ID 123
 * updateDepartment(123);
 *
 * @requires jQuery
 * @requires loadingHandler - A function to handle loading states.
 * @requires showAlert - A function to display alert messages to the user.
 *
 * @throws Will log an error to the console if the AJAX request fails.
 */
async function updateDepartment(department_id) {
    let department_name = $("#department_name").val();
    let department_code = $("#department_code").val();
    let department_extensions = [];
    $("#extensions_list li").each(function () {
        department_extensions.push($(this).attr("value").trim());
    });

    loadingHandler("Updating department...", 1);
    $.ajax({
        url: 'update-department',
        type: "POST",
        dataType: 'json',
        data: {
            department_id: department_id,
            department_name: department_name,
            department_code: department_code,
            department_extensions: department_extensions
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            console.log(response);
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while updating the department.", "Error", "error");
                return;
            }
            showAlert("Department updated successfully!", "Success", "success");
            window.location.reload(); 
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
            showAlert(xhr.responseJSON?.message || "An error occurred while updating the department.", "Error", "error");
        }
    });
}
/**
 * Adds a department extension to the extensions list if it is valid and not already present.
 * 
 * This function retrieves the value from the department extension input field, validates it,
 * checks for duplicates in the existing extensions list, and appends it to the list if valid.
 * It also clears the input field after adding the extension.
 * 
 * @function
 * @throws Will display an alert if the extension input is empty or if the extension already exists in the list.
 */
function setExtensionList() {
    let department_extension = $("#department_extension").val();
    if (isEmpty(department_extension)) {
        showAlert("Extension is required!", "Error", "error");
        return;
    }
    // Check if the extension already exists in the list
    let existing_extensions = [];
    $("#extensions_list li").each(function () {
        existing_extensions.push($(this).text().trim());
    });
    if (existing_extensions.includes("Extension " + department_extension)) {
        showAlert("Extension already exists!", "Error", "error");
        return;
    }
    let department_extension_list = $("#extensions_list");
    let li = document.createElement("li");
    li.className = "list-item";
    li.value = department_extension;
    li.innerHTML = "Extension: " + department_extension + "<span class='icon is-small is-right' onclick='removeExtension(this);'><i class='fa-solid fa-trash'></i></span>";
    department_extension_list.append(li);
    // Clear the input field
    $("#department_extension").val("");
}
function removeExtension(element) {
    // Remove the list item
    element.parentElement.remove();
}