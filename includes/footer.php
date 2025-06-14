<?php
/**
 * includes/footer.php
 * This file contains the common closing HTML tags and JavaScript includes
 * for all admin panel pages in the Supermarket POS system.
 * It integrates general JavaScript utilities from public/js/main.js
 * and includes the sidebar toggling logic.
 */
?>
        </div> <!-- Closes .main-content -->
    </div> <!-- Closes .main-content-wrapper, assuming it exists based on overall structure -->

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom Global JavaScript -->
    <script src="../public/js/main.js"></script>
    <script>
        // JavaScript for sidebar toggling and responsiveness (reused from header/footer logic)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('sidebar');

            // Toggle sidebar on button click for larger screens
            if (sidebarToggler) { // Check if toggler exists (it won't on small screens where offcanvas is used)
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    sidebarToggler.querySelector('i').classList.toggle('fa-angle-left');
                    sidebarToggler.querySelector('i').classList.toggle('fa-angle-right');
                });
            }

            // Handle Bootstrap Offcanvas for small screens (sidebar)
            var offcanvasSidebar = new bootstrap.Offcanvas(document.getElementById('sidebar'));

            // Show/hide offcanvas via top navbar toggler
            const navbarTogglerSidebar = document.querySelector('.navbar-toggler-sidebar');
            if (navbarTogglerSidebar) {
                navbarTogglerSidebar.addEventListener('click', function() {
                    offcanvasSidebar.toggle();
                });
            }

            // Prevent body scroll when offcanvas is open
            document.getElementById('sidebar').addEventListener('show.bs.offcanvas', function () {
                document.body.style.overflow = 'hidden';
            });
            document.getElementById('sidebar').addEventListener('hide.bs.offcanvas', function () {
                document.body.style.overflow = '';
            });

            // Adjust sidebar behavior based on screen size (for initial load and resize)
            function adjustSidebarOnResize() {
                if (window.innerWidth >= 768) {
                    // On larger screens, ensure sidebar is not off-canvas
                    if (sidebar.classList.contains('show')) {
                        offcanvasSidebar.hide();
                    }
                }
            }

            // Call on initial load
            adjustSidebarOnResize();
            // Call on window resize
            window.addEventListener('resize', adjustSidebarOnResize);
        });
    </script>
</body>
</html>
