// เมื่อคลิกที่ปุ่ม hamburger ให้เปิด/ปิดเมนู
const toggler = document.querySelector('.navbar-toggler');
const navbarCollapse = document.querySelector('#navbarNav');

toggler.addEventListener('click', () => {
    navbarCollapse.classList.toggle('show');
});
