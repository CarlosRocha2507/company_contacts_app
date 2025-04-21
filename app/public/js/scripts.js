function isEmpty(value) {
    return (value == null || (typeof value === "string" && value.trim().length === 0));
}
function showAlert(message, title, type) {
    Swal.fire({
        icon: type,
        title: title,
        text: message,
        footer: '<a href="mailto:youremail@gmail.com">Need some help?</a>'
    });
}
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
function loadingHandler(text, state) {
    if (state == 1) {
        $("#loadingScreen").show();
        $("#loadingText").text(text);
    } else {
        $("#loadingScreen").hide();
        $("#loadingText").text("A carregar...");
    }
}
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
