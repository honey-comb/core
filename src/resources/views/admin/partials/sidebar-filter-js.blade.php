<script>
    $(document).ready(function () {
        $(".search-menu-box").on('input', function () {
            var filterVal = $(this).val();
            var menuList = $(".sidebar-menu");

            filterMenu(menuList, filterVal)
        });

        function filterMenu(ulDiv, filterValue) {
            ulDiv.find('li').each(function () {
                if ($(this).text().search(new RegExp(filterValue, "i")) < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }

                if ($(this).children('ul').length) {
                    filterMenu($(this).children('ul'), filterValue);
                }
            });
        }
    });
</script>