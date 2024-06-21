document.addEventListener('DOMContentLoaded', function () {
    var otraRazonRadio = document.getElementById('otraRazonRadio');
    var otherReasonContainer = document.getElementById('otherReasonContainer');
    var otherReasonText = document.getElementById('otherReasonText');
    var reasonRadios = document.getElementsByName('reason');

    reasonRadios.forEach(function(radio) {
        radio.addEventListener('change', function () {
            if (otraRazonRadio.checked) {
                otherReasonContainer.style.display = 'block';
                otherReasonText.required = true;
            } else {
                otherReasonContainer.style.display = 'none';
                otherReasonText.required = false;
            }
        });
    });
});