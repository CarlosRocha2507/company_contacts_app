async function createDepartment() {
    let department_name = $("#department_name").val();
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
            department_extensions: department_extensions
        },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            console.log(response);
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while creating the department.", "Error", "error");
                return;
            }
            showAlert("Department created successfully!", "Success", "success");
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
async function deleteDepartment(department_id) {
    if (await showQuestionAlert("Are you sure?", "Delete Department", "warning")) {
        console.log("Deleting department..." + department_id);
        loadingHandler("Deleting department...", 1);
        $.ajax({
            url: 'delete-department',
            type: "POST",
            dataType: 'json',
            data: { department_id: department_id },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                console.log(response);
                loadingHandler(null, 0);
                if (response.status !== 'success') {
                    showAlert(response.message || "An error occurred while deleting the department.", "Error", "error");
                    return;
                }
                showAlert("Department deleted successfully!", "Success", "success");
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
function updateModal(department_id, department_name, department_extensions) {
    $("#department_name").val(department_name);
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

function createModal() {
    $("#department_name").val("");
    $("#department_save").attr("onclick", "createDepartment()").text("Create");
}

async function updateDepartment(department_id) {
    let department_name = $("#department_name").val();
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