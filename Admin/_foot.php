<script src="/lib/jquery.min.js"></script>
<script src="/js/adminscript.js"></script>
<script src="/js/app_admin.js"></script>
<script>
    var loader = document.getElementById("preloader");
    window.addEventListener("load", function() {
        setTimeout(function() {
            loader.style.transform = "translateY(-100%)";
        }, <?php echo rand(150, 150); ?>);

        setTimeout(function() {
            loader.style.display = "none";
        }, 0);
    });

    <?php if (isset($toastType) && isset($toastMessage)): ?>
        window.onload = function() {
            createToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        };
    <?php endif; ?>
</script>
</div>
</section>
</body>

</html>