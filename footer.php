<div class="container">
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0" style="color: #333;">© 2025 จองแผงตลาดออนไลน์ | All Rights Reserved</p>
            <p style="color: #333;">เว็บไซต์นี้พัฒนาโดย <strong>นายพีรพัฒน์ รักษ์นาค Comsci PBRU</strong></p>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <p><a href="Admin/index.php" style="color: #007bff;">เข้าสู่ระบบผู้ดูแลระบบ</a></p>
            <?php endif; ?>
        </div>
    </footer>
</div>