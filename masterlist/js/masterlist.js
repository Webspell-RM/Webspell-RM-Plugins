    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".server-row").forEach(row => {
            row.addEventListener("click", function() {
                document.querySelectorAll(".server-details").forEach(detail => {
                    if (detail.id !== row.dataset.bsTarget.substring(1)) {
                        detail.classList.remove("show");
                    }
                });
            });
        });
    });