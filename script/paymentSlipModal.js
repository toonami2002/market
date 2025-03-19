var paymentSlipModal = document.getElementById('paymentSlipModal')
paymentSlipModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget // ปุ่มที่เปิด Modal
    var paymentSlip = button.getAttribute('data-bs-payment-slip') // ดึงชื่อไฟล์สลิป
    var modalBody = paymentSlipModal.querySelector('.modal-body #paymentSlipImage')
    modalBody.src = '../uploads/slips/' + paymentSlip
})