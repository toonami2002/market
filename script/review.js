document.addEventListener("DOMContentLoaded", function() {
    const moreReviews = document.querySelectorAll(".more-reviews");
    const showMoreBtn = document.getElementById("showMoreBtn");

    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function() {
            moreReviews.forEach(review => review.classList.remove("d-none")); // แสดงรีวิวที่ซ่อนอยู่
            showMoreBtn.style.display = "none"; // ซ่อนปุ่มเมื่อกดแล้ว
        });
    }
});