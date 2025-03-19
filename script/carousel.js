$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        loop: true, // ให้สไลด์วนไปเรื่อยๆ
        margin: 15, // ระยะห่างระหว่างการ์ด
        nav: true, // แสดงปุ่มเลื่อนซ้าย-ขวา
        autoplay: true, // เลื่อนอัตโนมัติ
        autoplayTimeout: 3000, // เวลาเลื่อน (3 วินาที)
        autoplayHoverPause: true, // หยุดเมื่อเอาเมาส์ไปวาง
        responsive: {
            0: {
                items: 1,
            }, // แสดง 1 อันเมื่อหน้าจอเล็ก
            600: {
                items: 2,
            }, // แสดง 2 อันเมื่อหน้าจอกลาง
            1000: {
                items: 3,
            }, // แสดง 3 อันเมื่อหน้าจอใหญ่
        },
    });
});
