async function createDepartment() {
    let department_name = $("#department_name").val();
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
        data: { department_name: department_name },
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
function updateModal(department_id, department_name) {
    $("#department_name").val(department_name);
    $("#department_save").attr("onclick", "updateDepartment(" + department_id + ", '" + department_name + "')").text("Update");
}

function createModal() {
    $("#department_name").val("");
    $("#department_save").attr("onclick", "createDepartment()").text("Create");
}   

async function updateDepartment(department_id, department_name) {
    if (isEmpty(department_name)) {
        showAlert("Name is required!", "Error", "error");
        return;
    }

    console.log("Updating department..." + department_id);
    loadingHandler("Updating department...", 1);
    $.ajax({
        url: 'update-department',
        type: "POST",
        dataType: 'json',
        data: { department_id: department_id, department_name: department_name },
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