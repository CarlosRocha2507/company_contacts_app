var try_number = 3;
/**
 * Prompts the user to enter a department code and verifies it against the provided code.
 * If the code is incorrect, the user is given a limited number of attempts to retry.
 * If the code is correct, it proceeds to fetch contacts for the specified department.
 * If the user cancels or exhausts all attempts, they are redirected to the homepage.
 *
 * @param {string} department_code - The correct department code to verify against.
 * @param {number} department_id - The ID of the department for which contacts are to be fetched.
 */
function gestCodeVerification(department_code, department_id) {
    Swal.fire({
        title: 'Enter your department code',
        input: 'text',
        inputPlaceholder: 'Department code',
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to enter a code!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const userCode = result.value;
            if (userCode != department_code) {
                try_number--;
                if (try_number > 0) {
                    Swal.fire({
                        title: `Incorrect code! You have ${try_number} tries left.`,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Try again',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            gestCodeVerification(department_id);
                        } else {
                            window.location.href = "/";
                        }
                    });
                    return;
                }
                window.location.href = "/";
                return;
            }
            getContactsForGests(department_id);
        } else {
            window.location.href = "/";
        }
    });
}
/**
 * Fetches and displays contacts for a specific department.
 * Sends an AJAX POST request to retrieve contact information associated with the given department ID.
 * Handles loading states, success responses, and error responses appropriately.
 *
 * @param {number|string} department_id - The ID of the department for which to fetch contacts.
 * 
 * @returns {void}
 */
function getContactsForGests(department_id) {
    loadingHandler("Loading contacts...", 1);
    $.ajax({
        url: 'get-gest-contacts',
        type: "POST",
        dataType: 'json',
        data: { department: department_id },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (response) {
            loadingHandler(null, 0);
            if (response.status !== 'success') {
                showAlert(response.message || "An error occurred while gesting contacts information.", "Error", "error");
                return;
            }
            $("#gests-contacts").html(response.data);
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
            showAlert(xhr.responseJSON?.message || "An error occurred while gesting contacts information.", "Error", "error");
        }
    });
}