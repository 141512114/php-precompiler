// Accordion initialisieren
document.querySelectorAll('.accordion-button').forEach(button => {
    const targetId = button.getAttribute('data-bs-target');
    const targetEl = document.querySelector(targetId);

    // Bootstrap Collapse nur für diesen Abschnitt aktivieren
    new bootstrap.Collapse(targetEl, { toggle: false });
});