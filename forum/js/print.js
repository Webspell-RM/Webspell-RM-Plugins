document.addEventListener("DOMContentLoaded", function () {
    const printButtons = document.querySelectorAll('#printButton'); // Seleziona tutti i pulsanti con l'ID "printButton"

    printButtons.forEach(button => {
        button.addEventListener('click', function () {
            const PrintDiv = document.getElementById('PrintDiv');
            const divContent = PrintDiv.innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `<div>${divContent}</div>`;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        });
    });
});
