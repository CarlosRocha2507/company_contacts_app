function createModal() {
    $("#department_id").val("0");
    $("#contact_extension_div").html("<select class='input' name='department_extension' id='department_extension' disabled><option value='0' selected>Select a department first</option></select>");
    $("#contact_number").val("");
    $("#country_id").val("+351");
    $("#contact_email").val("");
    $("#contact_save").attr("onclick", "createContact()").text("Create");
}
async function updateModal(department_id, contact_id, contact_number, country_id, contact_email, extension) {
    $("#department_id").val(department_id);
    getDepartmentExtension(extension);
    $("#contact_id").val(contact_id);
    $("#contact_number").val(contact_number);
    $("#country_id").val(country_id);
    $("#contact_email").val(contact_email);
    $("#contact_save").attr("onclick", "updateContact(" + contact_id + ")").text("Update");
}
async function createContact() {
    let department_id = $("#department_id").find(":selected").val();
    let extension = $("#department_extension").find(":selected").val();
    let contact = $("#contact_number").val();
    let country_number = $("#country_id").find(":selected").val();
    let contact_email = $("#contact_email").val();

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
            contact_email: contact_email
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while creating the contact.", "Error", "error");
                return;
            }
            showAlert("Contact created successfully!", "Success", "success");
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
async function updateContact(contact_id) {
    let department_id = $("#department_id").find(":selected").val();
    let contact = $("#contact_number").val();
    let country_number = $("#country_id").find(":selected").val();
    let contact_email = $("#contact_email").val();

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
            contact_id: contact_id
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while updating the contact.", "Error", "error");
                return;
            }
            showAlert("Contact updated successfully!", "Success", "success");
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

$("#department_id").change(function () {
    let selectedValue = $(this).val();
    if (selectedValue && selectedValue !== "0") {
        getDepartmentExtension(0);
    }
});